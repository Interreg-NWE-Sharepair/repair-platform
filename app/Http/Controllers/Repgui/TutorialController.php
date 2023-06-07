<?php

namespace App\Http\Controllers\Repgui;

use App\Models\RepairGuidanceFormLog;
use App\Models\RepairTutorial;
use App\Services\OrdpApiService;
use Artesaos\SEOTools\Facades\SEOMeta;

class TutorialController extends RepguiController
{
    public function index()
    {
        SEOMeta::setTitle(trans('repgui.repair_guides_title'))
               ->setDescription(trans('repgui.repair_guides_description'));

        return view('repgui.pages.tutorials.index', ['filters' => true]);
    }

    public function show(RepairTutorial $repairTutorial)
    {

        $uuid = request()->query('uuid');
        $repairGuidanceLog = null;
        if ($uuid) {
            $repairGuidanceLog = RepairGuidanceFormLog::query()->where('uuid', $uuid)->first();
        }

        $showDisclaimer = false;
        if ($repairTutorial->has_disclaimer) {
            $showDisclaimer = true;
        }
        SEOMeta::setTitle($repairTutorial->title)->setDescription($repairTutorial->description)->setKeywords([
            trans('repgui.get_instructions_and_tutorials'),
            $repairTutorial->deviceType->name,
        ]);

        return view('repgui.pages.tutorials.tutorial', [
            'tutorial' => $repairTutorial,
            'repairGuidanceLog' => $repairGuidanceLog,
            'showDisclaimer' => $showDisclaimer
        ]);
    }

    public function external() {
        $searchQuery = request()->query('type');
        $guides =  (new OrdpApiService)->getGuidelines($searchQuery, 1, 30);

        return view('repgui.pages.tutorials.external',  [
            'ordpGuides' => $guides
        ]);

    }
}
