<div class="relative px-4 py-3 my-2 text-black bg-red-200 rounded-lg sm:px-6 sm:py-5 notice notice--danger">
    <svg class="absolute mt-1 icon" aria-hidden="true">
        <use xlink:href="{{  url('') }}/repgui/icon/sprite.svg#information-outline"></use>
    </svg>
    <ul class="pl-8 list list--reset">
        @foreach ($errors->all() as $error)
            <li>{!! $error !!}</li>
        @endforeach
    </ul>
</div>
