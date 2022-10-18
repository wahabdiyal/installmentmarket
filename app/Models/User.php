<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
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

    /**
     * Get all of the companyFollows for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyFollows(): HasMany
    {
        return $this->hasMany(UserCompanyFollow::class);
    }

    /**
     * Get all of the companyFavourite for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyFavourite(): HasMany
    {
        return $this->hasMany(UserCompanyFavourite::class);

        // ->join('companies','companies.id','=','user_company_favourites.company_id')->get();
    }

    /**
     * Get all of the productFavourite for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productFavourite(): HasMany
    {
        return $this->hasMany(UserProductFavourite::class);
    }

    /**
     * Get all of the companys for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companys(): HasMany
    {
        return $this->hasMany(Company::class);
    }


}
