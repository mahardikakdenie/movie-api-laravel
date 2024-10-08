<?php

namespace App\Http\Interface;

interface MovieRepositoryInterface
{
    public function get_many(array $payloads);
    public function create(array $payloads);
}
