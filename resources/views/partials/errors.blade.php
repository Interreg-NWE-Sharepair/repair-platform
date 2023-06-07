@if ($errors->any())
    <div class="notice notice--danger spacer">
        <ul class="list list--reset">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
