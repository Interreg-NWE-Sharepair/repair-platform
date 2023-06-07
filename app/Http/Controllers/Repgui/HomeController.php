<?php

namespace App\Http\Controllers\Repgui;

use App\Facades\PageRepository;
use App\Models\RepairTutorial;
use Artesaos\SEOTools\Facades\SEOMeta;

class HomeController extends RepguiController
{
    public function index()
    {
        $locale = app()->getLocale();
        $tutorials = RepairTutorial::query()->isHighlighted()->isLocalized($locale)->get();;
        SEOMeta::setTitle(trans('repgui.home'))->setDescription(trans('repgui.home_description'));

        return view('repgui.pages.home', ['tutorials' => $tutorials]);
    }
}
