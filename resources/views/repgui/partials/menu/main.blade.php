@php
    $active = 'font-bold text-primary';
@endphp
@if (isset($footer))
    @php
    $active = 'font-bold';
    @endphp
    <div class="{{ $footer }}">
        @endif
        <div class="flex items-center justify-between">
            @if (isset($footer))
            <a href="{{ route('home_index')  }}" class="flex-shrink-0 block mr-6">
                <img class="w-auto md:w-full lg:w-auto max-h-logo-mobile xl:max-h-logo"
                     src="{{ asset('repgui/img/logo_contrast.svg') }}" alt="Logo footer">
            </a>
            @else
            <a href="{{ route('home_index')  }}" class="flex-shrink-0 block mr-6">
                <img class="w-auto md:w-full lg:w-auto max-h-logo-mobile xl:max-h-logo"
                     src="{{ asset('repgui/img/logo.svg') }}" alt="Logo">
            </a>
            @endif
            <nav class="flex-auto hidden md:block" aria-label="Main">
                <ul class="flex items-center justify-end space-x-4 xl:space-x-6">
                    <li class="">
                        <a href="{{ route('tips_index') }}" class="whitespace-no-wrap menu__item text-base lg:text-lg @if (!isset($footer))hover:text-primary-hover @endif
											 {{ (Route::currentRouteName() == 'tips_index') ? $active : '' }}" data-text="{{ trans('repgui.route_tips_index') }}">
                            {{ trans('repgui.route_tips_index') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('tutorial_index') }}" class="whitespace-no-wrap menu__item text-base lg:text-lg @if (!isset($footer))hover:text-primary-hover @endif
											 {{ (Route::currentRouteName() == 'tutorial_index') ? $active : '' }}" data-text="{{ trans('repgui.route_tutorial_index') }}">
                            {{ trans('repgui.route_tutorial_index') }}
                        </a>
                    </li>
                    <li class="">
                        {{--<a href="{{ route('repair_map_index') }}" class="whitespace-no-wrap menu__item text-base lg:text-lg @if (!isset($footer))hover:text-primary-hover @endif
											 {{ (Route::currentRouteName() == 'repair_map_index') ? $active : '' }}" data-text="{{ trans('repgui.route_repair_map_index') }}">
                            {{ trans('repgui.route_repair_map_index') }}
                        </a>--}}
                        <a href="{{ route('repair_impact_calculation_index') }}" class="whitespace-no-wrap menu__item text-base lg:text-lg @if (!isset($footer))hover:text-primary-hover @endif
											 {{ (Route::currentRouteName() == 'repair_impact_calculation_index') ? $active : '' }}" data-text="{{ trans('repgui.route_repair_impact_index') }}">
                            {{ trans('repgui.nav_repair_impact_index') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('guide_step_1') }}" class="text-base btn btn--secondary lg:text-lg">
                            <span class="hidden lg:inline-block">{{ trans('repgui.start_guide') }}</span>
                            <span class="lg:hidden">{{ trans('repgui.start_guide_mobile') }}</span>
                        </a>
                    </li>
                </ul>
            </nav>

            @if (!isset($footer))
                <a href="#flyout" class="text-3xl js-flyout-toggle md:hidden text-secondary hover:text-secondary-hover"
                role="button" aria-expanded="false">
                    <span class="sr-only">Menu</span>
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="/repgui/icon/sprite.svg#menu"></use>
                    </svg>
                </a>
            @endif
        </div>
        @if (isset($footer))
    </div>
@endif
