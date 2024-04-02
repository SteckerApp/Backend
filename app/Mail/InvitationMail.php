<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $company;
    public $owner;
    public $type;
    public $invite;
    public $url;



    public function __construct($name, $company, $owner, $type, $invite)
    {
        $this->name = $name;
        $this->company = $company;
        $this->owner = $owner;
        $this->type = $type;
        $this->invite = $invite;
        $this->url = url('/register?invitation='.$invite->role.'&invitation_id='.$invite->id);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case 'client':
                return $this->markdown('emails.teams.invite');
                break;
            case 'admin':
                return $this->markdown('emails.teams.admininvite');
                break;
        }
    }
}
