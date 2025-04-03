<?php

namespace Domain\User\UseCases;

use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Support\Services\CepService;

class StoreUserUseCase
{
    public function __construct(
        private readonly CepService $cepService,
        private readonly UserRepository $userRepository
    ) {}

    public function __invoke(array $data)
    {
        $cepData = $this->cepService->search($data['zip_code']);
        $buildedUserData = $this->buildUserData($data, $cepData);
        $this->userRepository->create($buildedUserData);
    }

    private function buildUserData(
        array $userData,
        array $cepData
    ): array {
        return [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'zip_code' => $userData['zip_code'],
            'address_number' => $userData['address_number'],
            'address_complement' => $userData['address_complement'],
            'address' => $cepData['logradouro'],
            'neighborhood' => $cepData['bairro'],
            'city' => $cepData['localidade'],
            'state' => $cepData['uf'],
        ];
    }
}
