<?php

namespace App\Repos;

use App\Models\Genre;
use App\Models\Movie;

class MovieRepo
{
	public function getFromDB($page, $genre, $range_start, $range_end): array
	{
		// $genreModel  = Genre::where('name', $genre)->first();
		// if (!$genreModel) return [];
		return Movie::take(350)->get()->toArray();
	}

	public function getFromApi($page, $genre, $range_start, $range_end): array
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://unogsng.p.rapidapi.com/search?start_year=1970&end_year=2019&genrelist=" . $genre,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"x-rapidapi-host: unogsng.p.rapidapi.com",
				"x-rapidapi-key: fdec6729a1msh667dbf1fca5fbf3p10e9d4jsn51fe5a7dc66f"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$movies = json_decode($response, true);

		return $movies['results'] ?? [];
	}

	public function createGenreEntries()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://unogsng.p.rapidapi.com/genres",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"x-rapidapi-host: unogsng.p.rapidapi.com",
				"x-rapidapi-key: fdec6729a1msh667dbf1fca5fbf3p10e9d4jsn51fe5a7dc66f"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$genres = json_decode($response, true);

		foreach ($genres['results'] as $key => $genre) {
			$genreId = $genre['netflixid'];
			$genreName = $genre['genre'];
			if (Genre::find($genreId)) continue;
			var_dump('Storing ' . $genreName);
			$g = new Genre();
			$g->id = $genreId;
			$g->name = $genreName;
			$g->save();
		}
	}
}
