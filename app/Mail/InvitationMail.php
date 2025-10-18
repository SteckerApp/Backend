<?php

namespace App\Mail;

use App\Models\TagCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public $service;
    public $url;



    public function __construct($name, $company, $owner, $type, $invite, TagCategory $service)
    {
        $this->name = $name;
        $this->company = $company;
        $this->owner = $owner;
        $this->type = $type;
        $this->invite = $invite;
        $this->service = $service;
        $this->url = env('APP_URL') . '/register?invitation=' . $invite->role . '&invitation_id=' . $invite->id . '&tag_id=' . $service->id;
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
