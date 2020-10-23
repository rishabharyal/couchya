<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\UserLikes;
use App\Repos\MovieRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MovieService
{
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


	private $movieGenre;

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var Movie description
	 */
	private $movieModel;

	private $userLikesModel;

	public function __construct(UserLikes $userLikesModel, MovieRepo $movieRepo)
	{
		$this->userLikesModel = $userLikesModel;
		$this->movieRepo = $movieRepo;
	}

	public function likeMovie($movieId)
	{
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

	public function get($pageNumber, $genre, $range_start, $range_end): array
	{
		$movies = $this->movieRepo->getFromDB($pageNumber, $genre, $range_start, $range_end);
		if ($movies != []) {
			$this->movies = $movies;
		}

		return [
			'success' => true,
			'data' => $movies
		];
	}
}
