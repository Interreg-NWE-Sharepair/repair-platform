@extends('repsta.layouts.base')

@section('content_body')
    <div>
        @if($image)
            <img src="{{ $image }}" alt="" class="w-full" loading="lazy">
        @endif

        <div id="heroNr4" class="">
            <span>{{ $data['events'] }}</span>
            {{ __('messages.organized_events') }}
        </div>
        <div id="heroNr1" class="">
            <span>{{ number_format($data['total_co2'], 0, ',', '.') }} kg</span>
            <div class="inline-flex">{!! __('messages.prevented_co2') !!}</div>
        </div>
        <div id="heroNr2" class="">
            <span>{{ number_format($data['total_weight'], 0, ',', '.') }} kg</span>
            {{ __('messages.prevented_waste') }}
        </div>
        <div id="heroNr3" class="">
            <span>{{ $data['devices']['total']['absolute'] }}</span>
            {{ __('messages.total_devices')}}
        </div>
    </div>
@endsection
