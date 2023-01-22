<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestACallMail extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $mobile_no;

    public function __construct($full_name,$mobile_no)
    {
        $this->full_name = $full_name;
        $this->mobile_no = $mobile_no;
    }

     /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact_us.call');
    }

}
