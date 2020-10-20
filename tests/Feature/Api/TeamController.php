<?php

namespace Tests\Feature\Api;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamController extends TestCase
{
    use RefreshDatabase;

    private $team;
    private $user;
    private $users;
    private $members;

    protected function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'rish.aryal@gmail.com'
        ]);
        $this->users = User::factory(10)->create([
            'profile_picture' => 'http://counchya.test/test.png'
        ]);
        $this->teams = Team::factory(3)->create([
            'user_id' => $this->user->id
        ]);

        foreach ($this->teams as $index=>$team) {
            $this->members[$index] = collect();

            foreach ($this->users as $key => $user) {
                $this->members[$index]->push(TeamMember::factory()->create([
                    'team_id' => $team->id,
                    'user_id' => $user->id
                ]));
            }
        }

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
        $this->assertEquals(count($response->json()['data']['members']), 1);
    }

    /**
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     * @return void
     * Test if we get all the teams of authenticated
     * user with the members related to it
     */
    public function testGetTeamWillReturnTeamsWithTeamMembers() {
        $response = $this->actingAs($this->user)->get('api/team');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertEquals(count($response->json()['data']), 3);
    }

    /**
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     * @return void
     * Test if twe get the team for the id
     * we was with members
     */
    public function testGetSingleTeamWillReturnWithTeamMembers() {
        $team = $this->teams->first();
        $response = $this->actingAs($this->user)->get('api/team/' . $team->id);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $team->id,
                'title' => $team->title,
                'code' => $team->code
            ]
        ]);
        $this->assertEquals(count($response->json()['data']['members']), 10);
    }

    public function testJoinTeamWillJoinUserToTheTeam() {
        $team = $this->teams->first();
        $response = $this->actingAs($this->user)->get('api/team/join/' . $team->id);
        $response->assertJson([
            'success' => true,
            'message' => 'User added to the team.'
        ]);
        
        $this->assertNotNull(TeamMember::where('team_id', $team->id)->where('user_id', $this->user->id)->first());
    }
}