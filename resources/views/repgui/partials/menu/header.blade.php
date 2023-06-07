<nav class="" aria-label="Service">
    <ul class="flex flex-wrap space-x-8">
        <li class="">
            <a class="text-base text-black underline hover:text-primary-hover {{ (Route::currentRouteName() == 'contribute_index') ? 'font-bold text-primary' : '' }}" href="{{ route('contribute_index') }}"> {{ trans('repgui.route_contribute_index') }}</a>
        </li>
        <li>
            <a class="text-base text-black underline hover:text-primary-hover {{ (Route::currentRouteName() == 'about') ? 'font-bold text-primary' : '' }}" href="{{ route('about') }}"> {{ trans('repgui.route_about_project') }}</a>
        </li>
        <li>
            <a class="text-base text-black underline hover:text-primary-hover {{ (Route::currentRouteName() == 'contact') ? 'font-bold text-primary' : '' }}" href="{{ route('contact') }}">{{ trans('messages.route_contact_index') }}</a>
        </li>
    </ul>
</nav>
