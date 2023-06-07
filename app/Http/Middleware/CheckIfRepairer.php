<?php

namespace App\Http\Middleware;

use App\Facades\EmployeeRepository;
use App\Facades\PersonRepository;
use App\Models\Employee;
use App\Models\RepairerOld;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfRepairer
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        $person = PersonRepository::getByUser(auth()->user())->first();
        $employee = EmployeeRepository::getByPerson($person)->type(Employee::TYPE_REPAIRER)->first();
        if (!$employee) {
            return back()->with(['warning' => trans('messages.warning_user_no_repairer')]);
        }

        return $next($request);
    }
}
