<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public  string $code) {}
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'رمز عبور یکبار مصرف',
        );
    }

    /**
     * Build the message.
     */
    public function content():Content
    {
        return  new  Content(
            markdown: 'user::mail.sendCode',
            with: [
                'code'=>$this->code
            ]
        );
    }
}
