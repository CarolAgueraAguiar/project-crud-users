<?php

namespace Domain\PasswordResetToken\Model;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'password_reset_tokens';

    /**
     * A chave primária da tabela
     *
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * Indica que a chave primária não é auto-incrementável
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * O tipo da chave primária
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indica se o model tem timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    /**
     * Converte o created_at para instância de Carbon
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    // /**
    //  * Busca um token por email
    //  *
    //  * @param string $email
    //  * @return PasswordResetToken|null
    //  */
    // public static function findByEmail(string $email): ?PasswordResetToken
    // {
    //     return static::where('email', $email)->first();
    // }

    // /**
    //  * Verifica se o token expirou
    //  *
    //  * @param int $expiresInMinutes
    //  * @return bool
    //  */
    // public function isExpired(int $expiresInMinutes = 60): bool
    // {
    //     return $this->created_at->addMinutes($expiresInMinutes)->isPast();
    // }

    // /**
    //  * Cria ou atualiza um token
    //  *
    //  * @param string $email
    //  * @param string $token
    //  * @return PasswordResetToken
    //  */
    // public static function updateOrCreateToken(string $email, string $token): PasswordResetToken
    // {
    //     return static::updateOrCreate(
    //         ['email' => $email],
    //         [
    //             'token' => $token,
    //             'created_at' => now()
    //         ]
    //     );
    // }

    // /**
    //  * Deleta um token por email
    //  *
    //  * @param string $email
    //  * @return bool
    //  */
    // public static function deleteByEmail(string $email): bool
    // {
    //     return static::where('email', $email)->delete() > 0;
    // }
}
