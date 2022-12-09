<?php

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Auth\Listeners;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Auth\Events\Registered;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\MustVerifyEmail;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
