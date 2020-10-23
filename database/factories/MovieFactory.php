<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unogs_id' => rand(9999999,99999999),
            'netflix_id' => rand(9999999,99999999),
            'image' => 'http://couchya.test/image.png',
            'poster' => 'http://couchya.test/poster.png',
            'vtype' => 'series',
            'imdb_id' => rand(9999999,99999999),
            'title' => 'Test Series',
            'clist' => '{}',
            'synopsis' => 'The story began like this, this an example. Yay!',
            'imdb_rating' => 7.5,
            'title_date' => date('Y-m-d'),
            'average_rating' => 6.87,
            'release_year' => date('Y'),
            'runtime' => '128'
        ];
    }
}
