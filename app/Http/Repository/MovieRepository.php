<?php

namespace App\Http\Repository;

use App\Http\Interface\MovieRepositoryInterface;
use App\Models\Movie;

class MovieRepository implements MovieRepositoryInterface
{
    public function get_many(array $payloads)
    {
        $limit = $payloads['limit'] ?? 10;
        $movie = Movie::paginate($limit);

        return $movie;
    }

    public function create(array $payloads)
    {
        $movie = Movie::create([
            'title' => $payloads['title'],
            'publish' => $payloads['publish'],
            'description' => $payloads['description'],
        ]);

        return $movie;
    }
}
