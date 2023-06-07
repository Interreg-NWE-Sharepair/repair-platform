<?php

namespace App\Http\Controllers\Person;

use App\Facades\EmployeeOrganisationRepository;
use App\Facades\PersonRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonUpdateRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PersonController extends Controller
{
    public function show()
    {
        /** @var \App\Models\Person $person */
        $person = PersonRepository::getByUser(Auth::user())->with('user')->firstOrFail();
        $isRepairer = EmployeeOrganisationRepository::isRepairer($person);
        if (!$isRepairer) {
            throw new AuthenticationException();
        }

        $organisations = EmployeeOrganisationRepository::getOrganisations($person);

        $rolesPerOrganisation = EmployeeOrganisationRepository::getRolesPerOrganisation($person);

        return Inertia::render('Person/Profile', [
            'person' => $person,
            'organisations' => $organisations,
            'roles' => $rolesPerOrganisation,
        ]);
    }

    public function store(PersonUpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $person = PersonRepository::getByUser(Auth::user())->with('user')->firstOrFail();

            DB::beginTransaction();
            PersonRepository::update($person, $data);
            DB::commit();

            $person->refresh();

            return redirect()->route('person_profile_show')->with([
                'info',
                trans('messages.success_profile_update'),
            ]);
        } catch (Exception $e) {
            report($e);
            DB::rollBack();
        }
    }
}
