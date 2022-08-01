<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
<<<<<<< HEAD
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
    ];
=======
    protected $guarded = [];
>>>>>>> a2b680974174f969dabfe616c9894ad3329f2225

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendPasswordResetNotification($token)
    {
<<<<<<< HEAD
        
=======

>>>>>>> a2b680974174f969dabfe616c9894ad3329f2225
        $url = 'https://localhost/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }
<<<<<<< HEAD


=======
>>>>>>> a2b680974174f969dabfe616c9894ad3329f2225
}
