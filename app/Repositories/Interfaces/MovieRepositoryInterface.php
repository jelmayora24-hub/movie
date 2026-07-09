<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface
{
    public function getAllMovies();
    public function findMovieById(int $id);
    public function createMovie(array $data);
    public function updateMovie(int $id, array $data);
    public function deleteMovie(int $id);
    public function searchMovies(string $keyword);
}