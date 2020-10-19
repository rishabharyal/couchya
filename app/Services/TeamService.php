<?php

namespace App\Services;

use App\Models\User;
use App\Repos\TeamMemberRepo;
use App\Repos\TeamRepo;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;

class TeamService {

	use WithoutMiddleware;

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var TeamRepo $teamRepo
	 */
	private $teamRepo;

	private $teamMemberRepo;

	public function __construct(TeamRepo $teamRepo, TeamMemberRepo $teamMemberRepo) {
		$this->teamRepo = $teamRepo;
		$this->teamMemberRepo = $teamMemberRepo;
	}

	public function createTeam($teamName): array {
		$team = $this->teamRepo->create($teamName, Auth::id());

		return [
			'success' => true,
			'data' => [
				'title' => $team->title,
				'code' => $team->code,
				'id' => $team->id
			]
		];
	}

	public function getAllTeams() {
		$teams = Auth::user()->teams;
		$data = [];

		foreach ($teams as $key => $team) {
			$teamMembers = $this->getTeamMembers($team);
			$data[] = [
				'id' => $team->id,
                'title' => $team->title,
                'code' => $team->code,
                'members' => $teamMembers
			];
		}

		return [
			'success' => true,
			'data' => $data
		];

	}

	private function getTeamMembers($teamInfo) {
		return User::whereIn('id', $teamInfo->members()->pluck('user_id')->toArray())->get()->toArray();
	}

	public function getTeam($teamId) {

	    $teamInfo = $this->teamRepo->getTeam($teamId);

	    if (!$teamInfo) {
	        return [
	            'success' => false,
                'message' => 'The team does not exist!'
            ];
        }

	    $teamMembers = $this->getTeamMembers($teamInfo);

	    return [
	        'success' => true,
            'data' => [
                'id' => $teamInfo->id,
                'title' => $teamInfo->title,
                'code' => $teamInfo->code,
                'members' => $teamMembers
            ]
        ];
    }

	public function joinTeam($teamId) {
		$userId = Auth::id();
		$alreadyInTeam = $this->teamRepo->isMemberInTeam($userId, $teamId);
		if (!$alreadyInTeam) {
			$this->teamMemberRepo->create($teamId, $userId);
		}

		return [
			'success' => true,
			'message' => 'User added to the team.'
		];
	}

	public function inviteFriendToTeam($friends) {

	}
}
