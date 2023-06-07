<?php

namespace App\Http\Controllers\Repgui;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repgui\RepairImpactCalculationRequest;
use App\Http\Services\Repgui\GuidanceService;
use App\Http\Services\Repgui\RepairImpactCalculationService;
use App\Models\RepairGuidanceFormLog;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class RepairImpactCalculationController extends Controller
{
    public function index(RepairImpactCalculationService $calculationService){
        SEOMeta::setTitle(trans('repgui.route_repair_impact_index'))
               ->setDescription(trans('repgui.repair_impact_meta'));

        return view('repgui.pages.impact-calculation.index', ['productCategories' => $calculationService->getProductCategories()]);
    }

    public function calculate(RepairImpactCalculationRequest $request) {
        $validated = $request->validated();

        return redirect()->route('repair_impact_calculation_result', ['productCategory' => $validated['device_category'], 'productAge' => $validated['device_age']]);
    }

    public function result(Request $request, GuidanceService $guidanceService, RepairImpactCalculationService $calculationService) {

        SEOMeta::setTitle(trans('repgui.repair_impact_calculation_result_title'))
               ->setDescription(trans('repgui.repair_guides_description'));

        $productCategory = $request->query('productCategory');
        $mappedGuidanceCategory = $calculationService->getGuidanceForImpact($productCategory);
        $productAge = $request->query('productAge');
        $calculationInfo = $calculationService->getCalculationInfo($productAge, $productCategory);

        if(!$mappedGuidanceCategory) {
            return view('repgui.pages.impact-calculation.result', [
                'calculationInfo' => $calculationInfo,
                'recommendationInfo' => null,
                'deviceType' => null,
                'repairGuidanceLog' => null,
            ]);
        }

        $userAgent = $request->headers->get('user-agent');
        if (is_array($userAgent)) {
            $userAgent = $userAgent[0];
        }
        $uuid = $request->get('uuid');
        if (!$uuid) {
            $repairGuidanceLog = new RepairGuidanceFormLog([
                'device_type_id' => $mappedGuidanceCategory?->id,
                'product_age' => $productAge,
                'device_type_name' => $mappedGuidanceCategory->name,
                'extra_properties' => json_encode(['user-agent' => $userAgent], JSON_THROW_ON_ERROR),
                'locale' => app()->getLocale(),
                'source' => RepairGuidanceFormLog::TYPE_VITO
            ]);
            $repairGuidanceLog->save();
            $repairGuidanceLog->refresh();

            $route = route('repair_impact_calculation_result', ['productCategory' => $productCategory, 'productAge' => $productAge]);
            $route .= '&uuid=' . $repairGuidanceLog->uuid;
            return redirect()->to($route);
        }

        $repairGuidanceLog = RepairGuidanceFormLog::whereUuid($uuid)->firstOrFail();

        $recommendationInfo = $guidanceService->getRecommendationInfo($repairGuidanceLog);

        return view('repgui.pages.impact-calculation.result', [
            'calculationInfo' => $calculationInfo,
            'recommendationInfo' => $recommendationInfo,
            'deviceType' => $mappedGuidanceCategory,
            'repairGuidanceLog' => $repairGuidanceLog,
        ]);
    }
}
