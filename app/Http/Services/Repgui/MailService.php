<?php

namespace App\Http\Services\Repgui;

use App\Mail\Repgui\Contact\Contact as ContactMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Every mail that will be send on environments not "live" or "demo" will go to our sigma mailbox
     *
     * @var mixed
     */
    private $debugMail;

    public function initialize()
    {
        if (!app()->environment([
            'live',
            'demo',
        ])) {
            $this->debugMail = optional(Auth::user())->email ?? config('mail.debug_mail');
        }
    }

    public function sendContactMail(Contact $contact, $renderOnly = false)
    {
        $this->initialize();

        $mailTo = config('mail.repgui_email');
        if ($this->debugMail) {
            $mailTo = $this->debugMail;
        }
        Mail::to($mailTo)->send(new ContactMail($contact));
    }
}
