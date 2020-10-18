<?php

namespace App\Services;

use App\Models\GameInstance;

class InviteFriend {

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var GameInstance $gameInstanceModel
	 */
	private $gameInstanceModel;

	public function __construct(GameInstance $instance) {
		$this->gameInstanceModel = $instance;
	}

	private $message = 'Please download app from this link: http://couchya.app/link and after you install app, simply follow this link: ';

	public function invite($phoneNumber) {
		$this->gameInstanceModel = new GameInstance();
		$this->gameInstanceModel->user_id = Auth::id();
		$this->gameInstanceModel->title = 
		$this->gameInstanceModel->code = uniqid('game_', false);
		$this->gameInstanceModel->save();

		$this->message .= env('APP_URL') . '/' . $this->gameInstanceModel->code;

		return [
			'success' => true,
			'message' => $this->message
		];

	}
}