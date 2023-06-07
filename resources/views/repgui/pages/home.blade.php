@extends('repgui.layouts.layout')

@section('content_header')
    <div class="section section--light">
        <div class="section__content">
            <div class="container">
                <div class="text--center">
                    <div class="flex flex-wrap -mx-4 -mt-8">
                        <div class="order-2 w-full px-4 mt-8 sm:w-1/2 sm:order-1">
                            <h1 class="text-h1 text-primary">{{ trans('repgui.home_title') }}</h1>
                            <div class="text-xl redactor">
                                {{ trans('repgui.home_description') }}
                            </div>
                            <div class="flex flex-wrap items-center mt-6">
                                <span class="inline-block mt-2 mr-2">
                                    <a class="btn btn--primary btn--ext"
                                    href="{{ route('guide_step_1') }}">{{ trans('repgui.start_guide') }}</a>
                                </span>
                                <span class="mt-2 mr-2">
                                    <a class="link hover:text-primary-hover link--ext"
                                    href="{{ route('about') }}">{{ trans('repgui.more_about_repair_guidance') }}</a>
                                </span>
                            </div>
                        </div>
                        <div class="order-1 w-full max-w-xs px-4 mx-auto sm:max-w-none sm:w-1/2 sm:block">
                            <img data-src="/repgui/img/guidance_hero.svg" alt="Guy with wrench" class="lazyload">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content_body')
    <div class="section section--default sm:pt-12">
        <div class="container">
            <h2 class="mb-1 text-h2 text-secondary">{{ trans('repgui.find_repair_solution') }}</h2>
            <div>{{ trans('repgui.3_simple_steps') }}</div>

            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4 mt-4 md:w-1/3">
                    <div class="pr-12">
                        <span class="text-6xl font-black leading-none text-secondary">1</span>
                        <h3 class="mt-2 mb-4 text-lg font-bold text-black md:heading--arrow">{{ trans('repgui.tell_more_about_device') }}</h3>
                        <div class="text-base">{{ trans('repgui.tell_more_about_device_text') }}</div>
                    </div>
                </div>
                <div class="w-full px-4 mt-4 md:w-1/3">
                    <div class="pr-12">
                        <span class="text-6xl font-black leading-none text-secondary">2</span>
                        <h3 class="mt-2 mb-4 text-lg font-bold text-black md:heading--arrow">{{ trans('repgui.describe_the_problem') }}</h3>
                        <div class="text-base">{{ trans('repgui.describe_the_problem_text') }}</div>
                    </div>
                </div>
                <div class="w-full px-4 mt-4 md:w-1/3">
                    <div class="pr-12">
                        <span class="text-6xl font-black leading-none text-secondary">3</span>
                        <h3 class="mt-2 mb-4 text-lg font-bold text-black">{{ trans('repgui.check_repair_options') }}</h3>
                        <div class="text-base">{{ trans('repgui.check_repair_options_text') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <a class="btn btn--secondary btn--ext"
                    href="{{ route('guide_step_1') }}">{{ trans('repgui.start_guide') }}</a>
            </div>
        </div>
    </div>
    <div class="section section--default sm:pt-12">
        <div class="container">
            <h2 class="text-h2 text-primary">{{ trans('repgui.repair_impact_home_title') }}</h2>
            <div>{!! trans('repgui.repair_impact_home_info') !!}</div>
            <div class="mt-8">
                <a class="btn btn--primary btn--ext"
                   href="{{ route('repair_impact_calculation_index') }}">{{ trans('repgui.cta_repair_impact_index') }}</a>
            </div>
        </div>
    </div>
    <div class="section section--light">
        <div class="container">
            <div class="flex flex-row-reverse flex-wrap -mx-4 -mt-8">
                <div class="w-full px-4 mt-8 md:w-2/6">
                    <div class="justify-end md:flex">
                        <img data-src="/repgui/img/guidance_diy.svg" alt="Guy with book" class="lg:absolute lazyload">
                    </div>
                </div>
                <div class="w-full px-4 mt-8 md:w-4/6">
                    <h2 class="text-h2 text-secondary">{{ trans('repgui.in_the_picture') }}</h2>
                    <p>{{ trans('repgui.in_the_picture_text') }}</p>
                </div>
            </div>
            @if($tutorials)
                <div class="flex flex-wrap w-full mt-8 -mx-2 md:w-3/4">
                    @foreach ($tutorials as $tutorial)
                        <div class="w-full px-2 mt-4 sm:w-1/2">
                            <div class="relative flex flex-col w-full h-full p-6 bg-white rounded-card">
                                <a href="{{ route('tutorial_show', ['repairTutorial' => $tutorial]) }}">
                                    <h3 class="flex items-start mb-1 text-2xl font-semibold">
                                        <svg class="mr-1 icon" aria-hidden="true">
                                            <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
                                        </svg>
                                        {{ $tutorial->title }}
                                    </h3>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="section section--default">
        <div class="container mx-auto">
            <h2 class="text-h2 text-primary">{{ trans('repgui.explore_all_repair_options') }}</h2>
            <div class="w-full md:w-3/4">
                <p>{{ trans('repgui.explore_all_repair_options_text') }}</p>
            </div>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4 mt-8 md:w-1/2">
                    <div class="h-full p-8 bg-light rounded-card">
                        <h3 class="text-h3 text-primary">{{ trans('repgui.diy') }}</h3>
                        <p>{{ trans('repgui.diy_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--primary btn--ext"
                            href="{{ route('tutorial_index') }}">{{ trans('repgui.show_all_tutorials') }}</a>
                        </div>
                        <div class="mt-2">
                            <a class="text-base text-black underline link--ext hover:text-primary-hover"
                            href="{{ route('tips_index') }}">{{ trans('repgui.general_tips_diy') }}</a>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 mt-8 md:w-1/2">
                    <div class="h-full p-8 bg-light rounded-card">
                        <h3 class="text-h3 text-primary"> {{ trans('repgui.repair_cafe') }}</h3>
                        <p> {{ trans('repgui.repair_cafe_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--primary btn--ext"
                            href="{{ route('repair_map_index', ['type' => 'c3f479e8-e35b-43bf-83b9-64a0b4ae1a6e']) }}&organisation_types=repair_cafe">{{ trans('repgui.show_nearby_repaircafe') }}</a>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 mt-8 md:w-1/2">
                    <div class="h-full p-8 bg-light rounded-card">
                        <h3 class="text-h3 text-primary"> {{ trans('repgui.professionals') }}</h3>
                        <p> {{ trans('repgui.professionals_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--primary btn--ext"
                            href="{{ route('repair_map_index', ['type' => '7b404df9-bdcb-4a69-8588-507242d25044']) }}&organisation_types=professional_repairer">{{ trans('repgui.show_professionals') }}</a>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 mt-8 md:w-1/2">
                    <div class="h-full p-8 bg-light rounded-card">
                        <h3 class="text-h3 text-primary"> {{ trans('repgui.recycle') }}</h3>
                        <p> {{ trans('repgui.recycle_text') }}</p>
                        <div class="mt-6">
                            <a class="btn btn--primary btn--ext"
                            href="{{ route('recycle') }}">{{ trans('repgui.recycle_link') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section section--default">
        <div class="container">
            <div class="p-8 bg-light md:p-12 rounded-panel">
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
