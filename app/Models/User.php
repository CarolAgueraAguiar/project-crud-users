<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use  HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'zip_code',
        'address_number',
        'address_complement',
        'address',
        'neighborhood',
        'city',
        'state'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // /**
    //  * Get the indexable data array for the model.
    //  *
    //  * @return array
    //  */
    // public function toSearchableArray()
    // {
    //     return [
    //         'name' => $this->name,
    //         'email' => $this->email,
    //         'address' => $this->address,
    //         'neighborhood' => $this->neighborhood,
    //         'city' => $this->city,
    //     ];
    // }

    // /**
    //  * Mutator for zip_code - ensures consistent formatting
    //  */
    // public function setZipCodeAttribute($value)
    // {
    //     $this->attributes['zip_code'] = preg_replace('/[^0-9]/', '', $value);
    // }

    // /**
    //  * Accessor for formatted zip_code
    //  */
    // public function getFormattedZipCodeAttribute()
    // {
    //     $zip = $this->zip_code;
    //     return substr($zip, 0, 5) . '-' . substr($zip, 5);
    // }

    // /**
    //  * Get the full address
    //  */
    // public function getFullAddressAttribute()
    // {
    //     return sprintf(
    //         '%s, %s, %s - %s, %s, %s',
    //         $this->address,
    //         $this->address_number,
    //         $this->address_complement,
    //         $this->neighborhood,
    //         $this->city,
    //         $this->state
    //     );
    // }
}
