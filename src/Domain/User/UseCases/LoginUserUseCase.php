<?php

namespace Domain\User\UseCases;

use Domain\User\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Support\Exceptions\ErrorException;

class LoginUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function __invoke(string $email, string $password): string
    {
        $user = $this->userRepository->findByEmail($email);
        $isInvalidCredentials = !$user || !Hash::check($password, $user->password);
        throw_if($isInvalidCredentials, new ErrorException('Invalid Credentials', Response::HTTP_UNAUTHORIZED));
        return $user->createToken($user->name . '-AuthToken')->plainTextToken;
    }
}
