<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovieService;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
	private $movieService;

	public function __construct(MovieService $movieService) {
		$this->movieService = $movieService;
	}

    public function likeMovie(Request $request) {
    	$validator = Validator::make($request->all(), [
			'movie_id' => ['required', 'exists:movies,id'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'data' => $validator->errors(),
				'message' => 'The movie does not exist!'
			]);
		}

		$response = $this->movieService->likeMovie($request->get('movie_id'));
		return response()->json($response);
    }
}
