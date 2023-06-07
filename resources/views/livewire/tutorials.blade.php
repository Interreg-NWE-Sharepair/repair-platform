<div>
    @if ((isset($filters) && $filters) || (isset($searchSetting) && $searchSetting))
        <div class="section section--light">
            <div class="container">
                @if (isset($searchSetting) && $searchSetting)
                    <div class="w-full md:w-3/4">
                        <div class="flex flex-wrap items-end -mx-2 -mt-4">
                            <div class="w-full px-2 mt-4 md:w-3/4 input-container">
                                <label class="text-xl form__label"
                                       for="search">{{ trans('repgui.enter_keyword') }}</label>
                                <input class="mb-0 form__input" wire:model.defer="search" type="text"
                                       placeholder="{{ trans('repgui.search_tutorial') }}">
                            </div>
                            <div class="w-full px-2 mt-4 md:w-1/4 submit-container">
                                <button class="btn btn--secondary btn--ext"
                                        wire:click="search">{{ trans('messages.search') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-end w-full md:w-3/4">
                        <div class="mt-2 text-sm leading-none text-gray-600">{{ trans('repgui.search_help') }}</div>
                    </div>
                @endif
                @if($deviceTypes && (isset($filters) && $filters))
                    <div class="mt-6 md:mt-8">
                        <label class="text-xl form__label" for="type">{{ trans('repgui.search_by_type') }}</label>
                        <ul class="mb-4 space-y-1">
                            <li>
                                <label for="no-filter" class="cursor-pointer">
                                    <input wire:model="type" id="no-filter" type="radio" name="type"
                                           value="" style="accent-color: #71B8C5"
                                           class="filter__input-radio"/> {{ trans('repgui.filter_none') }}
                                </label>
                            </li>
                        </ul>
                        <div class="flex flex-wrap" style="gap: 2rem;">
                            @foreach ($deviceTypes as $deviceType)
                                <ul class="space-y-1">
                                    <li class="font-semibold">{{$deviceType['name']}}</li>
                                    @foreach($deviceType['options'] as $option)
                                        <li>
                                            <label for="label-{{ $option->uuid }}" class="cursor-pointer">
                                                <input wire:model="type" id="label-{{ $option->uuid }}" type="radio"
                                                       name="type"
                                                       value="{{ $option->uuid }}"
                                                       style="accent-color: #71B8C5"
                                                       class="filter__input-radio"/> {{ $option->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                @endforeach
                                </ul>
                        </div>
                    </div>
                    @if($deviceTypeIssues)
                        <div class="mt-6 md:mt-8">
                            <label class="text-xl form__label"
                                   for="faults">{{ trans('repgui.search_by_fault') }}</label>
                            <ul class="mt-4 space-y-1">
                                @foreach($deviceTypeIssues as $deviceTypeIssue)
                                    <li>
                                        <label for="faults-{{ $deviceTypeIssue->id }}">
                                            <input id="faults-{{ $deviceTypeIssue->id }}" wire:model="faults"
                                                   type="checkbox"
                                                   value="{{ $deviceTypeIssue->id }}"
                                                   style="accent-color: #71B8C5"
                                                   class="filter__input-checkbox"
                                                   checked/> {{ $deviceTypeIssue->issue }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
    <div class="section section--default">
        <div class="container">
            <div wire:loading.grid>
                @include('repgui.partials.loader')
            </div>
            <div wire:loading.remove>
                <div class="mb-4 font-semibold">{{ $tutorials->links('repgui.partials.pagination.total') }}</div>
                <div class="flex flex-wrap -mx-2 -mt-4">
                    @forelse ($tutorials as $repairGuide)
                        @include('repgui.partials.card.diy', ['bgColor' => 'light', 'target' => $target, 'params' => $params])
                    @empty
                        <p class="px-2 mt-4">{{ trans('messages.search_no_data') }}</p>
                    @endforelse

                    @if($tutorials->hasPages())
                        <div class="flex justify-center w-full mt-8 sm:mt-10">
                            {{ $tutorials->onEachSide(3)->links('repgui.partials.pagination.buttons') }}
                        </div>
                    @endif
                </div>
                @if ($ordpGuides && isset($externalGuides) && $externalGuides)
                    <div class="mt-10">
                        <h2>{{ trans('repgui.ordp.guides_title') }}</h2>
                        <div class="mt-8">
                            <div class="flex flex-wrap -mx-2 -mt-4">
                                @foreach ($ordpGuides as $ordpGuide)
                                    <div class="w-full px-2 mt-4 sm:w-1/2 md:w-1/3">
                                        <div class="relative flex flex-col h-full p-6 bg-light card rounded-card">
                                            <h3 class="flex items-start text-2xl font-semibold">
                                                <svg class="flex-shrink-0 mr-1 icon" aria-hidden="true">
                                                    <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
                                                </svg>
                                                {{ $ordpGuide['title'] }}
                                            </h3>
                                            <div class="mb-4">{{ $ordpGuide['description'] }}</div>
                                            <div class="flex flex-wrap justify-between mt-auto">
                                                <a href="{{ $ordpGuide['landingPage'] }}" target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex items-center link link--extended">
                                                    View guide
                                                    <svg class="ml-1 icon" aria-hidden="true">
                                                        <use xlink:href="/repgui/icon/sprite.svg#open-in-new"></use>
                                                    </svg>
                                                </a>
                                                <svg class="h-6" fill="#000" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 466.99 184.73" style="display: block;">
                                                    <defs>
                                                        <clipPath id="ifixit-header-logo-ua_svg__clip-path"
                                                                  transform="translate(35.91 27.05)">
                                                            <path
                                                                d="M65.32 0a65.32 65.32 0 1 0 65.32 65.31A65.31 65.31 0 0 0 65.32 0Zm18.2 68.07 12.92 17.05a4.81 4.81 0 0 1 .13 5.23l-.65 1a21.67 21.67 0 0 1-4.44 4.53l-1.56 1.09a4.67 4.67 0 0 1-5.19-.09l-17-12.93a4.52 4.52 0 0 0-5.14 0l-17 12.93a4.74 4.74 0 0 1-5.21.12l-1.13-.78a21.43 21.43 0 0 1-4.5-4.47l-1-1.39a4.74 4.74 0 0 1 .1-5.21l12.86-17.1a4.5 4.5 0 0 0 0-5.13L33.77 45.86a4.71 4.71 0 0 1-.09-5.2l1.1-1.56a21.38 21.38 0 0 1 4.52-4.44l1-.65a4.78 4.78 0 0 1 5.22.13l17 12.93a4.52 4.52 0 0 0 5.14 0l17-12.93a5.2 5.2 0 0 1 5.34-.3l1.78 1a14.07 14.07 0 0 1 4.34 4.45l.63 1.14a5.38 5.38 0 0 1-.38 5.38L83.52 62.92a4.53 4.53 0 0 0 0 5.15Z"
                                                                style="fill: none;"></path>
                                                        </clipPath>
                                                        <style>.ifixit-header-logo-ua_svg__cls-2 {
                                                                fill: #000
                                                            }</style>
                                                    </defs>
                                                    <g id="ifixit-header-logo-ua_svg__Layer_2" data-name="Layer 2">
                                                        <g id="ifixit-header-logo-ua_svg__Layer_1-2"
                                                           data-name="Layer 1">
                                                            <path class="ifixit-header-logo-ua_svg__cls-2"
                                                                  d="M161.82 41.14a6.89 6.89 0 1 1 13.78 0v49.91a6.89 6.89 0 1 1-13.78 0ZM191.25 41.68a6.83 6.83 0 0 1 6.89-6.89h35.06a6.26 6.26 0 1 1 0 12.52H205v13.33h24.15a6.26 6.26 0 0 1 0 12.52H205v17.89a6.89 6.89 0 0 1-13.78 0ZM250.56 41.14a6.89 6.89 0 1 1 13.77 0v49.91a6.89 6.89 0 1 1-13.77 0ZM353.7 41.14a6.89 6.89 0 1 1 13.77 0v49.91a6.89 6.89 0 1 1-13.77 0ZM397.71 47.49h-13.24a6.35 6.35 0 0 1 0-12.7h40.25a6.35 6.35 0 0 1 0 12.7h-13.24v43.56a6.89 6.89 0 1 1-13.77 0ZM318.94 66.47l19.62-19.62a7 7 0 1 0-9.93-9.93L309 56.54l-19.6-19.62a7 7 0 0 0-9.93 9.93l19.62 19.62-19.62 19.61A7 7 0 1 0 289.4 96L309 76.39 328.63 96a7 7 0 1 0 9.93-9.93Z"
                                                                  transform="translate(35.91 27.05)"></path>
                                                            <g style="clip-path: url(&quot;#ifixit-header-logo-ua_svg__clip-path&quot;);"
                                                               id="ifixit-header-logo-ua_svg__ifixit-logo-black-blue-horiz-RGB">
                                                                <path style="fill: rgb(0, 113, 206);"
                                                                      d="M35.91 92.36h149.78v92.37H0V92.66l35.91-.3z"></path>
                                                                <path style="fill: rgb(0, 113, 206);"
                                                                      d="M35.91 0h149.78v92.37H0V.3L35.91 0z"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-8">
                                @php
                                    if (!$target || $target === 'blank') {
                                        $url = LaravelLocalization::getLocalizedURL(app()->getLocale(), trans('tutorials/extern'));
                                        $url .= '?type=' . $ordpGuideString;
                                    } elseif ($target === 'self') {
                                        $url = route('api.tutorial.external');
                                        if ($params) {
                                            $url .= '?' . $params . '&type=' . $ordpGuideString;
                                        }
                                    }
                                @endphp
                                <a href="{{ $url }}" @if($target === 'blank') target="_blank" @elseif($target === 'self') target="_self" @else target="_top" @endif
                                   class="btn btn--secondary btn--ext">{{ __('repgui.external_guides_button') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <h2>{{ trans('repgui.interesting_links') }}</h2>
                        <div class="flex flex-wrap -mx-2 -mt-4">
                            <div class="w-full px-2 mt-4 sm:w-1/2 md:w-1/3">
                                <div class="relative flex flex-col h-full p-6 bg-light card rounded-card">
                                    <h3 class="flex items-start mb-6 text-2xl font-semibold">
                                        <svg class="flex-shrink-0 mr-1 icon" aria-hidden="true">
                                            <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
                                        </svg>
                                        Restarters Wiki
                                    </h3>
                                    {{-- <div class="mb-4">More guides on the Restarters Wiki</div> --}}
                                    <div class="flex flex-wrap justify-between mt-auto">
                                        <a href="https://wiki.restarters.net/index.php?search={{ $ordpSearch }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center link link--extended">
                                            View guides
                                            <svg class="ml-1 icon" aria-hidden="true">
                                                <use xlink:href="/repgui/icon/sprite.svg#open-in-new"></use>
                                            </svg>
                                        </a>
                                        <svg class="h-6" viewBox="0 0 180 49" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"><title>
                                                Restarters</title>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M170.529,11.058c-1.141,-0.743 -2.667,-0.42 -3.409,0.723c-0.744,1.14 -0.42,2.668 0.721,3.41c4.48,2.917 7.156,7.842 7.156,13.172c0,5.33 -2.673,10.25 -7.15,13.168c-0.002,0.002 -0.005,0.003 -0.006,0.004c-2.549,1.657 -5.507,2.534 -8.551,2.534c-3.049,0 -6.004,-0.877 -8.553,-2.534c-0.002,-0.001 -0.003,-0.001 -0.005,-0.002c-4.478,-2.919 -7.153,-7.84 -7.153,-13.17c0,-5.33 2.676,-10.255 7.158,-13.172c1.143,-0.742 1.465,-2.27 0.722,-3.41c-0.743,-1.143 -2.269,-1.465 -3.412,-0.723c-5.885,3.832 -9.398,10.298 -9.398,17.305c0,7.007 3.513,13.476 9.398,17.306c0.001,0 0.003,0.002 0.005,0.003c3.35,2.178 7.235,3.328 11.238,3.328c4.001,0 7.89,-1.151 11.239,-3.331c5.887,-3.831 9.4,-10.299 9.4,-17.306c0,-7.007 -3.513,-13.473 -9.4,-17.305Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M164.759,7.461c0.369,-1.259 -0.161,-4.292 -1.73,-6.15c-0.738,1.055 -1.153,2.407 -1.513,3.576c-0.627,0.229 -1.256,-0.049 -1.999,-0.287c-0.401,-0.128 -1.738,-0.424 -2.008,-0.856c-0.257,-0.407 0.247,-0.873 0.499,-1.357c0.174,-0.33 0.325,-0.81 0.469,-1.18c0.171,-0.438 0.241,-0.864 -0.022,-1.207c-1.588,0.117 -3.935,1.413 -4.793,3.169c-0.229,0.467 -0.351,1.376 -0.305,1.815c0.038,0.364 0.33,1.094 0.552,1.483c0.854,1.489 2.024,2.732 2.637,4.218c0.756,1.836 0.588,3.391 0.623,5.319c0.142,7.742 -0.021,10.223 -0.035,17.869c0,0.662 -0.048,1.452 0.109,1.773c0.894,1.843 3.54,2.025 4.438,-0.002c0.297,-0.671 0.094,-1.998 0.058,-2.948c-0.115,-3.038 -0.193,-5.976 -0.275,-8.838c-0.115,-3.994 -0.208,-3.151 -0.405,-6.89c-0.096,-1.849 -0.08,-3.907 0.949,-5.698c0.482,-0.84 2.258,-2.73 2.751,-3.809Z"></path>
                                                </g>
                                                <g>
                                                    <path
                                                        d="M9.008,25.245c-1.521,0 -2.572,0.29 -3.152,0.871l0,6.876c1.621,0.3 2.732,0.721 3.333,1.261l-0.301,2.312l-8.588,0l-0.3,-2.312c0.28,-0.38 0.831,-0.72 1.652,-1.021l0,-8.348c-0.701,-0.24 -1.252,-0.58 -1.652,-1.021l0.3,-2.312c1.101,-0.24 2.092,-0.36 2.973,-0.36c0.881,0 1.612,0.02 2.192,0.06l0,1.892c0.461,-0.541 1.051,-0.986 1.772,-1.336c0.72,-0.351 1.376,-0.526 1.967,-0.526c0.59,0 0.996,0.05 1.216,0.15l-0.09,3.904c-0.401,-0.06 -0.841,-0.09 -1.322,-0.09Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M18.858,36.806c-2.403,0 -4.209,-0.641 -5.42,-1.922c-1.211,-1.281 -1.817,-3.143 -1.817,-5.585c0,-1.482 0.21,-2.758 0.631,-3.829c0.42,-1.071 0.99,-1.897 1.711,-2.477c1.401,-1.121 3.033,-1.682 4.895,-1.682c1.862,0 3.288,0.476 4.279,1.426c0.991,0.951 1.486,2.658 1.486,5.12c0,1.442 -0.71,2.162 -2.132,2.162l-6.516,0c0.08,1.261 0.405,2.152 0.976,2.673c0.57,0.52 1.476,0.78 2.718,0.78c0.68,0 1.331,-0.08 1.951,-0.24c0.621,-0.16 1.071,-0.32 1.352,-0.48l0.42,-0.24l0.901,2.402c-0.12,0.14 -0.295,0.315 -0.526,0.525c-0.23,0.211 -0.81,0.491 -1.741,0.841c-0.931,0.35 -1.987,0.526 -3.168,0.526Zm1.892,-9.459c0.04,-0.281 0.06,-0.621 0.06,-1.021c0,-0.401 -0.161,-0.826 -0.481,-1.276c-0.32,-0.451 -0.876,-0.676 -1.666,-0.676c-0.791,0 -1.397,0.24 -1.817,0.721c-0.421,0.48 -0.701,1.291 -0.841,2.432l4.745,-0.18Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M32.16,24.014c-1.321,0 -1.982,0.39 -1.982,1.171c0,0.74 0.691,1.291 2.072,1.651l2.373,0.631c2.442,0.64 3.663,2.052 3.663,4.234c0,1.501 -0.525,2.727 -1.576,3.678c-1.051,0.951 -2.723,1.427 -5.015,1.427c-2.292,0 -4.079,-0.321 -5.36,-0.961c-0.02,-0.221 -0.03,-0.441 -0.03,-0.661c0,-1.101 0.22,-2.092 0.66,-2.973l2.493,0c0.26,0.481 0.43,0.981 0.51,1.502c0.401,0.12 0.971,0.18 1.712,0.18c1.761,0 2.642,-0.501 2.642,-1.502c0,-0.32 -0.12,-0.585 -0.36,-0.795c-0.24,-0.21 -0.711,-0.416 -1.411,-0.616l-2.373,-0.69c-1.221,-0.341 -2.172,-0.846 -2.852,-1.517c-0.681,-0.671 -1.021,-1.661 -1.021,-2.973c0,-1.311 0.53,-2.387 1.591,-3.228c1.061,-0.841 2.578,-1.261 4.55,-1.261c1.971,0 3.638,0.29 4.999,0.871c0.02,0.2 0.03,0.4 0.03,0.6c0,1.041 -0.22,2.002 -0.66,2.883l-2.463,0c-0.26,-0.46 -0.43,-0.941 -0.51,-1.441c-0.561,-0.14 -1.121,-0.21 -1.682,-0.21Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M45.163,17.768l0,3.783l4.294,0l0,2.523l-4.294,0l0,7.597c0,0.64 0.09,1.106 0.27,1.396c0.18,0.29 0.61,0.436 1.291,0.436c0.681,0 1.411,-0.151 2.192,-0.451l0.901,2.342c-1.542,0.781 -3.118,1.171 -4.73,1.171c-1.611,0 -2.702,-0.345 -3.273,-1.036c-0.57,-0.69 -0.855,-1.696 -0.855,-3.017l0,-8.438l-1.352,0l-0.33,-1.832c0.32,-0.38 0.981,-0.761 1.982,-1.141c0.24,-1.642 0.811,-2.753 1.711,-3.333l2.193,0Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M51.468,23.773c0,-0.3 0.01,-0.62 0.03,-0.96c1.862,-1.001 3.864,-1.502 6.006,-1.502c2.142,0 3.679,0.405 4.609,1.216c0.931,0.811 1.397,2.157 1.397,4.039l0,6.396c0.7,0.14 1.251,0.31 1.651,0.51l0,2.613c-1.021,0.48 -2.482,0.721 -4.384,0.721c-0.2,-0.521 -0.37,-1.151 -0.51,-1.892c-0.861,1.261 -2.423,1.892 -4.685,1.892c-1.341,0 -2.487,-0.391 -3.438,-1.171c-0.951,-0.781 -1.426,-1.842 -1.426,-3.183c0,-1.342 0.445,-2.398 1.336,-3.168c0.891,-0.771 2.217,-1.157 3.979,-1.157l3.273,0l0,-1.441c0,-1.561 -0.831,-2.342 -2.493,-2.342c-0.62,0 -1.131,0.06 -1.531,0.18c-0.04,0.841 -0.16,1.492 -0.36,1.952l-2.943,0c-0.34,-0.701 -0.511,-1.602 -0.511,-2.703Zm5.406,9.97c1.021,0 1.831,-0.301 2.432,-0.901l0,-2.462l-2.102,0c-1.441,0 -2.162,0.55 -2.162,1.651c0,0.501 0.155,0.911 0.465,1.231c0.311,0.321 0.766,0.481 1.367,0.481Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M75.251,25.245c-1.522,0 -2.573,0.29 -3.153,0.871l0,6.876c1.621,0.3 2.732,0.721 3.333,1.261l-0.3,2.312l-8.588,0l-0.301,-2.312c0.281,-0.38 0.831,-0.72 1.652,-1.021l0,-8.348c-0.701,-0.24 -1.251,-0.58 -1.652,-1.021l0.301,-2.312c1.101,-0.24 2.092,-0.36 2.972,-0.36c0.881,0 1.612,0.02 2.193,0.06l0,1.892c0.46,-0.541 1.051,-0.986 1.771,-1.336c0.721,-0.351 1.376,-0.526 1.967,-0.526c0.591,0 0.996,0.05 1.216,0.15l-0.09,3.904c-0.4,-0.06 -0.841,-0.09 -1.321,-0.09Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M83.358,17.768l0,3.783l4.295,0l0,2.523l-4.295,0l0,7.597c0,0.64 0.091,1.106 0.271,1.396c0.18,0.29 0.61,0.436 1.291,0.436c0.681,0 1.411,-0.151 2.192,-0.451l0.901,2.342c-1.542,0.781 -3.118,1.171 -4.73,1.171c-1.611,0 -2.702,-0.345 -3.273,-1.036c-0.57,-0.69 -0.855,-1.696 -0.855,-3.017l0,-8.438l-1.352,0l-0.33,-1.832c0.32,-0.38 0.981,-0.761 1.982,-1.141c0.24,-1.642 0.811,-2.753 1.711,-3.333l2.192,0Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M96.271,36.806c-2.403,0 -4.209,-0.641 -5.42,-1.922c-1.212,-1.281 -1.817,-3.143 -1.817,-5.585c0,-1.482 0.21,-2.758 0.63,-3.829c0.421,-1.071 0.991,-1.897 1.712,-2.477c1.401,-1.121 3.033,-1.682 4.895,-1.682c1.861,0 3.288,0.476 4.279,1.426c0.991,0.951 1.486,2.658 1.486,5.12c0,1.442 -0.711,2.162 -2.132,2.162l-6.516,0c0.08,1.261 0.405,2.152 0.976,2.673c0.57,0.52 1.476,0.78 2.717,0.78c0.681,0 1.332,-0.08 1.952,-0.24c0.621,-0.16 1.071,-0.32 1.352,-0.48l0.42,-0.24l0.901,2.402c-0.12,0.14 -0.296,0.315 -0.526,0.525c-0.23,0.211 -0.81,0.491 -1.741,0.841c-0.931,0.35 -1.987,0.526 -3.168,0.526Zm1.891,-9.459c0.04,-0.281 0.06,-0.621 0.06,-1.021c0,-0.401 -0.16,-0.826 -0.48,-1.276c-0.32,-0.451 -0.876,-0.676 -1.667,-0.676c-0.79,0 -1.396,0.24 -1.816,0.721c-0.421,0.48 -0.701,1.291 -0.841,2.432l4.744,-0.18Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M112.576,25.245c-1.521,0 -2.572,0.29 -3.153,0.871l0,6.876c1.622,0.3 2.733,0.721 3.333,1.261l-0.3,2.312l-8.588,0l-0.3,-2.312c0.28,-0.38 0.83,-0.72 1.651,-1.021l0,-8.348c-0.701,-0.24 -1.251,-0.58 -1.651,-1.021l0.3,-2.312c1.101,-0.24 2.092,-0.36 2.973,-0.36c0.88,0 1.611,0.02 2.192,0.06l0,1.892c0.46,-0.541 1.051,-0.986 1.771,-1.336c0.721,-0.351 1.377,-0.526 1.967,-0.526c0.591,0 0.996,0.05 1.216,0.15l-0.09,3.904c-0.4,-0.06 -0.841,-0.09 -1.321,-0.09Z"
                                                        style="fill-rule:nonzero;"></path>
                                                    <path
                                                        d="M121.224,24.014c-1.321,0 -1.982,0.39 -1.982,1.171c0,0.74 0.691,1.291 2.072,1.651l2.372,0.631c2.443,0.64 3.664,2.052 3.664,4.234c0,1.501 -0.526,2.727 -1.577,3.678c-1.051,0.951 -2.722,1.427 -5.014,1.427c-2.292,0 -4.079,-0.321 -5.36,-0.961c-0.02,-0.221 -0.03,-0.441 -0.03,-0.661c0,-1.101 0.22,-2.092 0.66,-2.973l2.493,0c0.26,0.481 0.43,0.981 0.51,1.502c0.4,0.12 0.971,0.18 1.712,0.18c1.761,0 2.642,-0.501 2.642,-1.502c0,-0.32 -0.12,-0.585 -0.36,-0.795c-0.24,-0.21 -0.711,-0.416 -1.411,-0.616l-2.373,-0.69c-1.221,-0.341 -2.172,-0.846 -2.852,-1.517c-0.681,-0.671 -1.021,-1.661 -1.021,-2.973c0,-1.311 0.53,-2.387 1.591,-3.228c1.061,-0.841 2.578,-1.261 4.549,-1.261c1.972,0 3.639,0.29 5,0.871c0.02,0.2 0.03,0.4 0.03,0.6c0,1.041 -0.22,2.002 -0.66,2.883l-2.463,0c-0.26,-0.46 -0.43,-0.941 -0.51,-1.441c-0.561,-0.14 -1.121,-0.21 -1.682,-0.21Z"
                                                        style="fill-rule:nonzero;"></path>
                                                </g>
                                            </g></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full px-2 mt-4 sm:w-1/2 md:w-1/3">
                                <div class="relative flex flex-col h-full p-6 bg-light card rounded-card">
                                    <h3 class="flex items-start mb-6 text-2xl font-semibold">
                                        <svg class="flex-shrink-0 mr-1 icon" aria-hidden="true">
                                            <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
                                        </svg>
                                        {{ __('repgui.ifixit') }}
                                    </h3>
                                    {{-- <div class="mb-4">More guides on the Restarters Wiki</div> --}}
                                    <div class="flex flex-wrap justify-between mt-auto">
                                        <a href="https://www.ifixit.com/Search?doctype=wiki&query={{ $ordpSearch }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center link link--extended">
                                            View guides
                                            <svg class="ml-1 icon" aria-hidden="true">
                                                <use xlink:href="/repgui/icon/sprite.svg#open-in-new"></use>
                                            </svg>
                                        </a>
                                        <svg class="h-6" fill="#000" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 466.99 184.73" style="display: block;">
                                            <defs>
                                                <clipPath id="ifixit-header-logo-ua_svg__clip-path"
                                                          transform="translate(35.91 27.05)">
                                                    <path
                                                        d="M65.32 0a65.32 65.32 0 1 0 65.32 65.31A65.31 65.31 0 0 0 65.32 0Zm18.2 68.07 12.92 17.05a4.81 4.81 0 0 1 .13 5.23l-.65 1a21.67 21.67 0 0 1-4.44 4.53l-1.56 1.09a4.67 4.67 0 0 1-5.19-.09l-17-12.93a4.52 4.52 0 0 0-5.14 0l-17 12.93a4.74 4.74 0 0 1-5.21.12l-1.13-.78a21.43 21.43 0 0 1-4.5-4.47l-1-1.39a4.74 4.74 0 0 1 .1-5.21l12.86-17.1a4.5 4.5 0 0 0 0-5.13L33.77 45.86a4.71 4.71 0 0 1-.09-5.2l1.1-1.56a21.38 21.38 0 0 1 4.52-4.44l1-.65a4.78 4.78 0 0 1 5.22.13l17 12.93a4.52 4.52 0 0 0 5.14 0l17-12.93a5.2 5.2 0 0 1 5.34-.3l1.78 1a14.07 14.07 0 0 1 4.34 4.45l.63 1.14a5.38 5.38 0 0 1-.38 5.38L83.52 62.92a4.53 4.53 0 0 0 0 5.15Z"
                                                        style="fill: none;"></path>
                                                </clipPath>
                                                <style>.ifixit-header-logo-ua_svg__cls-2 {
                                                        fill: #000
                                                    }</style>
                                            </defs>
                                            <g id="ifixit-header-logo-ua_svg__Layer_2" data-name="Layer 2">
                                                <g id="ifixit-header-logo-ua_svg__Layer_1-2" data-name="Layer 1">
                                                    <path class="ifixit-header-logo-ua_svg__cls-2"
                                                          d="M161.82 41.14a6.89 6.89 0 1 1 13.78 0v49.91a6.89 6.89 0 1 1-13.78 0ZM191.25 41.68a6.83 6.83 0 0 1 6.89-6.89h35.06a6.26 6.26 0 1 1 0 12.52H205v13.33h24.15a6.26 6.26 0 0 1 0 12.52H205v17.89a6.89 6.89 0 0 1-13.78 0ZM250.56 41.14a6.89 6.89 0 1 1 13.77 0v49.91a6.89 6.89 0 1 1-13.77 0ZM353.7 41.14a6.89 6.89 0 1 1 13.77 0v49.91a6.89 6.89 0 1 1-13.77 0ZM397.71 47.49h-13.24a6.35 6.35 0 0 1 0-12.7h40.25a6.35 6.35 0 0 1 0 12.7h-13.24v43.56a6.89 6.89 0 1 1-13.77 0ZM318.94 66.47l19.62-19.62a7 7 0 1 0-9.93-9.93L309 56.54l-19.6-19.62a7 7 0 0 0-9.93 9.93l19.62 19.62-19.62 19.61A7 7 0 1 0 289.4 96L309 76.39 328.63 96a7 7 0 1 0 9.93-9.93Z"
                                                          transform="translate(35.91 27.05)"></path>
                                                    <g style="clip-path: url(&quot;#ifixit-header-logo-ua_svg__clip-path&quot;);"
                                                       id="ifixit-header-logo-ua_svg__ifixit-logo-black-blue-horiz-RGB">
                                                        <path style="fill: rgb(0, 113, 206);"
                                                              d="M35.91 92.36h149.78v92.37H0V92.66l35.91-.3z"></path>
                                                        <path style="fill: rgb(0, 113, 206);"
                                                              d="M35.91 0h149.78v92.37H0V.3L35.91 0z"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
