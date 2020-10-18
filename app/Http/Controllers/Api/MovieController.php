<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var MovieService $movieService
	 */
	private $movieService;

	public function __construct(MovieService $movieService) {
		$this->movieService = $movieService;
	}

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     */
    public function get(Request $request) {
    	$pageNumber = $request->get('page') ?? 0;
    	$genre = $request->get('genre') ?? '';
    	$range_start = $request->get('range_start') ?? '';
    	$range_end = $request->get('range_end') ?? '';
        return $this->movieService->get($pageNumber, $genre, $range_start, $range_end);
    }
}
