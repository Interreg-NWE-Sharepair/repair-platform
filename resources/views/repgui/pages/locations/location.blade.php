@extends('repgui.layouts.layout')
@php
    if ($location->organisationType->code == 'professional_repairer') {
        $pinColor = 'text-professional-repairer';
    } elseif ($location->organisationType->code == 'fablab') {
        $pinColor = 'text-fablab';
    } elseif ($location->organisationType->code == 'repair_cafe') {
        $pinColor = 'text-repair-cafe';
    } elseif ($location->organisationType->code == 'urban_repair_center') {
        $pinColor = 'text-urban-repair-center';
    } elseif ($location->organisationType->code == 'spare_parts_shop_or_library') {
        $pinColor = 'text-spare-parts-shop-or-library';
    } elseif ($location->organisationType->code == 'recycling_center') {
        $pinColor = 'text-recycling-center';
    } else {
        $pinColor = 'text-black';
    }
@endphp
@section('content_header')
    <div class="section section--light">
        <div class="container">
            <div class="flex flex-wrap -mx-4">
                <div class="order-2 w-full px-4 mt-6 sm:w-2/3 sm:order-1 sm:mt-0">
                    <h1>{{ $location->name }} @if($location->organisationHasMoreLocations()) ({{ $location->organisation->name }})@endif</h1>
                    <div class="flex flex-col flex-wrap -mx-4 text-lg font-semibold sm:flex-row skew-dividers">
                        <div class="px-4 md:mt-0">
                            <svg class="-ml-1 text-lg {{ $pinColor }} icon" aria-hidden="true">
                                <use xlink:href="/repgui/icon/sprite.svg#place"></use>
                            </svg>
                            {{ $location->organisationType->name }}
                        </div>
                        @if($location->organisation->is_rc_active)
                        <div class="px-4 md:mt-0">{!! trans('repgui.active_repairers', ['repairers' => $amountRepairers]) !!}</div>
                        @endif
                        @if ($amountDevices > 0)
                        <div class="px-4 md:mt-0">{!! trans('repgui.devices_repaired', ['devices' => $amountDevices]) !!}</div>
                        @endif
                    </div>
                    <div class="mt-4 text-xl redactor">
                        @if($location->description)
                            {!! $location->description !!}
                        @elseif($location->organisation->description)
                            {!! $location->organisation->description !!}
                        @endif
                    </div>
                    <div class="mt-10">
                        <ul class="space-y-2">
                            <li class="relative">
                                <svg class="absolute mt-1 -ml-1 text-xl icon text-primary" aria-hidden="true">
                                    <use xlink:href="/repgui/icon/sprite.svg#place"></use>
                                </svg>
                                <div class="pl-6">
                                    @if($location->google || $location->organisation->google)
                                        @php
                                        $link = $location->google;
                                        if (!$link) {
                                            $link = $location->organisation->google;
                                        }
                                        @endphp
                                        <a href="{{ $link }}" class="underline hover:no-underline" target="_blank">
                                    @endif
                                    {{ $location->address }}
                                    @if($location->google || $location->organisation->google)
                                        </a>
                                    @endif
                                </div>
                            </li>
                            @if ($location->telephone || $location->organisation->telephone)
                                <li class="relative">
                                    <svg class="absolute mt-1 -ml-1 text-xl icon text-primary" aria-hidden="true">
                                        <use xlink:href="/repgui/icon/sprite.svg#phone"></use>
                                    </svg>
                                    <div class="pl-6">
                                        @if ($location->telephone)
                                            {{ $location->telephone }}
                                        @elseif ($location->organisation->telephone)
                                            {{ $location->organisation->telephone }}
                                        @endif
                                    </div>
                                </li>
                            @endif
                            @if($location->email || $location->organisation->email)
                                <li class="relative">
                                    <svg class="absolute mt-1 -ml-1 text-lg icon text-primary" aria-hidden="true">
                                        <use xlink:href="/repgui/icon/sprite.svg#envelope"></use>
                                    </svg>
                                    <div class="pl-6">
                                        @if($location->email)
                                            <a href="mailto:{{ $location->email }}"
                                               class="underline hover:no-underline">{{ $location->email }}</a>
                                        @elseif($location->organisation->email)
                                            <a href="mailto:{{ $location->organisation->email }}"
                                               class="underline hover:no-underline">{{ $location->organisation->email }}</a>
                                        @endif
                                    </div>
                                </li>
                            @endif
                            @if($location->website || $location->organisation->website)
                                <li class="relative">
                                    <svg class="absolute mt-1 -ml-1 text-xl icon text-primary" aria-hidden="true">
                                        <use xlink:href="/repgui/icon/sprite.svg#public"></use>
                                    </svg>
                                    <div class="pl-6">
                                        @if ($location->website)
                                            <a href="{{ $location->website }}"
                                               class="underline hover:no-underline" target="_blank">{{ $location->website }}</a>
                                        @elseif ($location->organisation->website)
                                            <a href="{{ $location->organisation->website }}"
                                               class="underline hover:no-underline">{{ $location->organisation->website }}</a>
                                        @endif
                                    </div>
                                </li>
                            @endif
                            @if($location->facebook || $location->organisation->facebook)
                                <li class="relative">
                                    <svg class="absolute mt-1 -ml-1 text-xl icon text-primary" aria-hidden="true">
                                        <use xlink:href="/repgui/icon/sprite.svg#facebook"></use>
                                    </svg>
                                    <div class="pl-6">
                                        @if ($location->facebook)
                                            <a href="{{ $location->facebook }}"
                                               class="underline hover:no-underline" target="_blank">{{ trans('messages.facebook', ['name' => $location->name]) }}</a>
                                        @elseif ($location->organisation->facebook)
                                            <a href="{{ $location->organisation->facebook }}"
                                               class="underline hover:no-underline">{{ trans('messages.facebook', ['name' => $location->name]) }}</a>
                                        @endif
                                    </div>
                                </li>
                            @endif
                            @if($location->instagram || $location->organisation->instagram)
                                <li class="relative">
                                    <svg class="absolute mt-1 -ml-1 text-xl icon text-primary" aria-hidden="true">
                                        <use xlink:href="/repgui/icon/sprite.svg#instagram"></use>
                                    </svg>
                                    <div class="pl-6">
                                        @if ($location->instagram)
                                            <a href="{{ $location->instagram }}"
                                               class="underline hover:no-underline" target="_blank">{{ trans('messages.instagram', ['name' => $location->name]) }}</a>
                                        @elseif ($location->organisation->instagram)
                                            <a href="{{ $location->organisation->instagram }}"
                                               class="underline hover:no-underline"> {{ trans('messages.instagram', ['name' => $location->name]) }}</a>
                                        @endif
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @if ($location->organisation->has_warranty && $location->organisation->warranty_description)
                        <div class="mt-6">
                            <h2 class="text-h2 text-secondary">
                                {{ trans('repgui.warranty_title') }}
                            </h2>
                            {{ $location->organisation->warranty_description }}
                        </div>
                    @endif
                </div>
                <div class="order-1 w-full max-w-xs px-4 mx-auto sm:max-w-none sm:w-1/3 sm:block">
                    <div>
                        @if ($location->image_url)
                        <img data-src="{{ $location->image_url }}" class="lazyload">
                        @else
                            <img data-src="{{ $location->organisation->image }}" class="lazyload">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content_body')
    @if ($location->organisation->deviceTypes->count())
        <div class="pb-8 section section--default">
            <div class="container">
                <div class="w-full p-8 sm:p-10 rounded-panel bg-light">
                    <h2 class="md:text-3xl text-primary">{{ trans('repgui.location_product_categories') }}</h2>
                    <div class="flex flex-wrap -mx-1 -mt-1">
                        @foreach ($location->organisation->deviceTypes->all() as $deviceType)
                            <div class="px-1 m-1 font-bold bg-white rounded text-secondary text-tiny">{{ $deviceType->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($location->getImage())
        <div class="pb-8 section section--default">
            <div class="container">
                <div class="w-1/2 px-4 mt-4 md:w-1/3">
                    <a class="block m-2 overflow-hidden js-modal-image modal__image-trigger" data-group="image-gallery" href="{{ $location->getImage() }}" role="button">
                        <img data-src="{{ $location->getImage() }}" class="lazyload" alt="">
                    </a>
                </div>
            </div>
        </div>
    @endif
    @if ($location->organisation->is_rc_active)
        <div class="pb-8 section section--default">
            <div class="container">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4 mt-6 sm:w-1/3 sm:mt-0">
                        <div class="h-full p-8 bg-light rounded-card">
                            <h3 class="text-h3 text-primary">{{ trans('repgui.device_repair_at', ['location' => $location->name]) }}</h3>
                            <p>{{ trans('repgui.device_repair_at_text', ['location' => $location->name]) }}</p>
                            <div class="mt-6">
                                <a href="{{ route('device_create') }}?uuid={{ $location->uuid }}"
                                   class="btn btn--primary btn--ext" target="_blank">{{ trans('messages.create_device_title') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 mt-6 sm:w-2/3 sm:mt-0">
                        <div class="h-full p-8 bg-light rounded-card">
                            <h3 class="text-h3 text-secondary">{{ trans('repgui.maker_who_likes_to_repair') }}</h3>
                            <p>{{ trans('repgui.maker_who_likes_to_repair_text', ['location' => $location->name]) }}</p>
                            <div class="mt-6">
                                <a href="{{ route('repairer_register_index') }}?uuid={{ $location->uuid }}"
                                   class="btn btn--secondary btn--ext" target="_blank">{{ trans('messages.register_repairer_title') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="section section--default">
        <div class="container">
            <div class="w-full p-8 sm:p-10 rounded-panel bg-light">
                <h2>{{ trans('repgui.something_wrong_repair_initiative') }}</h2>
                <p>{{ trans('repgui.something_wrong_repair_initiative_text') }}</p>
                <div class="mt-6">
                    <a href="https://mapping.sharepair.org/location/{{ $location->slug }}/edit"
                       class="btn btn--secondary btn--ext" target="_blank">{{ trans('repgui.suggest_correction') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
