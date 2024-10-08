<?php

namespace App\Http\Service;

use App\Helpers\ResponseFormatter;
use App\Http\Interface\AuthRepositoryInterface;
use App\Http\Interface\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected $auth_repo;
    public function __construct(AuthRepositoryInterface $auth_repo)
    {
        $this->auth_repo = $auth_repo;
    }

    public function registerService(array $pipeline)
    {
        if ($pipeline['email'] && $pipeline['name'] && $pipeline['password']) {
            $auth = $this->auth_repo->create($pipeline);
        }

        return $auth || ['message' => 'error'];
    }

    public function loginService(array $pipeline)
    {
        $match = [
            'email' => $pipeline['email'],
        ];

        $user = $this->auth_repo->get_one_raw($pipeline);
        if (! $user || ! Hash::check($pipeline['password'], $user->password)) {
            return ResponseFormatter::error('password anda salah');
        }

        $token = $user->createToken($user)->plainTextToken;

        return $token;
    }
}
