<?php

namespace Tests\Unit\Helpers;

use App\Helpers\PasswordHelper;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordHelperTest extends TestCase
{
    public function testHashSha1()
    {
        \Config::set('maplebit.password_hashing_algo', 'sha1');
        $this->assertEquals(PasswordHelper::hash('test'), 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');
    }

    public function testHashBcrypt()
    {
        \Config::set('maplebit.password_hashing_algo', 'bcrypt');
        $this->assertTrue(Hash::check('test', PasswordHelper::hash('test')));
    }
}
