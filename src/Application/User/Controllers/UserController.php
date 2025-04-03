<?php

namespace Application\User\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Application\User\Requests\UserForgotPasswordRequest;
use Application\User\Requests\UserLoginRequest;
use Application\User\Requests\UserResetPasswordRequest;
use Illuminate\Validation\ValidationException;
use Application\User\Requests\UserStoreRequest;
use Application\User\Requests\UserUpdateRequest;
use Domain\User\Model\User;
use Domain\User\UseCases\ForgotPasswordUserUseCase;
use Domain\User\UseCases\LoginUserUseCase;
use Domain\User\UseCases\ResetPasswordUserUseCase;
use Domain\User\UseCases\StoreUserUseCase;
use Domain\User\UseCases\UpdateUserUseCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
    public function store(UserStoreRequest $request, StoreUserUseCase $useCase)
    {
        $useCase($request->validated());
        return response()->json(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }

    public function show()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, UpdateUserUseCase $useCase)
    {
        $userId = Auth::user()->id;
        $useCase($userId, $request->validated());
        return response()->json(['message' => 'User updated successfully'], Response::HTTP_OK);

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:100',
        //     'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
        //     'password' => [
        //         'required',
        //         'confirmed',
        //         Password::min(8)
        //             ->letters()
        //             ->mixedCase()
        //             ->numbers()
        //             ->symbols()
        //     ],
        //     'zip_code' => 'required|string|size:8',
        //     'address_number' => 'required|string|max:20',
        //     'address_complement' => 'nullable|string|max:100',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // $data = $validator->validated();

        // if (isset($data['password'])) {
        //     $data['password'] = Hash::make($data['password']);
        // }

        // // Se o CEP foi alterado, atualiza o endereço
        // if (isset($data['zip_code']) && $data['zip_code'] !== $user->zip_code) {
        //     $cepResponse = Http::get("https://viacep.com.br/ws/{$data['zip_code']}/json/");

        //     if ($cepResponse->successful() && !isset($cepResponse->json()['erro'])) {
        //         $cepData = $cepResponse->json();
        //         $data = array_merge($data, [
        //             'address' => $cepData['logradouro'],
        //             'neighborhood' => $cepData['bairro'],
        //             'city' => $cepData['localidade'],
        //             'state' => $cepData['uf'],
        //         ]);
        //     }
        // }

        // $user->update($data);

        // return response()->json([
        //     'message' => 'User updated successfully',
        //     'user' => $user
        // ]);
    }

    public function destroy()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * User login
     * @throws ValidationException
     */
    public function login(UserLoginRequest $request, LoginUserUseCase $useCase)
    {
        $data = $request->validated();
        $token = $useCase($data['email'], $data['password']);
        return response()->json(['access_token' => $token]);



        // $user = User::where('email', $loginUserData['email'])->first();
        // if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
        //     return response()->json([
        //         'message' => 'Invalid Credentials'
        //     ], 401);
        // }
        // $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        // return response()->json([
        //     'access_token' => $token,
        // ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout realizado com sucesso']);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['message' => 'Token inválido ou expirado'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro durante o logout',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forgotPassword(UserForgotPasswordRequest $request, ForgotPasswordUserUseCase $useCase)
    {
        $data = $request->validated();
        $useCase($data['email']);
        return response()->json(['message' => 'Link de redefinição enviado']);

        // $user = User::where('email', $request->email)->first();

        // if (!$user) {
        //     return response()->json(['message' => 'E-mail não encontrado'], 404);
        // }

        // $plainToken = Str::random(60);

        // $hashedToken = Hash::make($plainToken);

        // PasswordResetToken::updateOrCreateToken($user->email, $hashedToken);

        // SendPasswordResetEmail::dispatch($user, $plainToken);

        // return response()->json(['message' => 'Link de redefinição enviado']);
    }

    /**
     * Reseta a senha do usuário
     */
    public function resetPassword(UserResetPasswordRequest $request, ResetPasswordUserUseCase $useCase)
    {
        $data = $request->validated();
        $useCase($data['email'], $data['password'], $data['token']);

        return response()->json(['message' => 'Senha redefinida com sucesso']);

        // $request->validate([
        //     'token' => 'required',
        //     'email' => 'required|email',
        //     'password' => [
        //         'required',
        //         'confirmed',
        //         Password::min(8)
        //             ->letters()
        //             ->mixedCase()
        //             ->numbers()
        //             ->symbols()
        //     ],
        // ]);

        // $tokenRecord = PasswordResetToken::where('email', $request->email)->first();

        // if (!$tokenRecord) {
        //     return response()->json(['error' => 'Token inválido'], 422);
        // }

        // if (!Hash::check($request->token, $tokenRecord->token)) {
        //     return response()->json(['error' => 'Token inválido'], 422);
        // }

        // $createdAt = Carbon::parse($tokenRecord->created_at);
        // if ($createdAt->addMinutes(60)->isPast()) {
        //     return response()->json(['error' => 'Token expirado'], 422);
        // }

        // //FIXME - Verificar se da para mudar para esse metodo
        // // $tokenExpiredNew = PasswordResetToken::isExpired();

        // $user = User::where('email', $request->email)->first();
        // $user->password = Hash::make($request->password);
        // $user->save();

        // PasswordResetToken::deleteByEmail($request->email);

        // return response()->json(['message' => 'Senha redefinida com sucesso']);
    }
}
