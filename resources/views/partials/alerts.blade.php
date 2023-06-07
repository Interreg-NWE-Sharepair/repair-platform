@foreach (['danger', 'warning', 'success', 'info'] as $alertType)
    @if (Session::has('alert-' . $alertType))
        <div class="notice notice--{{ $alertType }} spacer spacer--xxs">
            {!! Session::get('alert-' . $alertType) !!}
        </div>
    @endif
@endforeach
