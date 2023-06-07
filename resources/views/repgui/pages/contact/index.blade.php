@extends('repgui.layouts.layout')

@section('content_header')
    <div class="section section--light">
        <div class="container">
            <h1 class="text-h1 text-secondary">{{ trans('messages.contact_title') }}</h1>
            <p class="w-full text-xl md:w-3/5">{{ trans('messages.contact_body') }}</p>
        </div>
    </div>
@endsection


@section('content_body')
    <div class="section section--default">
        <div class="container">
            <div class="w-full md:w-3/5">
                <form class="form" action="{{ route('contact_store') }}" method="POST" data-s-validate>
                    @csrf
                    <div class="flex flex-wrap -mx-3 ">
                        <div
                            class="w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-1/2 lg:w-1/2 xl:w-1/2 form__error-container">
                            <label class="form__label required" for="name">{{ trans('messages.form_name') }}</label>
                            <input class="form__input" type="text" name="name" required="required"/>
                        </div>
                        <div class="w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-1/2 lg:w-1/2 xl:w-1/2">
                            <label class="form__label required" for="email">{{ trans('messages.form_email') }}</label>
                            <input class="form__input" type="email" name="email" required="required"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-full lg:w-full xl:w-full">
                            <label class="form__label required" for="message">{{ trans('messages.form_message') }}</label>
                            <textarea class="form__input" name="message" rows="5" required="required"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="relative w-full px-3 mb-4 form__field main-column-class md:mb-0 md:w-full lg:w-full xl:w-full">
                            <input
                                class="absolute px-4 py-3 mt-1 leading-tight text-gray-700 bg-gray-100 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500"
                                type="checkbox" name="terms" id="terms" required="required"/>
                            <label for="terms" class="block pl-6">
                                {!! trans('messages.contact_accept_terms', ['conditions' => route('terms_conditions'), 'privacy' => route('privacy')]) !!}
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-4 -mx-3 ">
                        <div class="mb-3">
                            {!!  GoogleReCaptchaV3::renderField('contact_us_id','contact_us') !!}
                        </div>
                        <div
                            class="w-full px-3 mt-6 text-left form__field main-column-class md:mb-0 md:w-full lg:w-full xl:w-full">
                            <button class="btn btn--secondary btn--ext"
                                    type="submit">{{ trans('messages.send_message') }}</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {!!  GoogleReCaptchaV3::init() !!}
@endsection
