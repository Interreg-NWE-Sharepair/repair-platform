<div class="pl-4 ml-4 border-l-1 border-divider">
    <ul class="flex flex-wrap space-x-4">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="text-base text-black underline hover:text-primary-hover">
                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                   class="nav__link @if ($localeCode == LaravelLocalization::getCurrentLocale()) font-bold text-primary @endif">
                    {{ \Illuminate\Support\Str::upper($localeCode) }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
