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
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'account_id'
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
}
