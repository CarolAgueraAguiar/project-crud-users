<?php

namespace Domain\User\UseCases;

use Domain\PasswordResetToken\Repositories\PasswordResetTokenRepository;
use Domain\User\Jobs\SendPasswordResetEmail;
use Domain\User\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Support\Exceptions\ErrorException;
use Illuminate\Support\Str;

class ForgotPasswordUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordResetTokenRepository $passwordResetTokenRepository
    ) {}

    public function __invoke(string $email)
    {
        $user = $this->userRepository->findByEmail($email);
        throw_if(!$user, new ErrorException('E-mail não encontrado', Response::HTTP_NOT_FOUND));
        $plainToken = Str::random(60);
        $hashedToken = Hash::make($plainToken);
        $this->passwordResetTokenRepository
            ->updateOrCreateToken($user->email, $hashedToken);

        SendPasswordResetEmail::dispatch($user, $plainToken);
    }
}
