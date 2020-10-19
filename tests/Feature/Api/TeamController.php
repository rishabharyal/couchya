<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamController extends TestCase
{
    use RefreshDatabase;

    private $team;
    private $user;

    protected function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'rish.aryal@gmail.com'
        ]);
        $this->team = Team::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     * @return void
     * Test if the team is created with
     * the given title successfully
     */
    public function testIfTeamIsCreatedSuccessfully()
    {
        $teamTitle = "Mero Team";
        $response = $this->actingAs($this->user)->post('/api/team', [
            'title' => $teamTitle,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'title' => $teamTitle,
            ]
        ]);
    }

    public function testGetTeamWillReturnTeamWithTeamMembers() {
        
    }
}
