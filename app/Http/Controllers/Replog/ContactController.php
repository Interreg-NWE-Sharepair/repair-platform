<?php

namespace App\Http\Controllers\Replog;

use App\Facades\OrganisationRepository;
use App\Http\Requests\ContactRequest;
use App\Http\Services\MailService;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Tenant;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends ReplogController
{
    public function index(): Response
    {
        $organisations = OrganisationRepository::getAvailable(app()->getLocale())->get();
        $organisationsMapped = [];
        foreach ($organisations as $index => $organisation) {
            $organisationsMapped[$index]['text'] = $organisation->name;
            $organisationsMapped[$index]['value'] = $organisation->id;
        }

        usort($organisationsMapped, function ($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return Inertia::render('Contact', [
            'data' => [
                'name' => null,
                'email' => null,
                'message' => null,
                'repair_organisation' => null,
                'terms' => null,
                'gRecaptchaResponse' => null,
            ],
            'title' => trans('messages.contact_title'),
            'repairOrganisations' => $organisationsMapped,
        ]);
    }

    public function store(ContactRequest $request, Contact $contact, MailService $mailService)
    {
        try {
            $data = $request->validated();
            $tenant = Tenant::where('code', Tenant::REPGUI)->first();
            $contactEntry = $contact->store($data, $tenant);
            $mailService->sendContactMail($contactEntry);
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with(['errors' => trans('messages.something_went_wrong')]);
        }

        return redirect(route('contact_confirmation'))->with('success', trans('messages.contact_success'));
    }

    public function confirmation(Page $page)
    {
        $page = $page->getContactConfirmationContent(Tenant::REPLOG);


        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => null,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }
}
