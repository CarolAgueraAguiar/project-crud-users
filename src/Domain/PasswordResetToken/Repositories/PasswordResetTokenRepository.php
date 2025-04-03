<?php

namespace Domain\PasswordResetToken\Repositories;

use Domain\PasswordResetToken\Model\PasswordResetToken;

class PasswordResetTokenRepository
{
    public function findByEmail(string $email): ?PasswordResetToken
    {
        return PasswordResetToken::where('email', $email)->first();
    }

    public function updateOrCreateToken(string $email, string $token)
    {
        PasswordResetToken::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );
    }
    public function deleteByEmail(string $email)
    {
        PasswordResetToken::where('email', $email)->delete();
    }

    // PasswordResetToken::updateOrCreateToken($user->email, $hashedToken); //NOTE - change to repository

    // public function create(array $data)
    // {
    //     User::create($data);
    // }

    // public function update(int $id, array $data){
    //     User::find($id)->update($data);
    // }
}
