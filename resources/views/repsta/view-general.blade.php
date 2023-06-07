@extends('repsta.layouts.base')

@section('content_body')
    @php
        $maxValue = max([
            $data['devices']['end_of_life'],
            $data['devices']['archived'],
            $data['devices']['to_repair'],
            $data['devices']['repaired'],
            ]);
    @endphp
    <div class="container px-0 max-w-none">
        <div class="flex flex-wrap -mx-3 -mt-6">
            <div class="w-full px-3 mt-6 xl:w-1/3">
                <div class="flex flex-col h-full xs:flex-row xl:flex-col" style="gap: 1.5rem;">
                    <div class="flex items-center justify-center flex-grow w-full p-4 text-center bg-white xs:w-1/2 md:w-full rounded-card sm:p-6">
                        <h3 class="text-black">
                            <span class="block text-6xl font-black">{{ $data['repairers'] }}</span>
                            <span class="font-bold">{{ __('messages.active_repairers') }}</span>
                        </h3>
                    </div>
                    <div class="flex flex-wrap items-center justify-center flex-grow w-full p-4 text-center bg-white xs:w-1/2 md:w-full sm:p-6 rounded-card">
                        <h3 class="mb-3 md:mb-1">
                            <span class="block text-6xl font-black text-tertiary">{{ $data['events'] }}</span>
                            <span class="font-bold text-black"> {{ __('messages.organized_events') }}</span>
                        </h3>
                        {{-- <div class="w-full">
                          <a href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->getLocale(), 'routes.location_show', ['organisation' => $data['organisation']]) }}" class="btn btn--tertiary btn--ext" target="_blank">{{ __('messages.overview_link') }}</a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="w-full px-3 mt-6 lg:w-1/2 xl:w-1/3">
                <div class="flex flex-col justify-between h-full p-4 text-center bg-white sm:p-6 rounded-card">
                    <h3>
                        <span class="block text-6xl font-black">{{ $data['devices']['registered'] }}</span>
                        <span class="font-bold text-black">{{ __('messages.devices_registered') }}</span>
                    </h3>
                    <div>
                        <div class="flex flex-wrap -mx-2">
                            <div class="w-1/2 px-2 mt-6 sm:w-1/4">
                                <div class="relative w-full h-24">
                                    <div class="w-32 mx-auto -rotate-90">
                                        @php
                                            $width = 0;
                                            if ($maxValue) {
                                                $width = $data['devices']['repaired'] / $maxValue * 100;
                                            }
                                        @endphp
                                        <div class="h-8 border border-secondary-hover" aria-hidden="true" style="width: {{$width }}%; background-color: rgba(156, 121, 151, {{$width}}%)"></div>
                                        <div class="text-left">
                                            <span class="text-4xl font-black tracking-wider">{{ $data['devices']['repaired'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5">
                  <span class="inline-block text-sm leading-none">
                    {{ __('messages.devices_repaired') }}
                  </span>
                                </div>
                            </div>
                            <div class="w-1/2 px-2 mt-6 sm:w-1/4">
                                <div class="relative w-full h-24">
                                    <div class="w-32 mx-auto -rotate-90">
                                        @php
                                            $width = 0;
                                            if ($maxValue) {
                                                $width = $data['devices']['end_of_life'] / $maxValue * 100;
                                            }
                                        @endphp
                                        <div class="h-6 border border-secondary-hover" aria-hidden="true" style="width: {{$width }}%; background-color: rgba(156, 121, 151, {{$width}}%)"></div>
                                        <div class="text-left">
                                            <span class="text-4xl font-black tracking-wider">{{ $data['devices']['end_of_life'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5">
                  <span class="inline-block text-sm leading-none">
                    {{ __('messages.devices_end_of_life') }}
                  </span>
                                </div>
                            </div>
                            <div class="w-1/2 px-2 mt-6 sm:w-1/4">
                                <div class="relative w-full h-24">
                                    <div class="w-32 mx-auto -rotate-90">
                                        @php
                                            $width = 0;
                                            if ($maxValue) {
                                              $width = $data['devices']['archived'] / $maxValue * 100;
                                            }
                                        @endphp
                                        <div class="h-6 border border-secondary-hover" aria-hidden="true" style="width: {{$width }}%; background-color: rgba(156, 121, 151, {{$width}}%)"></div>
                                        <div class="text-left">
                                            <span class="text-4xl font-black tracking-wider">{{ $data['devices']['archived'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5">
                  <span class="inline-block text-sm leading-none">
                    {{ __('messages.devices_archived') }}
                  </span>
                                </div>
                            </div>
                            <div class="w-1/2 px-2 mt-6 sm:w-1/4">
                                <div class="relative w-full h-24">
                                    <div class="w-32 mx-auto -rotate-90">
                                        @php
                                            $width = 0;
                                            if ($maxValue) {
                                              $width = $data['devices']['to_repair'] / $maxValue * 100;

                                            }
                                        @endphp
                                        <div class="h-6 border border-secondary-hover" aria-hidden="true" style="width: {{$width }}%; background-color: rgba(156, 121, 151, {{$width}}%)"></div>
                                        <div class="text-left">
                                            <span class="text-4xl font-black tracking-wider">{{ $data['devices']['to_repair'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5">
                  <span class="inline-block text-sm leading-none">
                    {{ __('messages.devices_to_repair') }}
                  </span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full pt-6 mt-6 border-t border-gray-400">
                            <a href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->getLocale(), 'routes.repairer_register_index') }}" class="text-sm underline hover:no-underline" target="_blank">{{ __('messages.become_repairer_link') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full px-3 mt-6 lg:w-1/2 xl:w-1/3">
                <div class="relative h-full p-4 overflow-hidden bg-primary sm:p-6 rounded-card">
                    <div class="mb-32">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="mb-0 text-base font-bold tracking-widest text-black uppercase">
                                {{ __('messages.eco_impact') }}
                            </h3>
                            <div class="relative">
                                <button class="relative text-white transition-colors duration-300 ease-out hover:text-black" data-s-tooltip>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="sr-only">
                                        {{ __('messages.impact_info') }}
                                    </div>
                                </button>
                                <div class="absolute right-0 z-10 hidden p-4 text-sm text-left text-black bg-white shadow sm:p-6 w-72 rounded-card" style="width: 280px;" data-s-tooltip-target>
                                    {!! __('messages.impact_info_text') !!}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-end">
                <span class="text-6xl font-black leading-none text-white">
                  {{ number_format($data['total_weight'], 0, ',', '.') }}
                </span>
                                <span class="ml-1 text-xl font-bold leading-snug text-white">kg</span>
                            </div>
                            <span class="block w-full mt-2 text-2xl font-bold text-black">
                {{ __('messages.prevented_waste') }}
              </span>
                        </div>
                        <div class="my-6 border-t border-primary-700" aria-hidden="true"></div>
                        <div>
                            <div class="flex flex-wrap items-end">
                <span class="text-6xl font-black leading-none text-white">
                  {{ number_format($data['total_co2'], 0, ',', '.') }}
                </span>
                                <span class="ml-1 text-xl font-bold leading-snug text-white">kg</span>
                            </div>
                            <span class="block w-full mt-2 text-2xl font-bold text-black">
                {!! __('messages.prevented_co2') !!}
              </span>
                        </div>
                        <div class="absolute bottom-0 right-0">
                            <img data-src="/repgui/img/impact-visual.png" class="lazyload" style="max-width: 275px; mix-blend-mode: luminosity;" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($data['device_type_ranking'][1], $data['device_type_ranking'][2], $data['device_type_ranking'][3]))
            <div class="mt-6">
                <div class="p-4 pb-16 text-center bg-white rounded-card sm:p-6 sm:pb-16">
                    <h3 class="text-base font-bold tracking-widest text-black uppercase">{{ __('messages.most_repaired_devices') }}</h3>
                    <div class="max-w-xl mx-auto">
                        <div class="flex flex-wrap justify-center -mx-3 -mt-12">
                            <div class="order-1 w-full px-3 mt-6 sm:order-2 sm:w-1/3">
                                <div class="flex items-end justify-center h-full">
                                    <svg width="184px" height="129px" viewBox="0 0 184 129" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>Rank 1</title>
                                        <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Repair-Connects-→-Organisatie-(publiek)" transform="translate(-634.000000, -1047.000000)" stroke="#333333" stroke-width="0.5">
                                                <g id="Group-15" transform="translate(173.000000, 493.000000)">
                                                    <g id="Group-21" transform="translate(0.000000, 483.000000)">
                                                        <g id="Group-17" transform="translate(260.000000, 71.000000)">
                                                            <g id="Group-18" transform="translate(201.500000, 0.000000)">
                                                                <rect id="Rectangle" fill="#FFFFFF" x="0.25" y="13.25" width="155.5" height="115.5"></rect>
                                                                <path d="M155.75,13.25 L171.75,29.1035534 L171.75,128.75 L155.75,128.75 L155.75,13.25 Z" id="Path-2" fill="#71B8C5"></path>
                                                                <text id="18" font-family="TitilliumWeb-Black, Titillium Web" font-size="36" font-weight="800" line-spacing="20" fill="#333333">
                                                                    <tspan text-anchor="middle" x="75" y="53">{{ $data['device_type_ranking'][1]['total'] }}</tspan>
                                                                </text>
                                                                <g id="Group-13" transform="translate(127.500000, 0.000000)">
                                                                    <polygon id="Path" fill="#333333" points="15 52 15 75 28 62 41 75 41 52"></polygon>
                                                                    <circle id="Oval" fill="#FF9623" cx="28" cy="28" r="27.75"></circle>
                                                                    <circle id="Oval" cx="28" cy="28" r="23.75"></circle>
                                                                    <g id="1" transform="translate(15.000000, 14.880000)" fill="#FFFFFF">
                                                                        <path d="M13,0.564887865 L16.6545878,7.96989148 L24.8264944,9.15733902 L18.9132472,14.9213305 L20.3091755,23.060217 L13,19.2175561 L5.69082448,23.060217 L7.08675279,14.9213305 L1.17350557,9.15733902 L9.34541224,7.96989148 L13,0.564887865 Z" id="Star"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="mt-4 text-base">
                                    {{ $data['device_type_ranking'][1]['name'] }}
                                </div>
                            </div>
                            <div class="order-2 w-full px-3 mt-6 sm:order-1 sm:w-1/3">
                                <div class="flex items-end justify-center h-full">
                                    <svg width="172px" height="104px" viewBox="0 0 172 104" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>Rank 2</title>
                                        <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Repair-Connects-→-Organisatie-(publiek)" transform="translate(-433.000000, -1072.000000)">
                                                <g id="Group-15" transform="translate(173.000000, 493.000000)">
                                                    <g id="Group-21" transform="translate(0.000000, 483.000000)">
                                                        <g id="Group-17" transform="translate(260.000000, 71.000000)">
                                                            <g id="Group-19" transform="translate(0.000000, 25.000000)">
                                                                <rect id="Rectangle" fill="#DCDDDE" x="0" y="0" width="156" height="104"></rect>
                                                                <text id="18" font-family="TitilliumWeb-Black, Titillium Web" font-size="36" font-weight="800" line-spacing="20" fill="#333333">
                                                                    <tspan text-anchor="middle" x="75" y="40">{{ $data['device_type_ranking'][2]['total'] }}</tspan>
                                                                </text>
                                                                <g id="Group-22" transform="translate(155.500000, 0.000000)" stroke="#333333" stroke-width="0.5">
                                                                    <path d="M0.25,0.25 L16.25,16.1035534 L16.25,103.75 L0.25,103.75 L0.25,0.25 Z" id="Path-2"></path>
                                                                    <line x1="8.5" y1="104" x2="8.5" y2="8.25" id="Path-3"></line>
                                                                    <line x1="12.5" y1="104" x2="12.5" y2="12.25" id="Path-3"></line>
                                                                    <line x1="4.5" y1="104" x2="4.5" y2="4.25" id="Path-3"></line>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="mt-4 text-base">
                                    {{ $data['device_type_ranking'][2]['name'] }}
                                </div>
                            </div>
                            <div class="order-3 w-full px-3 mt-6 sm:order-3 sm:w-1/3">
                                <div class="flex items-end justify-center h-full">
                                    <svg width="172px" height="92px" viewBox="0 0 172 92" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>Rank 3</title>
                                        <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Repair-Connects-→-Organisatie-(publiek)" transform="translate(-836.000000, -1084.000000)">
                                                <g id="Group-15" transform="translate(173.000000, 493.000000)">
                                                    <g id="Group-21" transform="translate(0.000000, 483.000000)">
                                                        <g id="Group-17" transform="translate(260.000000, 71.000000)">
                                                            <g id="Group-20" transform="translate(403.000000, 37.000000)">
                                                                <rect id="Rectangle" stroke="#333333" stroke-width="0.5" fill="#DBBAB4" x="0.25" y="0.25" width="155.5" height="91.5"></rect>
                                                                <text id="18" font-family="TitilliumWeb-Black, Titillium Web" font-size="36" font-weight="800" line-spacing="20" fill="#333333">
                                                                    <tspan text-anchor="middle" x="75" y="40">{{ $data['device_type_ranking'][3]['total'] }}</tspan>
                                                                </text>
                                                                <polygon id="Path-2" fill="#000000" points="172 92 156 92 155.5 92 155.5 1.91513472e-15 156 1.91513472e-15 172 16"></polygon>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="mt-4 text-base">
                                    {{ $data['device_type_ranking'][3]['name'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
