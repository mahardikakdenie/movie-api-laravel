<?php

namespace App\Http\Interface;

interface AuthServiceInterface
{
    public function registerService(array $pipeline);
    public function loginService(array $pipeline);
}
