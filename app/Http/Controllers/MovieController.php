<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Interface\MovieServiceInterface;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movie_service;
    public function __construct(MovieServiceInterface $movie_service)
    {
        $this->movie_service = $movie_service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $payloads = [];
            $movie = $this->movie_service->get_data_movie($payloads);

            return ResponseFormatter::success($movie);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage() . " - " . $th->getFile() . '-' . $th->getLine(), true, 'failed', $th->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'title' => ['required'],
                'publish' => ['required'],
                'description' => ['required'],
                'media_id' => ['required'],
            ]);

            $movie = $this->movie_service->create_movie_data($validate);

            return ResponseFormatter::success($movie);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage() . " - " . $th->getFile() . '-' . $th->getLine(), true, 'failed', $th->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
