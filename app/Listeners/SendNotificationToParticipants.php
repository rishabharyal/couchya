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
        $phoneNumber = $user->country_code . $user->phone_number;
        $invitations = Invitation::where('invitated_to_phone', $phoneNumber)->get();
        foreach ($invitations as $key => $invitation) {
            $invitation->user_id = $user->id;
            $invitation->save();
        }
    }
}
