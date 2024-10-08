<?php

namespace App\Http\Interface;

interface AuthRepositoryInterface
{
    public function get_raw(array $pipeline);
    public function create(array $pipeline);
    public function get_one_raw(array $pipeline);
}
