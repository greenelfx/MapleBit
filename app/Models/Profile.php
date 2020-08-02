<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * Get the user associated with profile.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'account_id', 'id');
    }

    /**
     * Hide some sensitive fields like the user model if loaded, account id, and timestamps
     *
     * @var array
     */
    protected $hidden = [
        'id', 'account_id', 'updated_at', 'created_at', 'user'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'age',
        'country',
        'motto',
        'about'
    ];

    /**
     * The attributes that are computed upon the Profile model
     *
     * @var array
     */    
    protected $appends = ['associated_user_gravatar'];

    
    public function getAssociatedUserGravatarAttribute()
    {
        return $this->user->getGravatar();
    }
}
