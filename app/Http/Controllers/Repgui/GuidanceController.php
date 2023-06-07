<?php

namespace App\Http\Controllers\Repgui;

use App\Facades\PageRepository;
use App\Facades\RepairTutorialRepository;
use App\Http\Requests\Repgui\GuidanceStep1Request;
use App\Http\Requests\Repgui\GuidanceStep2Request;
use App\Http\Services\Repgui\GuidanceService;
use App\Http\Services\Repgui\RepairImpactCalculationService;
use App\Models\CommonDeviceTypeIssue;
use App\Models\DeviceType;
use App\Models\RepairGuidanceFormLog;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class GuidanceController extends RepguiController
{
    const GUIDANCE_SESSION_KEY = 'guidance';

    /**
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function step1()
    {
        $step = 1;

        $deviceTypes = $this->getDeviceTypeOptions();

        /**
         * Check if there already is an active guidance session
         * This will prefill data when going back in steps
         */
        $repairGuidanceLog = $this->hasGuidanceSession();

        SEOMeta::setTitle(trans('repgui.tell_more_about_device'))->setDescription(
                trans('repgui.tell_more_about_device_intro')
            );

        return view('repgui.pages.guidance.step1', [
            'deviceTypes' => $deviceTypes,
            'step' => $step,
            'repairGuidanceLog' => $repairGuidanceLog,
        ]);
    }

    /**
     * Stores step 1 data and redirects to step 2 or back with data
     *
     * @param \App\Http\Requests\Repgui\GuidanceStep1Request $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function step1Store(GuidanceStep1Request $request): ?RedirectResponse
    {
        try {
            $userAgent = $request->headers->get('user-agent');
            if (is_array($userAgent)) {
                $userAgent = $userAgent[0];
            }

            $request = $request->validated();

            $repairGuidanceFormLog = $this->hasGuidanceSession();

            if (!$repairGuidanceFormLog) {
                $repairGuidanceFormLog = new RepairGuidanceFormLog();
                $request['extra_properties'] = json_encode([
                    'user-agent' => $userAgent,
                ], JSON_THROW_ON_ERROR);
                $request['locale'] = app()->getLocale();
            }

            //Also set the deviceType name
            if (isset($request['device_type_id'])) {
                $deviceType = DeviceType::where('id', $request['device_type_id'])->first();
            } elseif (!$request['device_type_id'] && isset($request['device_type_mobile_id']) !== null) {
                $deviceType = DeviceType::where('id', $request['device_type_mobile_id'])->first();
            }
            $repairGuidanceFormLog->device_type_name = $deviceType->name;
            $repairGuidanceFormLog->source = RepairGuidanceFormLog::TYPE_GUIDANCE;
            $repairGuidanceFormLog->store($request);
            $repairGuidanceFormLog->refresh();

            $this->hasGuidanceSession($repairGuidanceFormLog);

            return redirect()->route('guide_step_2');
        } catch (Exception $e) {
            report($e);

            return back()->withInput()->withErrors(['error' => trans('repgui.validation_errors')]);
        }
    }

    /**
     * Gives step 2 of the guidance form when a session is found from step 1
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function step2()
    {
        $step = 2;

        $repairGuidanceLog = $this->hasGuidanceSession();
        if (!$repairGuidanceLog) {
            return redirect()->route('guide_step_1')->withErrors(['error' => trans('repgui.guidance_session_not_found')]
                );
        }

        $commonDeviceTypeIssues = CommonDeviceTypeIssue::query()->where(
                'device_type_id',
                $repairGuidanceLog->deviceType->id
            )->get();

        SEOMeta::setTitle(trans('repgui.describe_the_problem'))->setDescription(
                trans('repgui.describe_the_problem_intro')
            );

        return view('repgui.pages.guidance.step2', [
            'step' => $step,
            'previousStep' => 1,
            'repairGuidanceLog' => $repairGuidanceLog,
            'commonDeviceTypeIssues' => $commonDeviceTypeIssues->all(),
        ]);
    }

    public function step2Store(GuidanceStep2Request $request): RedirectResponse
    {
        try {
            $request = $request->validated();

            /** @var RepairGuidanceFormLog $repairGuidanceLog */
            $repairGuidanceLog = $this->hasGuidanceSession();
            if (!$repairGuidanceLog) {
                return redirect()->route('guide_step_1')->withErrors(
                        ['error' => trans('repgui.guidance_session_not_found')]
                    );
            }

            if (isset($request['common_device_issues'])) {
                $commonDeviceTypeIssues = $request['common_device_issues'];
            }
            $data = [
                'common_issue_text' => $request['problem_now'],
            ];

            $repairGuidanceLog->store($data);

            if (isset($commonDeviceTypeIssues)) {
                $repairGuidanceLog->commonDeviceTypeIssues()->sync($commonDeviceTypeIssues);
            } else {
                $repairGuidanceLog->commonDeviceTypeIssues()->sync([]);
            }
        } catch (Exception $e) {
            report($e);

            return back()->withInput()->withErrors(['error' => trans('repgui.validation_errors')]);
        }

        return redirect()->route('guide_step_3');
    }

    public function step3(GuidanceService $guidanceService, RepairImpactCalculationService $repairImpactCalculationService)
    {
        $step = 3;

        /** @var RepairGuidanceFormLog $repairGuidanceLog */
        $repairGuidanceLog = $this->hasGuidanceSession();
        if (!$repairGuidanceLog) {
            return redirect()->route('guide_step_1')->withErrors(['error' => trans('repgui.guidance_session_not_found')]
                );
        }

        $recommendationInfo = $guidanceService->getRecommendationInfo($repairGuidanceLog);

        SEOMeta::setTitle(trans('repgui.check_repair_options'))->setDescription(
                trans('repgui.check_repair_options_text')
            );

        $mappedAge = $repairImpactCalculationService->getAge($repairGuidanceLog->product_age);
        $logDeviceTypeCode = $repairGuidanceLog->deviceType->code;
        $impactType = $repairImpactCalculationService->getImpactForGuidance($logDeviceTypeCode);
        $calculatedImpact = null;

        if ($impactType) {
            $calculatedImpact = $repairImpactCalculationService->getCalculationInfo($mappedAge, $impactType);
        }

        return view('repgui.pages.guidance.step3', [
            'step' => $step,
            'previousStep' => 2,
            'repairGuidanceLog' => $repairGuidanceLog,
            'recommendationInfo' => $recommendationInfo,
            'calculatedImpact' => $calculatedImpact,
            'mappedType' => $calculatedImpact ? Str::lower($calculatedImpact['category']) : null
        ]);
    }

    public function stepDiy()
    {
        $uuid = request()->get('uuid');
        /** @var RepairGuidanceFormLog $repairGuidanceLog */
        $repairGuidanceLog = $this->hasGuidanceSession();

        if (!$repairGuidanceLog && $uuid) {
            $repairGuidanceLog = RepairGuidanceFormLog::where('uuid', $uuid)->first();
        }
        if (!$repairGuidanceLog) {
            return redirect()->route('guide_step_1')->withErrors(['error' => trans('repgui.guidance_session_not_found')]
                );
        }

        $needsRecycling = $repairGuidanceLog->needsRecycling();
        $recyclePage = PageRepository::getRecyclePage();

        $generalRepairGuides = RepairTutorialRepository::getDeviceTypeTutorials(
            app()->getLocale(),
            $repairGuidanceLog->deviceType
        )->get();

        $commonDeviceTypeIssues = $repairGuidanceLog->deviceType->commonDeviceTypeIssues;

        if ($commonDeviceTypeIssues) {
            $commonRepairGuides = RepairTutorialRepository::getCommonDeviceTypeTutorials(
                app()->getLocale(),
                $repairGuidanceLog->deviceType,
                $commonDeviceTypeIssues
            )->get();

            if ($commonRepairGuides) {
                $parsedCommonRepairGuides = [];
                foreach ($commonRepairGuides as $commonRepairGuide) {
                    $parsedCommonRepairGuides[$commonRepairGuide->common_device_type_issue_id]['guides'][] = $commonRepairGuide;
                    $parsedCommonRepairGuides[$commonRepairGuide->common_device_type_issue_id]['issue'] = $commonRepairGuide->commonDeviceTypeIssue;
                }
            }
        }

        if ($commonDeviceTypeIssues->isNotEmpty()) {
            $otherCommonRepairGuides = RepairTutorialRepository::getCommonDeviceTypeTutorials(
                app()->getLocale(),
                $repairGuidanceLog->deviceType,
                $repairGuidanceLog->commonDeviceTypeIssues,
                true
            )->get();
        }

        $ordpGuides = $repairGuidanceLog->getOrdpGuides();

        $this->removeGuidanceSession();

        SEOMeta::setTitle(trans('repgui.repair_it_yourself'))->setDescription(trans('repgui.repair_it_yourself_text'));

        return view('repgui.pages.guidance.diy', [
            'repairGuidanceLog' => $repairGuidanceLog,
            'generalRepairGuides' => $generalRepairGuides->all() ?? [],
            'commonRepairGuides' => isset($parsedCommonRepairGuides) ? $parsedCommonRepairGuides : [],
            'otherCommonRepairGuides' => $otherCommonRepairGuides ?? collect(),
            'needsRecycling' => $needsRecycling,
            'recyclePage' => $recyclePage,
            'ordpGuides' => $ordpGuides,

        ]);
    }

    public function guideMap()
    {
        $uuid = request()->get('uuid');
        /** @var RepairGuidanceFormLog $repairGuidanceLog */
        $repairGuidanceLog = $this->hasGuidanceSession();

        if (!$repairGuidanceLog && $uuid) {
            $repairGuidanceLog = RepairGuidanceFormLog::where('uuid', $uuid)->first();
        }
        if (!$repairGuidanceLog) {
            return redirect()->route('guide_step_1')->withErrors(['error' => trans('repgui.guidance_session_not_found')]
                );
        }

        $this->removeGuidanceSession();

        SEOMeta::setTitle(trans('repgui.repair_it_map'))->setDescription(trans('repgui.repair_it_map_text'));

        return view('repgui.pages.guidance.map', [
            'repairGuidanceLog' => $repairGuidanceLog,
        ]);
    }

    /**
     * give a list of all the visible device type options ordered by parent type
     *
     * @return array
     */
    public function getDeviceTypeOptions()
    {
        $options = [];
        $deviceTypes = DeviceType::visible()->showOnGuidance()->with('parent')->orderBy('parent_id')->orderBy(
                'name' . '->' . app()->getLocale()
            )->get();
        if ($deviceTypes) {
            foreach ($deviceTypes as $deviceType) {
                if (!$deviceType->parent) {
                    continue;
                }
                if (!isset($options[$deviceType->parent->uuid])) {
                    $options[$deviceType->parent->uuid]['name'] = $deviceType->parent->name;
                }

                $options[$deviceType->parent->uuid]['options'][] = $deviceType;
            }
        }

        return $options;
    }

    /**
     * Check if current request has an existing guidance session and return it or create a new session with existing data
     *
     * @param \App\Models\RepairGuidanceFormLog|null $repairGuidanceFormLog
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function hasGuidanceSession(RepairGuidanceFormLog $repairGuidanceFormLog = null)
    {
        $session = session()->has(self::GUIDANCE_SESSION_KEY);
        if (!$session && $repairGuidanceFormLog) {
            session()->put(self::GUIDANCE_SESSION_KEY, $repairGuidanceFormLog->uuid);

            return null;
        }

        if ($session && $repairGuidanceFormLog) {
            session()->put(self::GUIDANCE_SESSION_KEY, $repairGuidanceFormLog->uuid);

            return null;
        }

        if ($session) {
            $guidance = session()->get(self::GUIDANCE_SESSION_KEY);

            return RepairGuidanceFormLog::query()->where('uuid', $guidance)->first();
        }

        if (request()->query('uuid')) {
            $guidance = request()->query('uuid');

            return RepairGuidanceFormLog::query()->where('uuid', $guidance)->first();
        }

        return null;
    }

    private function removeGuidanceSession(): void
    {
        session()->forget(self::GUIDANCE_SESSION_KEY);
    }
}
