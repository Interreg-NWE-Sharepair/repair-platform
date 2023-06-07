@extends('repgui.layouts.layout')
@section('content_header')
    <div class="section section--light">
        <div class="container">
            @if (isset($repairGuidanceLog))
                <div>
                    <a class="link link--prev"
                        href="{{ route('guide_step_3') }}?uuid={{ $repairGuidanceLog->uuid }}">{{ trans('repgui.guidance_back_overview') }}</a>
                </div>
            @endif
            <div class="text--center">
                <div class="mx-auto">
                    <h1 class="text-h1 text-primary">
                        {{ $title }}
                    </h1>
                    @if ($intro ?? null)
                        <div class="text-xl redactor">
                            <p>{!! $intro !!}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_body')
    <div class="section section--default">
        <div class="container mx-auto">
            @foreach($flexible as $item)
                @if($item['layout'] ==='header')
<!--                    <x-repgui.header :data="$item['attributes']"/>-->
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
@endsection
