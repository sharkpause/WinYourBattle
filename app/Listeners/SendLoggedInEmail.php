<?php

namespace App\Listeners;

use App\Mail\LoggedInMail;
use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLoggedInEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        Mail::to($event->user->email)->send(new LoggedInMail($event->user));
    }
}
