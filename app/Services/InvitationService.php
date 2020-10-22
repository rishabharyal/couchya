<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvitationService {
	private $invitationModel;
	public function __construct(Invitation $invitationModel) {
		$this->invitationModel = $invitationModel;
	}

	public function delete($id) {
		$this->invitationModel = $this->invitationModel->where('user_id', Auth::id())->where('id', $id)->first();
		$this->invitationModel->delete();
	}

	public function getInvitations() {
		$invitations = [];
		$allInvitations = Invitation::where('user_id', Auth::id())->get();
		foreach ($allInvitations as $key => $invitation) {
			$invitations[] = [
				'id' => $invitation->id,
				'team_id' => $invitation->team_id,
				'team_name' => $invitation->team->title,
				'invitation_from' => User::where('id', $invitation->invited_by)->first()
			];
		}

		return [
			'success' => true,
			'data' => $invitations
		];
	}
}