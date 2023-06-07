@extends('repgui.layouts.layout')
@php
    $deviceType = $repairGuidanceLog->deviceType;
@endphp
@section('content_header')
    <div class="section section--light">
        <div class="container">
            <div>
                <a class="link link--prev"
                   href="{{ route('guide_step_3') }}?uuid={{ $repairGuidanceLog->uuid }}">{{ trans('repgui.guidance_back_overview') }}</a>
            </div>
            <div>
                <h1 class="text-secondary">{{ trans('repgui.repair_it_map') }}</h1>
                <div class="w-full text-xl md:w-3/4">
                    <p>{{ trans('repgui.repair_it_map_text') }}</p>
                </div>
            </div>

            <div class="p-6 mt-8 bg-white border-4 rounded border-primary-200">
                <dl class="space-y-3">
                    <dd><strong>{{ trans('repgui.product_category') }}:</strong> {{ $deviceType->name }}</dd>

                    @if ($repairGuidanceLog->product_age)
                        <dd>
                            <strong>{{ trans('repgui.product_age') }}:</strong> {{ $repairGuidanceLog->product_age }}
                        </dd>
                    @endif
                    @if($repairGuidanceLog->common_issue_text)
                        <dd>
                            <strong>{{ trans('repgui.common_issue_text') }}
                                :</strong> {{ $repairGuidanceLog->common_issue_text }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
@section('content_body')
<div id="repguiMap">
    <div class="pb-2 section--light">
        <div class="container">
            <repair-map-filters @change="toggleFilter($event)" :active-filter="activeFilter" type-text="{{ trans('repgui.filter.type') }}" category-text="{{ trans('repgui.filter.category') }}"></repair-map-filters>
        </div>
    </div>
    <repgui-map baseUrl="{{ route('locations_show_redirect') }}" :active-filter="activeFilter" @close-filter="toggleFilter($event)" locale="{{ Lang::locale() }}"></repgui-map>
</div>
@endsection

<script src="{{ URL::asset('repgui/js/map.js') }}"></script>
