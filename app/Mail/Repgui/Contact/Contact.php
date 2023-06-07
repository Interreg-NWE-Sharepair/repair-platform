<?php

namespace App\Mail\Repgui\Contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Contact $contact
     */
    private $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.repgui_email'), config('mail.repgui_name'))->subject('Contact Repair Guidance')
                    ->view('repgui.mails.contact')->with([
                'contact' => $this->contact,
            ]);
    }
}
