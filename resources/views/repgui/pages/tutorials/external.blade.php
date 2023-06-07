@extends('repgui.layouts.layout')
@section('content_header')
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/4">
                <h1 class="text-h1 text-primary">{{ trans('repgui.repair_guides_external_title') }}</h1>
                <div class="text-xl">{{ trans('repgui.repair_guides_external_description') }}</div>
            </div>
        </div>
    </div>
@endsection

@section('content_body')
    <div class="section section--light md:py-16">
        <div class="container">
            <div class="mt-8">
                <div class="flex flex-wrap -mx-2 -mt-4">
                    @foreach ($ordpGuides as $ordpGuide)
                        @include('repgui.partials.card.external-guide', ['ordpGuide' => $ordpGuide])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
