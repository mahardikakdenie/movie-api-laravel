<?php

namespace App\Http\Repository;

use App\Http\Interface\MovieRepositoryInterface;
use App\Models\Movie;

class MovieRepository implements MovieRepositoryInterface
{
    public function get_many(array $payloads)
    {
        $limit = $payloads['limit'] ?? 10;
        $entities = $payloads['entities'] ?? '';
        $movie = Movie::entities($entities)->paginate($limit);

        return $movie;
    }

    public function create(array $payloads)
    {
        $movie = Movie::create([
            'title' => $payloads['title'],
            'publish' => $payloads['publish'],
            'description' => $payloads['description'],
            'media_id' => $payloads['media_id'],
        ]);

        $movie->media;


        return $movie;
    }

    public function update(string $id, array $payloads)
    {
        $movie = Movie::entities('media')->findOrFail($id);
        $movie->update([
            'title' => $payloads['title'] ?? $movie->title,
            'publish' => $payloads['publish'] ?? $movie->publish,
            'description' => $payloads['description'] ?? $movie->description,
            'media_id' => $payloads['media_id'] ?? $movie->media_id,
        ]);

        $movie->media;

        return $movie;
    }
}
