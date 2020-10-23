<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $genres = [
        "​Action",
        "Animation",
        "Comedy",
        "Crime",
        "Drama",
        "Experimental",
        "Fantasy",
        "Historical",
        "Horror",
        "Romance",
        "Science Fiction",
        "Thriller",
        "Western Genre",
        "Musical",
        "Anime",
    ];
    public function run()
    {
        foreach ($this->genres as $value) {
            $genre  = new Genre();
            $genre->name = $value;
            $genre->save();
        }
    }
}
