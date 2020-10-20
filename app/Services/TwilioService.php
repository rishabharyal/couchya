<?php
namespace App\Services;

use Twilio\Rest\Client;

class TwilioService {
	private $twilio;

	public function __constructor() {
		$sid    = env( 'TWILIO_SID' );
        $token  = env( 'TWILIO_TOKEN' );
		$this->twilio = new Client($sid, $token);
	}

	public function sendMessage($phoneNumbers, $message = '') {
		foreach ($phoneNumbers as $key => $phoneNumber) {
			$this->twilio->nessages->create($phoneNumber, [
				'from' => env( 'TWILIO_FROM' ),
				'body' => $message
			]);
		}
	}
}