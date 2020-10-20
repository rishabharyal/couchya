<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\UserLikes;
use App\Repos\MovieRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MovieService {
	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var Movierepo $movieRepo
	 */
	private $movieRepo;

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var Collection $movies
	 */
	private $movies;

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var Movie description
	 */
	private $movieModel;

	private $userLikesModel;

	public function __construct(UserLikes $userLikesModel, MovieRepo $movieRepo) {
		$this->userLikesModel = $userLikesModel;
		$this->movieRepo = $movieRepo;
	}

	public function likeMovie($movieId) {
		$userId = Auth::user()->id;
		$recordExists = $this->userLikesModel
			->where('user_id', $userId)
			->where('movie_id', $movieId)
			->first();

		if ($recordExists) {
			$recordExists->touch();
			return;
		}

		$this->userLikesModel = new UserLikes();
		$this->userLikesModel->user_id = $userId;
		$this->userLikesModel->movie_id = $movieId;
		$this->userLikesModel->save();

		return [
			'success' => true,
			'message' => 'User liked a movie successfully!',
		];
	}

	public function get($pageNumber, $genre, $range_start, $range_end): Collection {
		$movies = $this->movieRepo->get($pageNumber, $genre, $range_start, $range_end);
		if ($movies === []) {
			return $movies;
		}
		$this->movies = $movies;
		$movieList = $this->saveMovieFromApiToDatabase();

		return $movieList;
	}

	public function saveMovieFromApiToDatabase(): Collection {
		$movieUnogsIds = array_map(function($item) {
			return $item['id'];
		}, $this->movies);

		$moviesCollection = collect();
		$movieList = [];

		$existingMovies = Movie::whereIn('unogs_id', $movieUnogsIds)->pluck('id', 'unogs_id')->toArray();

		foreach ($this->movies as $key => $movie) {
			$this->movieModel = new Movie();
			$this->movieModel->unogs_id = $movie['id'];
			$this->movieModel->netflix_id = $movie['nfid'];
			$this->movieModel->image = $movie['img'];
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
