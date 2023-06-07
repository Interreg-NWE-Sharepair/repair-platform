<?php

namespace App\Http\Controllers\Repgui;

use App\Http\Requests\Repgui\ContactRequest;
use App\Http\Services\Repgui\MailService;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Tenant;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;

class ContactController extends RepguiController
{
    public function index()
    {
        SEOMeta::setTitle(trans('messages.contact_title'));

        return view('repgui.pages.contact.index');
    }

    public function store(ContactRequest $request, Contact $contact, MailService $mailService)
    {

        try {
            $request = $request->validated();

            $tenant = Tenant::where('code', Tenant::REPGUI)->first();
            $contactEntry = $contact->store($request, $tenant);
            $mailService->sendContactMail($contactEntry);
        } catch (Exception $e) {
            report($e);

            return back()->withInput();
        }

        return redirect(route('contact_confirmation'))->with('success', trans('messages.contact_success'));
    }

    public function confirmation(Page $page)
    {
        $page = $page->getContactConfirmationContent(Tenant::REPGUI);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }
}
