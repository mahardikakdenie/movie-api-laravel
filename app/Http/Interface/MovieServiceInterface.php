<?php

namespace App\Http\Interface;

interface MovieServiceInterface
{
    public function get_data_movie(array $payloads);
    public function create_movie_data(array $payloads);
}
