<?php

namespace App\Http\Services\Repgui;

use App\Facades\PageRepository;
use App\Facades\RepairTutorialRepository;
use App\Mail\Repgui\Contact\Contact as ContactMail;
use App\Models\Contact;
use App\Models\RepairGuidanceFormLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GuidanceService
{
   public function getRecommendationInfo(RepairGuidanceFormLog $repairGuidanceLog) {
       $needsRecycling = $repairGuidanceLog->needsRecycling();
       $recyclePage = PageRepository::getRecyclePage();
       $guides = collect();

       $commonRepairGuides = null;
       if ($repairGuidanceLog->commonDeviceTypeIssues) {
           $commonRepairGuides = RepairTutorialRepository::getCommonDeviceTypeTutorials(
               app()->getLocale(),
               $repairGuidanceLog->deviceType,
               $repairGuidanceLog->commonDeviceTypeIssues
           );

           if ($commonRepairGuides->count()) {
               $guides = $commonRepairGuides->get();
           }
       }


       $generalRepairGuides = RepairTutorialRepository::getDeviceTypeTutorials(
           app()->getLocale(),
           $repairGuidanceLog->deviceType
       );

       if ($generalRepairGuides->count()) {
           $guides->merge($generalRepairGuides->get());
       }

       $otherCommonRepairGuides = RepairTutorialRepository::getCommonDeviceTypeTutorials(
           app()->getLocale(),
           $repairGuidanceLog->deviceType,
           $repairGuidanceLog->commonDeviceTypeIssues,
           true
       );

       $hasOrdpGuides = $repairGuidanceLog->getOrdpGuides()->isNotEmpty();

       $hasGuides = false;
       if ($generalRepairGuides->count() || $commonRepairGuides->count() || $otherCommonRepairGuides->count() || $hasOrdpGuides) {
           $hasGuides = true;
       }

       return [
           'needsRecycling' => $needsRecycling,
           'recyclePage' => $recyclePage,
           'hasGuides' => $hasGuides,
           'guides' => $guides->take(2),
       ];
   }
}
