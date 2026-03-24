<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $notificationMessage,
        public string $type = 'general'
    ) {}

    public function build()
    {
        return $this->subject('BOCRA System Notification')
                    ->view('emails.notification');
    }
}