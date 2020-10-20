<?php

namespace App\Repos;

class MovieRepo {
	public function create() {

	}

	public function get($page, $genre, $range_start, $range_end): array {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://rapidapi.p.rapidapi.com/search?start_year=1972&orderby=rating&audiosubtitle_andor=and&limit=100&subtitle=english&countrylist=78%2C46&audio=english&country_andorunique=unique&offset=$page&end_year=2019",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"x-rapidapi-host: unogsng.p.rapidapi.com",
				"x-rapidapi-key: fdec6729a1msh667dbf1fca5fbf3p10e9d4jsn51fe5a7dc66f"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return [];
		}

		return json_decode($response, true)['results'] ?? [];
	}
}
