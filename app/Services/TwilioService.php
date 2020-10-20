<?php
namespace App\Services;

use Twilio\Rest\Client;

class TwilioService {

	private $twilio;

	private $numberOfInvitedPeople;
	private $numberOfFailedPeople;

	public function __construct() {
		$sid    = env( 'TWILIO_SID' );
        $token  = env( 'TWILIO_TOKEN' );
		$this->twilio = new Client($sid, $token);
	}

	public function getNumberOfPeopleInvited() {
		return $this->numberOfInvitedPeople;
	}

	public function sendMessage($phoneNumbers, $message = '') {
		$failed = 0;
		$sent = 0;
		foreach ($phoneNumbers as $key => $phoneNumber) {
			try {
				$this->twilio->messages->create($phoneNumber, [
					'from' => env( 'TWILIO_FROM' ),
					'body' => $message
				]);
				$sent++;
			} catch (Exception $e) {
				dd($e);
				$failed++;
				continue;
			}
		}

		$this->numberOfInvitedPeople = $sent;
		$this->numberOfFailedPeople = $failed;
	}
}