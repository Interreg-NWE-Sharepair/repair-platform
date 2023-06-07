<?php

namespace App\Http\Controllers\Replog\Repairer\Auth;

use App\Facades\EmployeeRepository;
use App\Facades\OrganisationRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\RepairerRegisterRequest;
use App\Http\Services\MailService;
use App\Models\Location;
use App\Models\Page;
use App\Models\Tenant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RepairerRegisterController extends ReplogController
{
    public function index($locationCode = null)
    {
        if (!$locationCode) {
            return Inertia::render('Repairer/Auth/Register/Step0', [
                'location' => $locationCode,
                'title' => trans('messages.register_repairer_title'),
                'mapbox' => config('mapbox'),
            ]);
        }

        return Inertia::render('Repairer/Auth/Register/Step1', [
            'location' => $locationCode,
            'title' => trans('messages.register_repairer_title'),
        ]);
    }

    public function storeStep0(Request $request)
    {
        $locationCode = $request->get('organisation');

        if (!$locationCode) {
            back()->with('validation.location');
        }

        return redirect()->route('repairer_register_index', ['locationCode' => $locationCode]);
    }

    public function storeStep1(RepairerRegisterRequest $request, $locationCode)
    {
        try {
            /**
             * @var \App\Models\Organisation $organisation
             */
            $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

            DB::beginTransaction();
            $data = $request->validated();
            $employee = EmployeeRepository::createRepairer($data, $organisation, app()->getLocale());
            DB::commit();

            $mailService = new MailService();
            $mailService->sendRepairerRegisteredMail($employee);

            return redirect()->route('repairer_register_confirmation')
                             ->with('success', trans('messages.repairer_succesfull_registered'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->withErrors(['errors' => trans('messages.something_went_wrong')]);
        }
    }

    /**
     * @param \App\Models\Page $page
     * @return \Inertia\Response
     */
    public function confirmation(Page $page)
    {
        $page = $page->getRepairerCreatedContent(Tenant::REPLOG);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    /**
     * Return the guidance url to de repairConnects page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Mcamara\LaravelLocalization\Exceptions\SupportedLocalesNotDefined
     * @throws \Mcamara\LaravelLocalization\Exceptions\UnsupportedLocaleException
     */
    public function redirectIndex(Request $request) {
        $uuid = $request->get('uuid');
        $location = Location::query()->where('uuid', $uuid)->firstOrFail();
        $organisation = $location->organisation;

        $laravelLocalized = new LaravelLocalization();
        $laravelLocalized->setLocale(app()->getLocale());
        $laravelLocalized->setBaseUrl('https://repairconnects.org/');
        $url = $laravelLocalized->getURLFromRouteNameTranslated(app()->getLocale(), 'routes.repairer_register_index', ['locationCode' => $organisation->slug]);


        return redirect($url);

    }
}
