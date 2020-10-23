<?php

namespace App\Repos;

use App\Models\Team;

class TeamRepo {
	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var Team $team
	 */
	private $teamModel;

	public function __construct(Team $team) {
		$this->team = $team;
	}

	public function getTeam($teamId): ?Team {
		$this->teamModel = Team::find($teamId);
		if (!$this->teamModel) {
			return null;
		}

		return $this->teamModel;
	}

	public function create($name, $userId): Team {
		$this->teamModel = new Team();
		$this->teamModel->user_id = $userId;
		$this->teamModel->title = $name;
		$this->teamModel->code = uniqid('team_' . $userId . '_', false);
		$this->teamModel->save();

		return $this->teamModel;
	}

	public function isMemberInTeam($userId, $teamId): bool {
		$this->teamModel = Team::find($teamId);
		if (!$this->teamModel) {
			return false;
		}

		$teamMembers = $this->teamModel->members()->pluck('user_id')->toArray();
		return in_array($userId, $teamMembers, false);
	}
}