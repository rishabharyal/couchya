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
		return Movie::all()->toArray();
	}

	public function getFromApi($page, $genre, $range_start, $range_end):array {
		$genres = $this->createGenreEntries();
		return [];
	}

	public function createGenreEntries()
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://unogs-unogs-v1.p.rapidapi.com/api.cgi?t=genres",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"x-rapidapi-host: unogs-unogs-v1.p.rapidapi.com",
				"x-rapidapi-key: fdec6729a1msh667dbf1fca5fbf3p10e9d4jsn51fe5a7dc66f"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$genres = json_decode($response, true);

		foreach ($genres['ITEMS'] as $key => $genre) {
			foreach ($genre as $genreName => $genreItem) {
				foreach ($genreItem as $genreId) {
					$genre = Genre::find($genreId);
					if ($genre) {
						continue;
					}
					$genre = new Genre();
					$genre->name = $genreName;
					$genre->id = $genreId;
					$genre->save();
					break;
				}
			}
		}
	}
}
