<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PasswordResetToken;
use App\Jobs\SendPasswordResetEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController
{

    use HasApiTokens;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'zip_code' => 'required|string|size:8',
            'address_number' => 'required|string|max:20',
            'address_complement' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Consulta a API ViaCEP
        $cepResponse = Http::get("https://viacep.com.br/ws/{$request->zip_code}/json/");

        if ($cepResponse->failed() || isset($cepResponse->json()['erro'])) {
            return response()->json(['message' => 'CEP inválido'], 400);
        }

        $cepData = $cepResponse->json();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'zip_code' => $request->zip_code,
            'address_number' => $request->address_number,
            'address_complement' => $request->address_complement,
            'address' => $cepData['logradouro'],
            'neighborhood' => $cepData['bairro'],
            'city' => $cepData['localidade'],
            'state' => $cepData['uf'],
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'zip_code' => 'required|string|size:8',
            'address_number' => 'required|string|max:20',
            'address_complement' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Se o CEP foi alterado, atualiza o endereço
        if (isset($data['zip_code']) && $data['zip_code'] !== $user->zip_code) {
            $cepResponse = Http::get("https://viacep.com.br/ws/{$data['zip_code']}/json/");

            if ($cepResponse->successful() && !isset($cepResponse->json()['erro'])) {
                $cepData = $cepResponse->json();
                $data = array_merge($data, [
                    'address' => $cepData['logradouro'],
                    'neighborhood' => $cepData['bairro'],
                    'city' => $cepData['localidade'],
                    'state' => $cepData['uf'],
                ]);
            }
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * User login
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);
        $user = User::where('email', $loginUserData['email'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout realizado com sucesso']);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['message' => 'Token inválido ou expirado'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro durante o logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'E-mail não encontrado'], 404);
        }

        $plainToken = Str::random(60);

        $hashedToken = Hash::make($plainToken);

        PasswordResetToken::updateOrCreateToken($user->email, $hashedToken);

        SendPasswordResetEmail::dispatch($user, $plainToken);

        return response()->json(['message' => 'Link de redefinição enviado']);
    }

    /**
     * Reseta a senha do usuário
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        $tokenRecord = PasswordResetToken::where('email', $request->email)->first();

        if (!$tokenRecord) {
            return response()->json(['error' => 'Token inválido'], 422);
        }

        if (!Hash::check($request->token, $tokenRecord->token)) {
            return response()->json(['error' => 'Token inválido'], 422);
        }

        $createdAt = Carbon::parse($tokenRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            return response()->json(['error' => 'Token expirado'], 422);
        }

        //FIXME - Verificar se da para mudar para esse metodo
        // $tokenExpiredNew = PasswordResetToken::isExpired();

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetToken::deleteByEmail($request->email);

        return response()->json(['message' => 'Senha redefinida com sucesso']);
    }
}
