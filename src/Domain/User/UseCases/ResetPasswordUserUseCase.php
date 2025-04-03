<?php

namespace Domain\User\UseCases;

use App\Models\PasswordResetToken;
use Carbon\Carbon;
use Domain\PasswordResetToken\Repositories\PasswordResetTokenRepository;
use Domain\User\Repositories\UserRepository;
use Illuminate\Http\Response;
use Support\Exceptions\ErrorException;
use Illuminate\Support\Facades\Hash;

class ResetPasswordUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordResetTokenRepository $passwordResetTokenRepository
    ) {}
    public function __invoke(string $email, string $password, string $token)
    {
        $tokenRecord = $this->passwordResetTokenRepository
        ->findByEmail($email);
        throw_if(!$tokenRecord, new ErrorException('E-mail inválido', Response::HTTP_UNPROCESSABLE_ENTITY));

        $isValidHash = Hash::check($token, $tokenRecord->token);
        throw_if(!$isValidHash, new ErrorException('Token inválido', Response::HTTP_UNPROCESSABLE_ENTITY));

        $createdAt = Carbon::parse($tokenRecord->created_at);
        $isExpired = $createdAt->addMinutes(60)->isPast();
        throw_if($isExpired, new ErrorException('Token expirado', Response::HTTP_UNPROCESSABLE_ENTITY));

        $user = $this->userRepository->findByEmail($email);
        $newPasswordHash = Hash::make($password);
        $this->userRepository->update($user->id, ['password' => $newPasswordHash]);

        $this->passwordResetTokenRepository->deleteByEmail($email);
    }
}
