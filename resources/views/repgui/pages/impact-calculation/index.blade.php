@extends('repgui.layouts.layout')
@section('content_header')
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/4">
                <h1 class="text-h1 text-secondary">{{ trans('repgui.repair_impact_calculation_title') }}</h1>
                <div class="text-xl">{!! trans('repgui.repair_impact_calculation_info') !!}</div>
            </div>
        </div>
    </div>
@endsection
@section('content_body')
    <div class="section section--default">
        <div class="container">
            <form class="form" action="{{ route('repair_impact_calculation_calculate') }}" method="POST">
                @csrf
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-1/2 lg:w-1/2 xl:w-1/2 form__error-container">
                        <label class="form__label required" for="device_category">{{ trans('repgui.repair_impact_calculation_device_category_field') }}</label>
                        <div class="relative">
                            <select class="pr-8 mb-0 appearance-none form__select" name="device_category" id="device_category" required="required">
                                @foreach ($productCategories as $key => $label)
                                    <option value="{{ $key }}" @if($key == request()->get('product_category')) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                            {{-- Svg icon chevron down --}}
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap mt-6 -mx-3">
                    <div class="w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-2/6 form__error-container">
                        <label class="form__label required" for="device_age">{{ trans('repgui.repair_impact_calculation_device_age_field') }}</label>
                        <input class="form__input" type="number" min="1" name="device_age" id="device_age" required="required" value="{{request()->get('product_age')}}"/>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3">
                    <div
                        class="w-full px-3 mt-6 text-left form__field main-column-class md:mb-0 md:w-full lg:w-full xl:w-full">
                        <button class="btn btn--secondary btn--ext"
                                type="submit">{{ trans('repgui.repair_impact_calculation_submit_button') }}</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
