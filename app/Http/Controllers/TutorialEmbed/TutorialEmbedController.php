<?php

namespace App\Http\Controllers\TutorialEmbed;

use App\Http\Controllers\Controller;
use App\Models\RepairGuidanceFormLog;
use App\Models\RepairTutorial;
use App\Services\OrdpApiService;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Str;

class TutorialEmbedController extends Controller
{

    private $style = '';

    private function getStyle() {
        $styleParam = request()->get('style', 'repair');
        $baseUrl = request()->getBaseUrl();
        $this->style = match ($styleParam) {
            'repair' => $baseUrl . '/repgui/css/main.css',
            'testaankoop' => $baseUrl . '/repgui/css/testaankoop.css',
            'repairtogether' => $baseUrl . '/repgui/css/repairtogether.css',
            default => $styleParam
        };
    }
    public function index()
    {
        SEOMeta::setTitle(trans('repgui.repair_guides_title'))->setDescription(
            trans('repgui.repair_guides_description')
        );

        $this->getStyle();

        $styleParam = request()->get('style', 'repair');
        return view('repguiembed.index', ['style' => $this->style, 'themeName' => $styleParam])->layout('repguiembed.layouts.base');
    }

    public function show(RepairTutorial $repairTutorial)
    {

        $uuid = request()->query('uuid');
        $repairGuidanceLog = null;
        if ($uuid) {
            $repairGuidanceLog = RepairGuidanceFormLog::query()->where('uuid', $uuid)->first();
        }

        $this->getStyle();
        $styleParam = request()->get('style', 'repair');

        $showDisclaimer = false;
        if ($repairTutorial->has_disclaimer) {
            $showDisclaimer = true;
        }
        SEOMeta::setTitle($repairTutorial->title)->setDescription($repairTutorial->description)->setKeywords([
            trans('repgui.get_instructions_and_tutorials'),
            $repairTutorial->deviceType->name,
        ]);

        return view('repguiembed.tutorial', [
            'tutorial' => $repairTutorial,
            'repairGuidanceLog' => $repairGuidanceLog,
            'showDisclaimer' => $showDisclaimer,
            'style' => $this->style,
            'themeName' => $styleParam,
        ])->layout('repguiembed.layouts.base');
    }

    public function external() {
        $searchQuery = request()->query('type');
        $guides =  (new OrdpApiService)->getGuidelines($searchQuery, 1, 30);

        $this->getStyle();
        $styleParam = request()->get('style', 'repair');

        return view('repguiembed.external',  [
            'ordpGuides' => $guides,
            'style' => $this->style,
            'themeName' => $styleParam,
        ])->layout('repguiembed.layouts.base');

    }

    public function documentation()
    {
        $markdown = file_get_contents(resource_path('docs/TUTORIALS.md'));

        return response()->make(Str::markdown($markdown));
    }
}
