<?php

namespace Tests\Feature\Api;

use App\Models\Movie;
use App\Models\User;
use App\Models\UserLikes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameController extends TestCase
{
    use RefreshDatabase;

    private $movie;
    private $user;

    protected function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'rish.aryal@gmail.com'
        ]);
        $this->movie = Movie::factory()->create();
    }

    /**
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     * @return void
     * Make sure that when user requests to like
     * page url, the movie will be
     * liked by the user
     */
    public function testLikeMovieWillLikeTheMovie()
    {
        $response = $this->actingAs($this->user)->post('/api/movie/like', [
            'movie_id' => $this->movie->id,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User liked a movie successfully!'
        ]);
        $this->assertNotNull(UserLikes::where('user_id', $this->user->id)->where('movie_id', $this->movie->id)->first());
    }
}
