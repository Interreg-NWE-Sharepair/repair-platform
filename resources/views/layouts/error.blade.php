@extends(siteIsRepgui() ? 'repgui.layouts.layout' : 'layouts.base')


@section('content_header')
    <div class="section section--default">
        <div class="container mx-auto">
            <h1>{{ __('error.' . $errorCode . '_title') }}</h1>
            <p>{{ __('error.' . $errorCode . '_text') }}</p>
            <a class="text-base text-black underline hover:text-primary-hover " href="{{ URL::to('/') }}">
                <span aria-hidden="true"></span><span>{{ __('error.back') }}</span>
            </a>
        </div>
    </div>
@endsection
