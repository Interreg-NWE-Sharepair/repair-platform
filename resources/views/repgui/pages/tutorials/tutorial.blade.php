@extends('repgui.layouts.layout')

@section('content_header')
    <div class="section section--light">
        <div class="section__content">
            <div class="container">
                @if ($repairGuidanceLog)
                    <div>
                        <a class="link link--prev"
                           href="{{ route('guide_step_diy') }}?uuid={{ $repairGuidanceLog->uuid }}">{{ trans('repgui.guidance_back_overview') }}</a>
                    </div>
                @else
                    <div>
                        <a class="link link--prev"
                            href="{{ route('tutorial_index') }}">{{ trans('repgui.tutorial_index_overview') }}</a>
                    </div>
                @endif
                @if($showDisclaimer)
                    <div class="relative px-4 py-3 my-2 mt-4 text-black bg-red-200 rounded-lg sm:px-6 sm:py-5 notice notice--danger">
                        <svg class="absolute mt-1 icon" aria-hidden="true">
                            <use xlink:href="{{  url('') }}/repgui/icon/sprite.svg#information-outline"></use>
                        </svg>
                        <ul class="pl-8 list list--reset">
                            <li>{{__('messages.tutorial_disclaimer')}}</li>
                        </ul>
                    </div>
                @endif
                <div class="mt-3">
                    <h1 class="flex items-center text-h1 text-secondary">
                        <svg class="mr-1 text-3xl icon" aria-hidden="true">
                            <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
                        </svg>
                        {{ $tutorial->title }}
                    </h1>
                    <div class="inline-flex flex-wrap items-center px-2 py-1 text-sm font-semibold bg-white rounded text-secondary">
                        <span>{{ $tutorial->deviceType->name }}</span>
                        @if ($tutorial->commonDeviceTypeIssue)
                            <span class="inline-block w-px h-4 mx-3 -skew-x-12 bg-gray-500" aria-hidden="true"></span><span class="font-normal text-gray-900">{{ $tutorial->commonDeviceTypeIssue->issue }}</span>
                        @endif
                    </div>
                    <div class="w-full mt-4 md:w-3/5">
                        {{ $tutorial->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_body')
    <div class="section section--default">
        <div class="container mx-auto">
            @foreach($tutorial->flexible as $item)
                @if($item['layout'] ==='header')
                    <x-repgui.header :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'wysiwyg')
                    <x-repgui.wysiwyg :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'image')
                    <x-repgui.image :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'images')
                    <x-repgui.images :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'video')
                    <x-repgui.video :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'wysiwyg-image')
                    <x-repgui.wysiwygimage :data="$item['attributes']"/>
                @elseif ($item['layout'] === 'source')
                    <x-repgui.source :data="$item['attributes']"/>
                @endif
            @endforeach
        </div>
    </div>
    <div class="section section--default">
        <div class="container">
            <div class="p-8 bg-gray-100 md:p-12 rounded-panel">
                <div class="flex flex-wrap -mx-4 -mt-8">
                    <div class="w-full px-4 mt-8 md:w-3/5">
                        <h2 class="text-h2 text-secondary">
                            {{ trans('repgui.maker_who_likes_to_repair') }}
                        </h2>
                        <p>{{ trans('repgui.maker_who_likes_to_repair_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--secondary btn--ext"
                               href="{{ route('contact') }}">{{ trans('repgui.get_in_touch') }}</a>
                        </div>
                    </div>
                    <div class="w-full px-4 mt-8 md:w-2/5">
                        <img data-src="/repgui/img/guidance_repaircafe.svg" alt="Guy repair item" class="lazyload">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
