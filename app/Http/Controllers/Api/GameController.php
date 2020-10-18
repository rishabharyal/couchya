<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeamService;
use App\Services\MovieService;
use Illuminate\Http\Request;

class GameController extends Controller
{
	private $teamService;
	private $movieService;

	public function __construct(TeamService $teamService, MovieService $movieService) {
		$this->teamService = $teamService;
		$this->movieService = $movieService;
	}

    public function likeMovie(Request $request) {
    	$validator = Validator::make($request->all(), [
			'movie_id' => ['required', 'exists:movies,id'],
			'team_id' => ['required', 'exists:teams,id']
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'data' => $validator->errors(),
				'message' => 'The movie or team no longer exist.'
			]);
		}

		$response = $this->movieService->likeMovie($request->get('movie_id'), $request->get('team_id'));
		return response()->json($response);
    }
}
