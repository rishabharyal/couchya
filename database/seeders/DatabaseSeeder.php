<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::factory(10)->create();

        foreach ($users as $key => $user) {
        	$team = Team::factory()->create([
        		'user_id' => $user->id
        	]);

        	$teamMember = TeamMember::factory()->create([
        		'team_id' => $team->id,
        		'user_id' => $team->user_id
        	]);
        }

    }
}
