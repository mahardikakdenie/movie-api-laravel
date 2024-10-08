<?php

namespace App\Http\Service;

use App\Http\Interface\MovieRepositoryInterface;
use App\Http\Interface\MovieServiceInterface;

class MovieService implements MovieServiceInterface
{
    protected $movie_repo;
    public function __construct(MovieRepositoryInterface $movie_repo)
    {
        $this->movie_repo = $movie_repo;
    }

    public function get_data_movie(array $payloads)
    {
        return $this->movie_repo->get_many($payloads);
    }

    public function create_movie_data(array $payloads)
    {
        $is_field_not_valid = array_filter($payloads, function ($curr) {
            return $curr === '' || $curr === null;
        });

        return $this->movie_repo->create($payloads);
    }
}
