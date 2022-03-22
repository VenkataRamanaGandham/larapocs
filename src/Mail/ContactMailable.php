<?php

namespace Lmate\Customer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $firstname;
    public $lastname;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('customer::customer.email')->with(['firstname' => $this->firstname, 'lastname'=>$this->lastname]);
    }
}
