<?php

namespace App\Listeners;

use App\Models\Invitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToParticipants
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $phoneNumber = $this->cleanPhoneNumber($user->country_code . $user->phone_number);
        $phoneNumber = $this->onlyNumber($phoneNumber);

        $invitations = Invitation::where('invitated_to_phone', $phoneNumber)->get();
        foreach ($invitations as $key => $invitation) {
            $invitation->user_id = $user->id;
            $invitation->save();
        }
    }

    private function cleanPhoneNumber($number) {
        $number = str_replace('+', '', $number);
        $number = str_replace('-', '', $number);
        $number = str_replace(' ', '', $number);
        $number = str_replace('(', '', $number);
        $number = str_replace(')', '', $number);

        if (strlen($number) <= 10) {
            $number = '1' . $number;
        }

        return $number;
    }

    private function onlyNumber($number) {
        return substr($number, -10);
    }
}
