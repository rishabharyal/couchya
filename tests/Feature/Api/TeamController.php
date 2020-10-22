<?php

namespace Tests\Feature\Api;

use App\Models\Invitation;
use App\Models\Movie;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\UserLikes;
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
    private $movies;
    private $likes;

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

        $this->movies = Movie::factory(10)->create();

        $this->likes = collect();

        foreach ($this->users as $key => $user) {
            foreach ($this->movies as $key => $movie) {
                $like = UserLikes::forceCreate([
                    'user_id' => $user->id,
                    'movie_id' => $movie->id
                ]);
                $this->likes->push($like);
            }
        }

        Invitation::forceCreate([
            'user_id' => $this->user->id,
            'team_id' => $this->teams->first()->id,
            'invited_by' => $this->user->id,
            'invitated_to_phone' => '9779865012999',
        ]);

    }
    public function testShowTeamPageWillShoreMoviesWithLikes() {
        $team = $this->teams->first();
        $response = $this->actingAs($this->user)->post('api/matches/team', [
            'team_id' => $team->id
        ]);

        // test if the assertions are right..
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
        $this->assertEquals(count($response->json()['data']['teams']), 3);
        $this->assertEquals(count($response->json()['data']['invitations']), 1);
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

    public function testInviteWillInviteUsers() {
        $team = $this->teams->first();
        $response = $this->actingAs($this->user)->post('/api/team/invite', [
            'team_id' => $team->id,
            'invitations' => [
                '+9779865012999',
                '+9779865011077'
            ]
        ]);

        $this->assertNotNull(Invitation::where('invitated_to_phone', '+9779865012999')->where('invited_by', $this->user->id)->first());
        $response->assertJson([
            'success' => true,
            'message' => 'We have sent SMS to 2 people with invitation link.'
        ]);
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