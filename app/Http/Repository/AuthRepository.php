<?php

namespace App\Http\Repository;

use App\Http\Interface\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function get_raw(array $pipeline) {}
    public function create(array $pipeline)
    {
        // Create the user
        $user = User::create([
            'name' => $pipeline['name'],
            'email' => $pipeline['email'],
            'password' => Hash::make($pipeline['password']),
        ]);

        $token = $user->createToken($user)->plainTextToken;

        return $token;
    }

    public function get_one_raw(array $pipeline)
    {
        return User::where('email', $pipeline['email'])->first();
    }
}
