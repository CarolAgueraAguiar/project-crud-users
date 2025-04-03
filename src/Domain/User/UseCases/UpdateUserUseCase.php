<?php

namespace Domain\User\UseCases;

use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Support\Services\CepService;

class UpdateUserUseCase
{
    public function __construct(
        private readonly CepService $cepService,
        private readonly UserRepository $userRepository
    ) {}

    public function __invoke(int $userId, array $data)
    {
        $user = $this->userRepository->find($userId);

        $prevZipCode = $user->zip_code;
        $newZipCode = $data['zip_code'] ?? null;
        $isUpdatedZipCode = ($newZipCode && $newZipCode !== $prevZipCode);
        $cepData = $isUpdatedZipCode ? $this->cepService->search($newZipCode) : null;

        $buildedUserData = $this->buildUserData($data, $cepData);
        $this->userRepository->update($userId, $buildedUserData);
    }

    private function buildUserData(
        array $userData,
        ?array $cepData
    ): array {
        $password = $userData['password'] ?? null;
        $passwordData =  $password ? ['passowrd' => Hash::make($password)] : [];
        $cepData = $cepData ? [
            'address' => $cepData['logradouro'],
            'neighborhood' => $cepData['bairro'],
            'city' => $cepData['localidade'],
            'state' => $cepData['uf'],
        ] : [];

        return [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'zip_code' => $userData['zip_code'],
            'address_number' => $userData['address_number'],
            'address_complement' => $userData['address_complement'],
            ...$passwordData,
            ...$cepData,
        ];
    }
}
