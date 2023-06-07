<footer class="page-footer">
    <div class="py-6 text-white bg-primary">
        @include('repgui.partials.menu.footer', ['footer' => 'text-white'])
    </div>
    <div class="clearfix mt-6 partners">
        <div class="container">
            <div class="flex flex-wrap items-center justify-center -mx-4 -mt-4">
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://www.test-aankoop.be/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_testaankoop.jpeg" alt="Logo Test Aankoop" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://vito.be/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_vito.png" alt="Logo Vito" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://www.tudelft.nl/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_tu_delft_1.png" alt="Logo TU Delft" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://repairtogether.be/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_repair_together.png" alt="Logo Repair Together" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://therestartproject.org/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_restart.jpg" alt="Logo The Restart Project" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://repairshare.be/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_repairshare.png" alt="Logo Repair & Share" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://www.smarthubvlaamsbrabant.be/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_sh_vb.png" alt="Logo Smarthub Vlaams-Brabant" class="lazyload">
                    </a>
                </div>
                <div class="w-1/3 px-4 mt-4 text-center sm:w-1/6 lg:w-1/12">
                    <a href="https://www.nweurope.eu/projects/project-search/sharepair-digital-support-infrastructure-for-citizens-in-the-repair-economy/" target="_blank" rel="noopener">
                        <img data-src="/repgui/img/partners/logo_sharepair.png" alt="Logo Sharepair" class="lazyload">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="py-6 footer">
        <div class="container">
            <div class="xs:flex xs:flex-wrap xs:justify-center">
                <div class="flex flex-wrap items-center justify-center sm:flex-no-wrap">
                    <div class="sm:mr-4">
                        <span class="block font-semibold sm:hidden text-primary">{{ trans('repgui.repair_world_difference') }}</span>
                        <div
                            class="hidden pr-8 overflow-hidden rounded-full sm:block bg-primary sm:p-0 sm:bg-transparent sm:rounded-none">
                            <img src="/repgui/img/label-{{ app()->getLocale()}}.svg"
                                 alt="{{ trans('repgui.repair_world_difference') }}">
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="items-center text-center sm:text-left sm:flex">©
                            {{ __('repgui.footer_title_repgui') }},
                            {{ now()->year }}
                            <span class="hidden px-2 sm:block">-</span>
                            <div>
                                {{ trans('repgui.with') }}
                                <span class="sr-only">
                                    love
                                </span>
                                <svg class="mx-1 icon text-primary" aria-hidden="true">
                                    <use xlink:href="{{  url('') }}/repgui/icon/sprite.svg#heart"></use>
                                </svg>
                                {!! trans('repgui.love_by') !!}
                                <a target="_blank" rel="noopener" href="https://www.statik.be" class="ml-1 hover:underline hover:text-primary-hover">
                                Statik
                                </a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <nav>
                                <ul class="text-center divide-gray-700 sm:text-left xs:flex xs:flex-wrap xs:divide-x-1">
                                    <li class="xs:pr-2">
                                        <a class="underline hover:no-underline hover:text-primary-hover"
                                           href="{{ route('privacy') }}"> {{ trans('messages.footer_privacy') }}</a>
                                    </li>
                                    <li class="xs:px-2">
                                        <a class="underline hover:no-underline hover:text-primary-hover"
                                           href="{{ route('cookies') }}">{{ trans('messages.footer_cookies') }}</a>
                                    </li>
                                    <li class="xs:px-2">
                                        <a class="underline hover:no-underline hover:text-primary-hover"
                                           href="{{ route('terms_conditions') }}">{{ trans('messages.route_terms_conditions') }}</a>
                                    </li>
                                    <li class="xs:px-2">
                                        <a href="javascript:void(0)" class="underline hover:no-underline hover:text-primary-hover js-lcc-settings-toggle">@lang('cookie-consent::texts.alert_settings')</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
