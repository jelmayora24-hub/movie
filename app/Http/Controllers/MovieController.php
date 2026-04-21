<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
    protected MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index()
    {
        $movies = request('search')
            ? $this->movieService->searchMovies(request('search'))
            : $this->movieService->getAllMovies();

        return view('homepage', compact('movies'));
    }

    public function detail($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return view('detail', compact('movie'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('input', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul'       => 'required|string|max:255',
            'sinopsis'    => 'required|string',
            'tahun'       => 'required|integer',
            'pemain'      => 'required|string',
            'category_id' => 'required|integer',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto_sampul')) {
            $validatedData['foto_sampul'] = $request->file('foto_sampul')->store('movie_covers', 'public');
        }

        $this->movieService->createMovie($validatedData);
        return redirect('/')->with('success', 'Film berhasil ditambahkan.');
    }

    public function data()
    {
        $movies = $this->movieService->getAllMovies();
        return view('data-movies', compact('movies'));
    }

    public function form_edit($id)
    {
        $movie = $this->movieService->getMovieById($id);
        $categories = Category::all();
        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'judul'       => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis'    => 'required|string',
            'tahun'       => 'required|integer',
            'pemain'      => 'required|string',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $movie = $this->movieService->getMovieById($id);

        if ($request->hasFile('foto_sampul')) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $request->file('foto_sampul')->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;
            $request->file('foto_sampul')->move(public_path('images'), $fileName);

            if (File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $validatedData['foto_sampul'] = $fileName;
        }

        $this->movieService->updateMovie($id, $validatedData);
        return redirect('/movies/data')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $movie = $this->movieService->getMovieById($id);

        if (File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        $this->movieService->deleteMovie($id);
        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }
}