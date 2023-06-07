@extends('repgui.layouts.layout')
@section('content_body')
    @php
        $deviceType = $repairGuidanceLog->deviceType;
    @endphp
    <div class="section section--light">
        <div class="container">
            @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => true, 'top' => true])
            <div class="mt-12">
                <div>
                    <h2>{{ trans('repgui.check_repair_options') }}</h2>
                    <p>{{ trans('repgui.check_repair_options_text') }}</p>
                </div>

                <div class="p-6 my-8 bg-white border-4 rounded border-primary-200">
                    <dl class="space-y-2">
                        <dd><strong>{{ trans('repgui.product_category') }}:</strong> {{ $deviceType->name }}</dd>

                        @if ($repairGuidanceLog->product_age)
                            <dd>
                                <strong>{{ trans('repgui.product_age') }}
                                    :</strong> {{ $repairGuidanceLog->product_age }}
                            </dd>
                        @endif
                        @if ($repairGuidanceLog->commonDeviceTypeIssues->count() > 0)
                            <dd>
                                <strong>{{ trans('repgui.common_device_issue') }}:</strong>
                                @foreach ($repairGuidanceLog->commonDeviceTypeIssues as $issue)
                                    {{ $issue->issue }} @if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach

                            </dd>
                        @endif
                        @if($repairGuidanceLog->common_issue_text)
                            <dd>
                                <strong>{{ trans('repgui.common_issue_text') }}
                                    :</strong> {{ $repairGuidanceLog->common_issue_text }}</dd>
                        @endif
                    </dl>
                </div>
                @if($recommendationInfo['needsRecycling'])
                    <div
                        class="flex items-center px-3 py-2 my-4 text-primary-600 bg-primary-100 border-primary-600 notice notice--danger border-1">
                        <svg class="mr-2 text-xl icon text-primary-600" aria-hidden="true">
                            <use xlink:href="/repgui/icon/sprite.svg#information"></use>
                        </svg>
                        {!! trans('repgui.needs_recycling_warning', ['deviceType' => strtoupper($deviceType->name), 'maxAge' => $repairGuidanceLog->deviceType->max_repair_age]) !!}
                    </div>
                @endif

                @if($deviceType->eco_impact || $calculatedImpact || $deviceType->repair_success_rate)
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-semibold">{{ trans('repgui.eco_impact_metadata') }}</h3>
                            @if($calculatedImpact and $calculatedImpact['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_REPAIR)
                                <dl class="space-y-2">
                                    {{ trans('repgui.repair_impact_calculation_eco_impact', ['device' => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::plural($deviceType->name)), 'co2savings' => $calculatedImpact['co2_savings']]) }}
                                </dl>
                                <div class="relative mt-2">
                                    <a class="mt-auto link link--ext"
                                       href="{{ route('repair_impact_calculation_result', ['productCategory' => $calculatedImpact['category_key'], 'productAge' => $calculatedImpact['age']]) }}">{{ trans('repgui.impact_tool_results') }}</a>
                                </div>
                            @elseif($deviceType->eco_impact)
                                <p>{{ $deviceType->eco_impact }}</p>
                            @endif
                        </div>
                        @if($deviceType->repair_success_rate)
                            <div>
                                <h3 class="font-semibold">{{ trans('repgui.repair_success_rate') }}</h3>
                                <p>{{ $deviceType->repair_success_rate }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('repgui.partials.guidance.recommendations', ['repairGuidanceLog' => $repairGuidanceLog, 'deviceType' => $deviceType, 'recommendationInfo' => $recommendationInfo])
    <div class="section section--default">
        <div class="container">
            @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => false, 'top' => false])
        </div>
    </div>
@endsection
