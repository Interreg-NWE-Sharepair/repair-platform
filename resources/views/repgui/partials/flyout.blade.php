@php
    $active = 'font-bold';
@endphp
<div class="invisible flyout" id="flyout">
    <div class="container">
        <div class="absolute top-0 right-0 mt-4 mr-4">
            <a href="#" class="text-2xl cursor-pointer js-flyout-close text-primary-hover" tabindex="0" role="button">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="/repgui/icon/sprite.svg#clear"></use>
                </svg>
                <span class="sr-only">Navigatie sluiten</span>
            </a>
        </div>
    </div>
    <div class="flyout__content">
        <div class="container">
            <nav class="flex-auto mt-6" aria-label="Mobile">
                <ul class="flex flex-col justify-end space-y-4">
                    <li class="">
                        <a href="{{ route('tutorial_index') }}" class="whitespace-no-wrap menu__item
                                            {{ (Route::currentRouteName() == 'tutorial_index') ? $active : '' }}" data-text="{{ trans('repgui.route_tutorial_index') }}">
                            {{ trans('repgui.route_tutorial_index') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('tips_index') }}" class="whitespace-no-wrap menu__item
                                            {{ (Route::currentRouteName() == 'tips_index') ? $active : '' }}" data-text="{{ trans('repgui.route_tips_index') }}">
                            {{ trans('repgui.route_tips_index') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('repair_map_index') }}" class="whitespace-no-wrap menu__item
                                            {{ (Route::currentRouteName() == 'repair_map_index') ? $active : '' }}" data-text="{{ trans('repgui.route_repair_map_index') }}">
                            {{ trans('repgui.route_repair_map_index') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('contribute_index') }}" class="whitespace-no-wrap menu__item
                                            {{ (Route::currentRouteName() == 'contribute_index') ? $active : '' }}" data-text="{{ trans('repgui.route_contribute_index') }}">
                            {{ trans('repgui.route_contribute_index') }}
                        </a>
                    </li>
                </ul>
                <ul class="pt-6 mt-6 space-y-2 border-t-1 border-divider">
                    <li>
                        <a class="text-base text-black underline hover:text-primary-hover {{ (Route::currentRouteName() == 'about') ? 'font-bold text-primary' : '' }}" href="{{ route('about') }}"> {{ trans('repgui.route_about_project') }}</a>
                    </li>
                    <li>
                        <a class="text-base text-black underline hover:text-primary-hover {{ (Route::currentRouteName() == 'contact') ? 'font-bold text-primary' : '' }}" href="{{ route('contact') }}">{{ trans('messages.route_contact_index') }}</a>
                    </li>
                </ul>
                <ul class="flex flex-wrap pt-6 mt-6 space-x-4 border-t-1 border-divider">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li class="text-base text-black underline hover:text-primary-hover">
                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            class="nav__link @if ($localeCode == LaravelLocalization::getCurrentLocale()) font-bold text-primary @endif">
                                {{ \Illuminate\Support\Str::upper($localeCode) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="flyout__overlay js-flyout-close"></div>
