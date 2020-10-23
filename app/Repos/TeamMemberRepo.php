<?php

namespace App\Repos;

use App\Models\TeamMember;

class TeamMemberRepo {
	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var TeamMember $teamMemberModel
	 */
	private $teamMemberModel;

	public function __construct(TeamMember $teamMember) {
		$this->teamMemberModel = $teamMember;
	}

	public function create($teamId, $userId): TeamMember {
		$this->teamMemberModel = new TeamMember();
		$this->teamMemberModel->user_id = $userId;
		$this->teamMemberModel->team_id = $teamId;
		$this->teamMemberModel->save();

		return $this->teamMemberModel;
	}
}