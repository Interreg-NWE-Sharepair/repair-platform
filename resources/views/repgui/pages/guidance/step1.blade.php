@extends('repgui.layouts.layout')
@section('content_body')
    <div class="section section--light">
        <div class="container">
            <div>
                <form method="POST" action="{{ route('guide_step_1_store') }}" class="form">
                    @csrf
                    <div class="sm:flex">
                        @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => true, 'top' => true])
                    </div>
                    <div>
                        <h2>{{ trans('repgui.tell_more_about_device') }}</h2>
                        <p>{{ trans('repgui.tell_more_about_device_intro') }}</p>
                        @if($deviceTypes)
                            <div class="mt-10">
                                <label class="form__label"
                                       for="type">{{ trans('repgui.guidance_product_type') }}</label>
                                <div class="flex-wrap hidden -mx-4 md:flex">
                                    @foreach ($deviceTypes as $deviceType)
                                        <div class="w-full px-4 mt-4 md:w-1/3">
                                            <div class="mb-1 font-bold">{{$deviceType['name']}}</div>
                                            <ul class="space-y-1">
                                                @foreach($deviceType['options'] as $option)
                                                    <li>
                                                        <label for="{{ $option->id }}">
                                                            <input id="{{ $option->id }}" type="radio"
                                                                   name="device_type_id" value="{{ $option->id }}"
                                                                   @if((old('device_type_id') == $option->id) || (isset($repairGuidanceLog) && $repairGuidanceLog->deviceType->id == $option->id)) checked="checked" @endif>
                                                            {{ $option->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    @endforeach
                                </div>
                                <div class="max-w-2xl form__field md:hidden">
                                    <select name="device_type_mobile_id" id="device_type_mobile_id" data-s-autocomplete class="form__select">
                                        @foreach ($deviceTypes as $deviceType)
                                            <option value="">{{ trans('repgui.select_option') }}</option>
                                            <optgroup label="{{ $deviceType['name'] }}">
                                                @foreach($deviceType['options'] as $option)
                                                    <option value="{{ $option->id }}"  @if((old('device_type_mobile_id') == $option->id) || (isset($repairGuidanceLog) && $repairGuidanceLog->deviceType->id == $option->id))selected="selected" @endif>{{ $option->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-wrap mt-6 -mx-3">
                        <div
                            class="w-full px-3 mb-6 form__field main-column-class md:w-1/2">
                            <label class="form__label" for="name">{{ trans('messages.form_brand_name') }}
                                ({{ trans('messages.optional') }})</label>
                            @php
                                $brand_name = old('brand_name');
                                if (!$brand_name && isset($repairGuidanceLog) && $repairGuidanceLog->brand_name) {
                                    $brand_name = $repairGuidanceLog->brand_name;
                                }
                            @endphp
                            <input class="form__input" type="text" name="brand_name"
                                   @if($brand_name) value="{{ $brand_name  }}" @endif
                                   placeholder="{{ trans('messages.form_brand_name_placeholder') }}"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div
                            class="w-full px-3 mb-6 form__field main-column-class md:w-1/2">
                            <label class="form__label" for="name">{{ trans('messages.form_model_name') }}
                                ({{ trans('messages.optional') }})</label>
                            @php
                                $model_name = old('model_name');
                                if (!$model_name && isset($repairGuidanceLog) && $repairGuidanceLog->model_name) {
                                    $model_name = $repairGuidanceLog->model_name;
                                }
                            @endphp
                            <input class="form__input" type="text" name="model_name"
                                   @if($model_name) value={{ $model_name }} @endif
                                       placeholder="{{ trans('messages.form_model_name_placeholder') }}"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div
                            class="w-full px-3 mb-6 form__field main-column-class md:w-1/2">
                            <label class="form__label" for="name">{{ trans('repgui.form_product_description') }}
                                ({{ trans('messages.optional') }})</label>
                            @php
                                $product_description = old('product_description');
                                if (!$product_description && isset($repairGuidanceLog) && $repairGuidanceLog->product_description) {
                                    $product_description = $repairGuidanceLog->product_description;
                                }
                            @endphp
                            <input class="form__input" type="text" name="product_description"
                                   @if($product_description) value={{ $product_description }} @endif
                                       placeholder="{{ trans('repgui.form_product_description_placeholder') }}"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-6 -mx-3 ">
                        <div class="w-full px-3 mb-6 form__field main-column-class md:w-full">
                            <label class="form__label" for="name">{{ trans('repgui.form_age') }}
                                ({{ trans('messages.optional') }})</label>
                            @php
                                $ages = ['2', '2-5', '5-10', '10-15', '15'];
                                $product_age = old('product_age');
                                if (!$product_age && isset($repairGuidanceLog) && $repairGuidanceLog->product_age) {
                                        $product_age = $repairGuidanceLog->product_age;
                                    }
                            @endphp
                            <ul>
                                @foreach ($ages as $age)
                                    <li class="space-y-1">
                                        <label for="age-{{ $age }}">
                                            <input class="form_input" type="radio" name="product_age"
                                                   id="age-{{ $age }}" value="{{ $age }}"
                                                   @if($product_age == $age) checked="checked" @endif">
                                            {{ trans("repgui.form_age_$age") }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => false, 'top' => false])
                </form>
            </div>
        </div>
    </div>
@endsection
