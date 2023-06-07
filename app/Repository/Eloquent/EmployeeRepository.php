<?php

namespace App\Repository\Eloquent;

use App\Models\ContactDetail;
use App\Models\Employee;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Repository\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param Employee $model
     */
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    /**
     * @param \App\Models\Person $person
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getByPerson(Person $person): Builder
    {
        return $this->model::query()->selectRaw('employees.*')->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->where('employee_type', Employee::TYPE_REPAIRER)->groupBy('employees.id');
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getById($id): Builder
    {
        return $this->model::query()->where('id', $id);
    }

    public function getByUser(User $user): Builder
    {
        return $this->model::query()->selectRaw('employees.*')->user($user)->active();
    }

    /**
     * @param \App\Models\Organisation $organisation
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * Find all the  ACTIVE employees with the type EMPLOYEE for a certain organisation. Without the users having a '@statik.be' address
     * Employees are sorted by their user role. Sadly enough, because employees can have multiple roles per organisation
     * There is a SELECT(MAX(model_has_roles.role_id)) function that adds a row based on the ID of the role.
     * entity-admin = 2 | event-organizer = 1 | repairer = 0
     * It's the only simple way to order the employees by their role in frontend.
     */
    public function getByOrganisation(Organisation $organisation): Builder
    {
        $roles = Role::query()->whereIn('name', [
            Role::ENTITY_ADMIN,
            Role::REPAIRER,
            Role::EVENT_ORGANIZER,
        ])->pluck('id')->all();

        if ($organisation->show_employee_info) {
            $query = $this->model::query()->selectRaw('employees.*')->with([
                'person',
                'person.user',
                'person.contactDetails',
                'organisation',
                'roles',
            ]);
        } else {
            $query = $this->model::query()->selectRaw('employees.id')->with([
                'person.first_name',
                'person.last_name',
                'person.specialization',
                'organisation.*',
                'person.contactDetails',
            ]);
        }

        return $query->active()->organisation($organisation)->role($roles)
                     ->selectRaw('(CASE WHEN MAX(model_has_roles.role_id) = 4 THEN 2 WHEN MAX(model_has_roles.role_id) = 6 THEN 1 ELSE 0 END) as admin')
                     ->join('people', 'people.id', '=', 'employees.person_id')
                     ->join('users', 'users.id', 'people.user_id')->leftJoin('model_has_roles', function ($join) {
                $join->on('employees.id', '=', 'model_has_roles.model_id');
                $join->where('model_has_roles.model_type', '=', 'employee');
            })->where('users.email', 'NOT LIKE', '%@statik.be%')->groupBy('employees.person_id')
                     ->orderBy('admin', 'desc')->orderBy('people.last_name')->orderBy('people.first_name');
    }

    public function countByOrganisation(Organisation $organisation = null): int
    {
        $query = $this->model->active();

        if ($organisation) {
            $query->organisation($organisation);
        }

        return $query->count();
    }

    public function search(Builder $query, Request $request): Builder
    {
        $searchTerm = $request->query('search');
        if ($searchTerm) {
            $query::whereLike($query, [
                'person.first_name',
                'person.last_name',
                'person.specialization',
                'person.location',
                'person.user.email',
            ], $searchTerm);
        }

        return $query;
    }

    /**
     * Return a newly created Employee with type && role Repairer
     *
     * @param array $data
     * @param \App\Models\Organisation $organisation
     * @param string $locale
     * @return \App\Models\Employee
     */
    public function createRepairer($data, Organisation $organisation, $locale = 'nl'): Employee
    {
        unset($data['terms']);
        unset($data['gRecaptchaResponse']);
        unset($data['password_confirmation']);
        unset($data['location_id']);

        $user = new User();
        $user->name = $data['first_name'] . ' ' . $data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->locale = $locale;
        $user->save();
        $user->refresh();

        $person = new Person();
        $person->first_name = $data['first_name'];
        $person->last_name = $data['last_name'];
        $person->user()->associate($user);
        $person->save();
        $person->refresh();

        if ($data['telephone']) {
            $phoneDetails = $this->makeContactDetail($data['telephone'], ContactDetail::TYPE_PHONE);
            $person->contactDetails()->save($phoneDetails);
        }

        $employee = new Employee();
        $employee->organisation()->associate($organisation);
        $employee->employee_type = Employee::TYPE_REPAIRER;
        $employee->assignRole([Role::REPAIRER]);
        $employee->person()->associate($person);

        $employee->save();
        $employee->refresh();

        return $employee;
    }

    /**
     * @param $value
     * @param string $type
     * @return \App\Models\ContactDetail
     */
    private function makeContactDetail($value, string $type)
    {
        $contactDetail = new ContactDetail();
        $contactDetail->type = $type;
        $contactDetail->name = Str::limit($value, 252);
        $contactDetail->value = str_replace([
            ' ',
            '(',
            ')',
        ], '', $value);

        return $contactDetail;
    }
}
