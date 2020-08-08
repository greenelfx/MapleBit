<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The table associated with Users.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token', 'site_password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Override default password field to use site_password.
     *
     * @return site_password
     */
    public function getAuthPassword()
    {
        return $this->site_password;
    }

    public function getBasicInfo()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'gravatar_url' => $this->getGravatar(),
            'profile' => $this->profile,
        ];
    }

    public function getGravatar()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s=40&amp;d=identicon&amp;r=g';
    }

    /**
     * Get the profile associated with user.
     */
    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'account_id', 'id')->withDefault([
            'name' => \Atrox\Haikunator::haikunate(),
        ]);
    }
}
