<?php

namespace App\Http\Controllers\Replog\Repairer\Auth;

use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\RepairerLoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RepairerLoginController extends ReplogController
{
    use AuthenticatesUsers;

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('repairer_dashboard')->with(['success' => trans('messages.already_logged_in')]);
        }

        return Inertia::render('Repairer/Auth/Login', ['title' => trans('messages.title_login_repairer')]);
    }

    public function store(RepairerLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->email_verified_at) {
                return redirect()->route('repairer_dashboard');
            }

            Auth::logout();

            return back()->withErrors(['email' => trans('messages.email_not_verified')]);
        }

        return back()->withErrors(['password' => trans('validation.wrong_password')]);
    }
}
