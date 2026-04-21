<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllMovies()
    {
        return Movie::latest()->paginate(6);
    }

    public function findMovieById(int $id)
    {
        return Movie::findOrFail($id);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }

    public function updateMovie(int $id, array $data)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function deleteMovie(int $id)
    {
        return Movie::destroy($id);
    }

    public function searchMovies(string $keyword)
    {
        return Movie::latest()
            ->where('judul', 'like', '%' . $keyword . '%')
            ->orWhere('sinopsis', 'like', '%' . $keyword . '%')
            ->paginate(6)
            ->withQueryString();
    }
}