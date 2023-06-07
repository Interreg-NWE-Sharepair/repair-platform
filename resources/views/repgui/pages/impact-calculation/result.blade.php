{{--Whitelist sub html tag--}}
{{--<sub></sub>--}}

@extends('repgui.layouts.layout')
@section('content_body')
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/4">
                <h3 class="text-h3 text-secondary">{{ trans('repgui.repair_impact_calculation_result_title') }}</h3>
                <div class="p-6 my-8 bg-white border-4 rounded border-primary-200">
                    <div class="flex flex-wrap justify-between -mx-4 -mt-8">
                        <div class="w-full px-4 mt-8 md:w-1/2">
                            <h3 class="mb-3 text-xl font-thin">
                                {{ trans('repgui.repair_impact_calculation_product') }}
                            </h3>
                            <dl class="space-y-2 text-base">
                                <dd>
                                    <strong>{{ trans('repgui.product_category') }}: </strong>{{ $calculationInfo['category'] }}
                                </dd>

                                <dd>
                                    <strong>{{ trans('repgui.product_age') }}: </strong>{{ $calculationInfo['age'] }} {{ trans('repgui.years') }}
                                </dd>
                            </dl>
                        </div>
                        <div class="w-full px-4 mt-8 md:w-1/2">
                            <h3 class="mb-3 text-xl font-thin">
                                {{ trans('repgui.repair_impact_calculation_assumptions') }}
                            </h3>
                            <dl class="space-y-2 text-base">
                                <dd>
                                    {!! trans('repgui.repair_impact_calculation_assumptions_expected_lifetime', ['deviceCategory' => $calculationInfo['category'], 'years' => $calculationInfo['expectedLifetime']]) !!}
                                </dd>

                                <dd>
                                    {!! trans('repgui.repair_impact_calculation_assumptions_expected_remaining_lifetime', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a class="link link--prev" href="{{ route('repair_impact_calculation_index', ['product_age' => $calculationInfo['age'], 'product_category' => $calculationInfo['category']]) }}">{{ trans('repgui.repair_impact_calculation_change_details_button') }}</a>
                    </div>
                </div>
                <div class="my-8">
                    <h3 class="mb-3 font-semibold text-h3">
                        {{ trans('repgui.repair_impact_calculation_calculation_title') }}
                    </h3>
                    <p class="text-base leading-normal" >
                        {!! trans('repgui.repair_impact_calculation_calculation_info', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Visualisation component --}}
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-11/12">
                <div>
                    <div class="flex flex-wrap -mx-4 sm:flex-no-wrap sm:h-75-screen">
                        <!-- Labels/Heading column -->
                        <div class="flex-col hidden w-1/5 px-4 md:flex">
                            <div class="flex flex-col h-11/12">
                                <div class="h-2/12"></div>
                                <div class="items-center hidden text-right h-2/12 md:flex">
                                    <h4 class="text-base font-thin text-black">
                                        {!! trans('repgui.repair_impact_calculation_viz_total_co2_label', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}
                                    </h4>
                                </div>
                                <div class="items-center hidden text-right md:flex h-6/12">
                                    <h4 class="text-base font-thin text-black">
                                        {!! trans('repgui.repair_impact_calculation_viz_breakdown_co2_label') !!}
                                    </h4>
                                </div>
                                <div class="items-center hidden text-right md:flex h-2/12">
                                    <h4 class="text-base font-thin text-black">
                                        {{ trans('repgui.repair_impact_calculation_viz_eq_co2_label') }}
                                    </h4>
                                </div>
                            </div>
                            <div class="h-1/12">

                            </div>
                        </div>
                        <!-- End Labels/Heading column -->

                        <!-- Repair column -->
                        <div class="w-full sm:w-1/2 md:w-2/5 flex flex-col mx-2 @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_REPAIR) border-2 rounded-3xl overflow-hidden border-secondary @endif">
                            <div class="flex flex-col overflow-hidden sm:h-11/12 @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_BUY) border-2 rounded-3xl overflow-hidden border-light @endif">
                                <div class="flex items-end justify-center py-4 sm:h-2/12">
                                    <h3 class="mb-0 text-h3 text-secondary">{{ trans('repgui.repair_impact_calculation_viz_repair_title') }}</h3>
                                </div>
                                <div class="flex items-center justify-center py-6 sm:h-2/12 bg-light">
                                    <div class="text-center">
                                        <h4 class="text-base font-thin text-black md:hidden">{!! trans('repgui.repair_impact_calculation_viz_total_co2_label', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}</h4>
                                        <h3 class="mb-0 text-xl font-semibold text-black">
                                            {!! trans('repgui.repair_impact_calculation_viz_co2_format', ['amount' => number_format($calculationInfo['co2_repair'], 0, ',', '.')]) !!}
                                        </h3>
                                    </div>
                                </div>
                                <div class="my-8 sm:my-6 md:my-6 sm:h-6/12">
                                    <h4 class="text-base font-thin text-center text-black md:hidden">
                                        {!! trans('repgui.repair_impact_calculation_viz_breakdown_co2_label') !!}
                                    </h4>
                                    <div class="flex flex-row h-full py-8 sm:py-6 md:py-4" style="gap: 2%">
                                        <div class="flex items-center pl-2">
                                            <div class="text-sm font-semibold" style="writing-mode: vertical-lr; transform: rotate(180deg)">
                                                kg CO2
                                            </div>
                                        </div>
                                        <div class="relative w-1/6 min-h-[200px] sm:h-full">
                                            @for($step = 0; $step <= $calculationInfo['chart']['maxY']; $step += $calculationInfo['chart']['step'])
                                                <div class="absolute pl-1 text-sm font-semibold leading-none -translate-y-1/2" style="top: {{100 - round(($step / $calculationInfo['chart']['maxY']) * 100)}}%">{{$step}}</div>
                                            @endfor
                                        </div>
                                        <div class="w-2/6 mx-auto min-h-[200px] bar-repair"></div>
                                        {{-- Legende --}}
                                        <div class="top-0 right-0 w-2/6 mx-2">
                                            <div class="flex justify-end pr-2 -mt-2">
                                                <ul>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #D8D2E7"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.usage') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #DCE9D5"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.repair') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #AAC1F0"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.endoflife') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #EECECD"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.production') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        .bar-repair {
                                            height: 100%;
                                            background: linear-gradient(
                                            to top,
                                            #EECECD 0% {{$calculationInfo['chart']['repair']['production']}}%,
                                            #AAC1F0 {{$calculationInfo['chart']['repair']['production']}}% {{$calculationInfo['chart']['repair']['eol']}}%,
                                            #DCE9D5 {{$calculationInfo['chart']['repair']['eol']}}% {{$calculationInfo['chart']['repair']['repair']}}%,
                                            #D8D2E7 {{$calculationInfo['chart']['repair']['repair']}}% {{$calculationInfo['chart']['repair']['usage']}}%,
                                                /* De restwaarde */
                                            #FFF {{$calculationInfo['chart']['repair']['usage']}}% 100%
                                            );
                                        }
                                    </style>
                                </div>
                                <div class="flex items-center justify-center py-6 sm:h-2/12 bg-light">
                                    <div class="text-center">
                                        <h4 class="text-base font-thin text-black md:hidden">
                                            {{ trans('repgui.repair_impact_calculation_viz_eq_co2_label') }}
                                        </h4>
                                        <h3 class="mb-0 text-xl font-semibold text-black">
                                            @if($calculationInfo['eq_repair_days'] > 0)
                                                {{ trans('repgui.repair_impact_calculation_viz_eq_days_format', ['amount' => number_format($calculationInfo['eq_repair_days'], 0, ',', '.')])}}
                                            @else
                                                {{ trans('repgui.repair_impact_calculation_viz_eq_hours_format', ['amount' => number_format($calculationInfo['eq_repair_hours'], 0, ',', '.')])}}
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:h-1/12 py-4 sm:py-2 flex justify-center items-center @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_REPAIR) bg-secondary @endif">
                                @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_REPAIR)
                                    <h3 class="m-0 text-xl font-semibold text-secondary-contrast">
                                        {{ trans('repgui.repair_impact_calculation_viz_recommendation_label') }}
                                    </h3>
                                @endif
                            </div>
                        </div>
                        <!-- End Repair column -->

                        <!-- Buy new column -->
                        <div class="w-full mt-8 sm:mt-0 sm:w-1/2 md:w-2/5 flex flex-col mx-2 @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_BUY) border-2 rounded-3xl overflow-hidden border-secondary @endif">
                            <div class="flex flex-col sm:h-11/12 @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_REPAIR) border-2 rounded-3xl overflow-hidden border-light @endif">
                                <div class="flex items-end justify-center py-4 sm:h-2/12">
                                    <h3 class="mb-0 text-h3 text-secondary">{{ trans('repgui.repair_impact_calculation_viz_buy_title') }}</h3>
                                </div>
                                <div class="flex items-center justify-center py-6 sm:h-2/12 bg-light">
                                    <div class="text-center">
                                        <h4 class="text-base font-thin text-black md:hidden">{!! trans('repgui.repair_impact_calculation_viz_total_co2_label', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}</h4>
                                        <h3 class="mb-0 text-xl font-semibold text-black">
                                            {!! trans('repgui.repair_impact_calculation_viz_co2_format', ['amount' => number_format($calculationInfo['co2_buy'], 0, ',', '.')]) !!}
                                        </h3>
                                    </div>
                                </div>
                                <div class="my-8 sm:my-6 md:my-4 sm:h-6/12">
                                    <h4 class="text-base font-thin text-center text-black md:hidden">
                                        {!! trans('repgui.repair_impact_calculation_viz_breakdown_co2_label') !!}
                                    </h4>
                                    <div class="flex flex-row h-full py-8 sm:py-6 md:py-4" style="gap: 2%">
                                        <div class="flex items-center pl-2">
                                            <div class="text-sm font-semibold" style="writing-mode: vertical-lr; transform: rotate(180deg)">
                                                kg CO2
                                            </div>
                                        </div>
                                        <div class="relative w-1/6 min-h-[200px] sm:h-full">
                                            @for($step = 0; $step <= $calculationInfo['chart']['maxY']; $step += $calculationInfo['chart']['step'])
                                                <div class="absolute pl-1 text-sm font-semibold leading-none -translate-y-1/2" style="top: {{100 - round(($step / $calculationInfo['chart']['maxY']) * 100)}}%">{{$step}}</div>
                                            @endfor
                                        </div>
                                        <div class="w-2/6 mx-auto min-h-[200px] bar-buy"></div>
                                        {{-- Legende --}}
                                        <div class="top-0 right-0 w-2/6 mx-2">
                                            <div class="flex justify-end pr-2 -mt-2">
                                                <ul>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #D8D2E7"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.usage') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #DCE9D5"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.repair') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #AAC1F0"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.endoflife') }}</span>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <span class="inline-block w-4 h-4 mr-2" style="background-color: #EECECD"></span>
                                                        <span class="text-sm font-semibold">{{ trans('repgui.production') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        .bar-buy {
                                            height: 100%;
                                            background: linear-gradient(
                                            to top,
                                            #EECECD 0% {{$calculationInfo['chart']['buy']['production']}}%,
                                            #AAC1F0 {{$calculationInfo['chart']['buy']['production']}}% {{$calculationInfo['chart']['buy']['eol']}}%,
                                            #DCE9D5 {{$calculationInfo['chart']['buy']['eol']}}% {{$calculationInfo['chart']['buy']['repair']}}%,
                                            #D8D2E7 {{$calculationInfo['chart']['buy']['repair']}}% {{$calculationInfo['chart']['buy']['usage']}}%,
                                                /* De restwaarde */
                                            #FFF {{$calculationInfo['chart']['buy']['usage']}}% 100%
                                            );
                                        }
                                    </style>
                                </div>
                                <div class="flex items-center justify-center py-6 sm:h-2/12 bg-light">
                                    <div class="text-center">
                                        <h4 class="text-base font-thin text-black md:hidden">
                                            {{ trans('repgui.repair_impact_calculation_viz_eq_co2_label') }}
                                        </h4>
                                        <h3 class="mb-0 text-xl font-semibold text-black">
                                            @if($calculationInfo['eq_buy_days'] > 0)
                                                {{ trans('repgui.repair_impact_calculation_viz_eq_days_format', ['amount' => number_format($calculationInfo['eq_buy_days'], 0, ',', '.')]) }}
                                            @else
                                                {{ trans('repgui.repair_impact_calculation_viz_eq_hours_format', ['amount' => number_format($calculationInfo['eq_buy_hours'], 0, ',', '.')]) }}
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:h-1/12 py-4 sm:py-2 flex justify-center items-center @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_BUY) bg-secondary @endif">
                                @if($calculationInfo['recommendation'] == \App\Http\Services\Repgui\RepairImpactCalculationService::RECOMMENDATION_BUY)
                                    <h3 class="m-0 text-xl font-semibold text-secondary-contrast">
                                        {{ trans('repgui.repair_impact_calculation_viz_recommendation_label') }}
                                    </h3>
                                @endif
                            </div>
                        </div>
                        <!-- End Buy new column -->
                    </div>
                </div>

                <div class="mt-8">
                    <span class="text-sm">{{ trans('repgui.repair_impact_calculation_footnote_remaining_lifetime', ['years' => $calculationInfo['expectedRemainingLifetime']]) }}</span>
                </div>
            </div>
        </div>
    </div>
    {{-- End Visualisation --}}

    {{-- Conclusion --}}
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/4">
                <div class="mt-4">
                    <h3 class="mb-8 font-semibold text-h3">
                        {{ trans('repgui.repair_impact_calculation_conclusion_title') }}
                    </h3>
                    <div class="p-8 bg-secondary rounded-3xl text-secondary-contrast">
                        <p class="relative pl-8 text-base">
                            <svg class="absolute mr-1 -ml-8 text-lg text-white icon" aria-hidden="true" style="margin-top: 3px">
                                <use xlink:href="/repgui/icon/sprite.svg#arrow-right"></use>
                            </svg>
                            {!! trans('repgui.repair_impact_calculation_conclusion_lifetime', ['years' => $calculationInfo['expectedRemainingLifetime']]) !!}
                        </p>
                        <p class="relative pl-8 text-base">
                            <svg class="absolute mr-1 -ml-8 text-lg text-white icon" aria-hidden="true" style="margin-top: 3px">
                                <use xlink:href="/repgui/icon/sprite.svg#arrow-right"></use>
                            </svg>
                            <strong>{!! trans('repgui.repair_impact_calculation_conclusion_impact', [
                                         'saving' => number_format($calculationInfo['co2_savings'], 0, ',', '.'),
                                         'years' => $calculationInfo['expectedRemainingLifetime'],
                                         'savingEquivalent' => $calculationInfo['eq_savings_days'] > 0 ? trans('repgui.repair_impact_calculation_viz_eq_days_format', ['amount' => $calculationInfo['eq_savings_days']]) : trans('repgui.repair_impact_calculation_viz_eq_hours_format', ['amount' => $calculationInfo['eq_savings_hours']] )]) !!}</strong>
                        </p>
                    </div>
                </div>
                @if($calculationInfo['minRequiredLifetime'])
                    <div class="p-6 mt-8 bg-white border border-black">
                        <h3 class="inline-flex text-lg font-normal text-black underline">
                            <svg class="mr-2" style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                            </svg>
                            {{ trans('repgui.repair_impact_calculation_footnote_title') }}
                        </h3>
                        <p class="text-base leading-normal">
                            {!! trans('repgui.repair_impact_calculation_footnote_info', ['minYears' => $calculationInfo['minRequiredLifetime'], 'years' => $calculationInfo['expectedRemainingLifetime']]) !!}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('repgui.partials.guidance.recommendations', ['repairGuidanceLog' => $repairGuidanceLog, 'deviceType' => $deviceType, 'recommendationInfo' => $recommendationInfo])
@endsection
