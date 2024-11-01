<?php

namespace App\Listeners;

use App\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetNotificationEmail
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
    public function handle(PasswordReset $event): void
    {
        Mail::to($event->user->email)->send(new PasswordResetNotificationMail($event->user));
    }
}
