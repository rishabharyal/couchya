<?php

namespace App\Services;

use App\Models\Genre;
use App\Repos\MovieRepo;
use App\Models\Movie;
use Illuminate\Support\Collection;

class CacheService
{


    private $movieRepo;

    private $movies;

    private $genre;


    private $movieModel;

    public function __construct(MovieRepo $movieRepo)
    {
        $this->movieRepo = $movieRepo;
    }


    public function handle()
    {
        $genres = Genre::all();

        if ($genres->count() === 0) {
            $this->movieRepo->createGenreEntries();
            return $this->handle();
        }

        foreach ($genres as $genre) {
            $this->genre = $genre;
            $this->movies = $this->movieRepo->getFromApi(1, $genre->id, 0, 0);
            $this->saveMovieFromApiToDatabase();
        }
    }


    private function saveMovieFromApiToDatabase(): Collection
    {

        $moviesCollection = collect();
        $movieList = [];
        $movieUnogsIds = array_map(function ($item) {
            return $item['id'];
        }, $this->movies);

        $existingMovies = Movie::whereIn('unogs_id', $movieUnogsIds)->pluck('id', 'unogs_id')->toArray();

        foreach ($this->movies as $key => $movie) {
            var_dump('Storing movie titled ' . $movie['title']);
            $this->movieModel = new Movie();
            $this->movieModel->unogs_id = $movie['id'];
            $this->movieModel->netflix_id = $movie['nfid'];
            $this->movieModel->image = $movie['img'];
            $this->movieModel->genre_id = $this->genre->id;
            $this->movieModel->poster = $movie['poster'];
            $this->movieModel->vtype = $movie['vtype'];
            $this->movieModel->imdb_id = $movie['imdbid'];
            $this->movieModel->title = $movie['title'];
            $this->movieModel->clist = $movie['clist'];
            $this->movieModel->synopsis = $movie['synopsis'];
            $this->movieModel->imdb_rating = $movie['imdbrating'];
            $this->movieModel->title_date = $movie['titledate'];
            $this->movieModel->average_rating = $movie['avgrating'];
            $this->movieModel->release_year = $movie['year'];
            $this->movieModel->runtime = $movie['runtime'];

            if (!isset($existingMovies[$movie['id']])) {
                $this->movieModel->save();
            } else {
                $this->movieModel->id = $existingMovies[$movie['id']];
            }

            $moviesCollection->push($this->movieModel);
        }

        return $moviesCollection;
    }
}
