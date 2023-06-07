<div class="nav nav--language {{ $class ?? '' }}">
    <ul class="nav__menu">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav__item">
                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                   class="nav__link @if ($localeCode == LaravelLocalization::getCurrentLocale()) is-active @endif">
                    {{ $properties['native'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
