@if($deviceType)
    <div class="section section--default">
        <div class="container">
            <div class="mb-10">
                <div class="flex flex-wrap items-center -mx-4 -mt-8">
                    <div class="w-full px-4 mt-6 md:w-3/4">
                        <h3 class="text-h3 text-primary">{{ trans('repgui.diy') }}</h3>
                        @if($recommendationInfo['hasGuides'])
                            <p>{{ trans('repgui.diy_text') }}</p>
                            <div class="mt-8 sm:mt-12">
                                <div class="flex flex-wrap -mx-2 -mt-4">
                                    @foreach ($recommendationInfo['guides'] as $guide)
                                        <div class="w-full px-2 mt-4 sm:w-1/2">
                                            <div
                                                class="relative flex flex-col h-full p-6 md:p-8 bg-light rounded-panel">
                                                <h3 class="font-semibold md:text-2xl">{{ $guide->title }}</h3>
                                                <a class="mt-auto link link--ext link--extended"
                                                   href="{{ route('tutorial_show', ['repairTutorial' => $guide]) }}?uuid={{ $repairGuidanceLog->uuid }}">{{ trans('repgui.view_tutorial') }}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p>{{ trans('repgui.diy_no_solution_step_3_text') }}</p>
                        @endif
                        <div class="flex flex-wrap items-center">
                            <div class="mr-2">
                                <a class="btn btn--primary btn--ext"
                                   href="{{ route('guide_step_diy') }}?uuid={{$repairGuidanceLog->uuid }}">
                                    @if($recommendationInfo['hasGuides'])
                                        {{ trans('repgui.get_instructions_and_tutorials') }}
                                    @else
                                        {{ trans('repgui.get_instructions_and_tutorials') }}
                                    @endif
                                </a>
                            </div>
                            @if(!$recommendationInfo['hasGuides'])
                                <div class="mt-6">
                                    <a class="text-base text-black underline hover:text-primary-hover link--ext"
                                       href="{{ route('tips_index') }}">{{ trans('repgui.general_tips_diy') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-full px-4 mt-8 md:w-1/4">
                        <!-- TODO: SVG -->
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full px-3 mt-6">
                    <div
                        class="flex flex-wrap bg-light h-full p-8 rounded-card @if($recommendationInfo['needsRecycling']) order-3 @endif @if(!$deviceType || !$deviceType->is_fixed_by_repair_cafe)opacity-50 cursor-not-allowed @endif">
                        <div class="w-full md:w-3/5 lg:w-3/4">
                            <h3 class="text-h3 text-primary"> {{ trans('repgui.repair_cafe') }}</h3>
                            <p> {{ trans('repgui.repair_cafe_text') }}</p>
                            <div class="mt-6">
                                <a class="btn btn--primary btn--ext @if(!$deviceType || !$deviceType->is_fixed_by_repair_cafe)cursor-not-allowed @endif"
                                   href="@if(!$deviceType || !$deviceType->is_fixed_by_repair_cafe)javascript:void(0);@else{{ route('guide_step_map') }}?uuid={{$repairGuidanceLog->uuid }}&product_categories={{ $repairGuidanceLog->deviceType->code }}&organisation_types=repair_cafe @endif">{{ trans('repgui.show_nearby_repaircafe') }}</a>
                            </div>
                        </div>
                        <div class="w-full mt-4 md:mt-0 md:w-2/5 lg:w-1/4">
                            <img data-src="/repgui/img/guidance_repaircafe.svg" alt="" class="px-10 lazyload">
                        </div>
                    </div>
                </div>
                <div class="w-full px-3 mt-6 md:w-1/2">
                    <div
                        class="relative bg-light h-full overflow-hidden p-8 rounded-card @if($recommendationInfo['needsRecycling']) order-last @endif">
                        <div class="relative z-10">
                            <h3 class="text-h3 text-primary"> {{ trans('repgui.professionals') }}</h3>
                            <p> {{ trans('repgui.professionals_text') }}</p>
                            <div class="mt-4">
                                <a class="btn btn--primary btn--ext"
                                   href="{{ route('guide_step_map') }}?uuid={{$repairGuidanceLog->uuid }}&product_categories={{ $repairGuidanceLog->deviceType->code }}&organisation_types=professional_repairer">{{ trans('repgui.show_professionals') }}</a>
                            </div>
                        </div>
                        <div class="absolute right-0 w-2/5 mr-4 pull-to-center-y top-1/2" style="opacity: .1">
                            <img data-src="/repgui/img/guidance_professional.svg" alt="" class="lazyload">
                        </div>
                    </div>
                </div>
                <div class="w-full px-3 mt-6 md:w-1/2">
                    <div
                        class="bg-light h-full p-8 rounded-card @if($recommendationInfo['needsRecycling']) order-first @endif">
                        <h3 class="text-h3 text-primary"> {{ trans('repgui.recycle') }}</h3>
                        <p> {{ trans('repgui.recycle_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--primary btn--ext"
                               href="{{ route('recycle') }}?uuid={{ $repairGuidanceLog->uuid }}">{{ trans('repgui.recycle_link') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
