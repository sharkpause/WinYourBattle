<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\PasswordReset;
use App\Mail\PasswordResetNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetNotificationMail
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
