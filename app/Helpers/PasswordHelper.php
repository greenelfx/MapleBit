<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;

class PasswordHelper
{
    public static function hash($password)
    {
        if (config('maplebit.password_hashing_algo') == 'bcrypt') {
            return Hash::make($password);
        }

        return sha1($password);
    }
}
