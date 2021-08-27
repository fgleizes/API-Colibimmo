<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PersonPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The person instance.
     *
     * @var \App\Models\Person
     */
    protected $person;
    protected $plainPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $person, $plainPassword)
    {
        $this->person = $person;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->text('emails.person.password_plain');
        return $this->view('emails.person.password')
                    // ->text('emails.person.password_plain')
                    ->with([
                        'personLastname' => $this->person->lastname,
                        'personFirstname' => $this->person->firstname,
                        'plainPassword' => $this->plainPassword
                    ]);
    }
}
