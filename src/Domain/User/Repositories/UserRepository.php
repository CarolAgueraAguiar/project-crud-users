<?php

namespace Domain\User\Repositories;

use Domain\User\Model\User;

class UserRepository
{
    public function find(int $id): User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data)
    {
        User::create($data);
    }

    public function update(int $id, array $data){
        User::find($id)->update($data);
    }
}
