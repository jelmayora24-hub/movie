<?php

namespace App\Services;

use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieService
{
    protected MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMovies()
    {
        return $this->movieRepository->getAllMovies();
    }

    public function getMovieById(int $id)
    {
        return $this->movieRepository->findMovieById($id);
    }

    public function searchMovies(string $keyword)
    {
        return $this->movieRepository->searchMovies($keyword);
    }

    public function createMovie(array $data)
    {
        return $this->movieRepository->createMovie($data);
    }

    public function updateMovie(int $id, array $data)
    {
        return $this->movieRepository->updateMovie($id, $data);
    }

    public function deleteMovie(int $id)
    {
        return $this->movieRepository->deleteMovie($id);
    }
}