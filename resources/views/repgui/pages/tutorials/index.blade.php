@extends('repgui.layouts.layout')
@livewireStyles
@section('content_header')
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/4">
                <h1 class="text-h1 text-primary">{{ trans('repgui.repair_guides_title') }}</h1>
                <div class="text-xl">{{ trans('repgui.repair_guides_description') }}</div>
            </div>
        </div>
    </div>
@endsection

@section('content_body')
    @livewire('tutorials')
    <livewire:tutorials/>
    @livewireScripts
@endsection
