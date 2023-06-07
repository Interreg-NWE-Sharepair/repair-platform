@extends('repsta.layouts.base')

@section('content_body')
    @php

        $maxValue = 0;
        if ($data['devices']) {
            $maxValue = max([
                $data['devices']['end_of_life']['absolute'],
                $data['devices']['repaired']['absolute'],
                $data['devices']['advice']['absolute'],
                $data['devices']['spare_parts']['absolute'],
                $data['devices']['resign_repair']['absolute'],
                ]);
            $total = $data['devices']['end_of_life']['absolute'] +
                $data['devices']['repaired']['absolute'] +
                $data['devices']['advice']['absolute'] +
                $data['devices']['spare_parts']['absolute'] +
                $data['devices']['resign_repair']['absolute'];
        }
    @endphp

    <div class="container px-0 max-w-none">
        <div class="flex flex-wrap -mx-3 -mt-6">
            <div class="w-full px-3 mt-6 lg:w-1/3">
                <div class="flex flex-col h-full" style="gap: 1.5rem;">
                    <div
                        class="flex items-center justify-center flex-grow w-full p-4 text-center bg-white rounded-card sm:p-6">
                        <h3 class="text-black">
                            <span class="block text-6xl font-black text-tertiary">{{ $data['repairers'] }}</span>
                            <span class="font-bold">{{ __('messages.active_repairers_events') }}</span>
                        </h3>
                    </div>
                    @if (isset($data['devices']['registered']))
                        <div
                            class="flex items-center justify-center flex-grow w-full p-4 text-center bg-white rounded-card sm:p-6">
                            <h3 class="text-black">
                                <span class="block text-6xl font-black text-secondary">{{ $data['devices']['registered']['absolute'] }}</span>
                                <span class="font-bold">{{ __('messages.devices_registered') }}</span>
                            </h3>
                        </div>
                    @endif
                    <div
                        class="relative flex flex-wrap items-center justify-center flex-grow w-full p-4 overflow-hidden bg-primary sm:p-6 rounded-card">
                        <div class="w-full mb-32">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="mb-0 text-base font-bold tracking-widest text-black uppercase">
                                    {{ __('messages.eco_impact') }}
                                </h3>
                                <div class="relative">
                                    <button
                                        class="relative text-white transition-colors duration-300 ease-out hover:text-black"
                                        data-s-tooltip>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="currentColor"
                                             class="w-6 h-6" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <div class="sr-only">
                                            {{ __('messages.impact_info') }}
                                        </div>
                                    </button>
                                    <div
                                        class="absolute right-0 z-10 hidden p-4 text-sm text-left text-black bg-white shadow sm:p-6 w-72 rounded-card"
                                        style="width: 280px;" data-s-tooltip-target>
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
                                <img data-src="/repgui/img/impact-visual.png" class="lazyload"
                                     style="max-width: 275px; mix-blend-mode: luminosity;" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full px-3 mt-6 lg:w-2/3">
                <div class="flex flex-col h-full" style="gap: 1.5rem;">
                    @if ($data['devices'])
                        <div class="flex flex-col justify-between p-6 text-center bg-white sm:p-8 rounded-card">
                            {{-- Circle --}}
                            <div class="relative block">
                                <div class="flex flex-wrap -mx-4">
                                    <div class="w-full px-4 sm:w-1/2 md:w-1/3">
                                        <div class="mx-auto donut">
                                            <div class="hole"></div>
                                        </div>
                                    </div>
                                    <div class="w-full px-4 mt-8 sm:mt-0 sm:w-1/2 md:w-2/3">
                                        <div class="flex flex-col justify-center h-full space-y-4 text-left">
                                            <h3 class="text-center text text-h3">
                                                <span class="block text-6xl text-primary">{{ $total }}</span>
                                                <span class="block w-full mt-2 text-2xl font-bold text-black">
                                                    {{ __('messages.total_devices')}}
                                                </span>
                                            </h3>
                                            <div class="flex flex-col items-center">
                                                <div class="space-y-3 sm:space-y-2">
                                                    <div class="flex flex-wrap items-center text-base">
                                                        <div class="w-4 h-4 mr-1 rounded-full" style="background-color: #9C7A97;"></div><span class="inline-block w-8 font-bold text-center">{{ $data['devices']['repaired']['absolute'] }}</span> {{ __('messages.devices_repaired_event', ['relative' => $data['devices']['repaired']['relative']]) }}
                                                    </div>
                                                    <div class="flex flex-wrap items-center text-base">
                                                        <div class="w-4 h-4 mr-1 rounded-full" style="background-color: #2ECC71;"></div><span class="inline-block w-8 font-bold text-center">{{ $data['devices']['advice']['absolute'] }}</span> {{ __('messages.devices_advice_event', ['relative' => $data['devices']['advice']['relative']]) }}
                                                    </div>
                                                    <div class="flex flex-wrap items-center text-base">
                                                        <div class="w-4 h-4 mr-1 rounded-full" style="background-color: #F2CA27;"></div><span class="inline-block w-8 font-bold text-center">{{ $data['devices']['spare_parts']['absolute'] }}</span> {{ __('messages.devices_spare_parts_event', ['relative' => $data['devices']['spare_parts']['relative']]) }}
                                                    </div>
                                                    <div class="flex flex-wrap items-center text-base">
                                                        <div class="w-4 h-4 mr-1 rounded-full" style="background-color: #F57C7C;"></div><span class="inline-block w-8 font-bold text-center">{{ $data['devices']['resign_repair']['absolute'] }}</span> {{ __('messages.devices_resign_repair_event', ['relative' => $data['devices']['resign_repair']['relative']]) }}
                                                    </div>
                                                    <div class="flex flex-wrap items-center text-base">
                                                        <div class="w-4 h-4 mr-1 rounded-full" style="background-color: #71b8c5;"></div><span class="inline-block w-8 font-bold text-center">{{ $data['devices']['end_of_life']['absolute'] }}</span> {{ __('messages.devices_end_of_life_event', ['relative' => $data['devices']['end_of_life']['relative']]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full pt-6 mt-6 border-t border-gray-400">
                                <a href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->getLocale(), 'routes.repairer_register_index', ['locationCode' => $data['event']->organisation->slug]) }}"
                                class="text-sm underline hover:no-underline"
                                target="_blank">{{ __('messages.become_repairer_link') }}</a>

                            </div>
                        </div>
                    @endif
                    @if (isset($data['device_type_ranking'][1], $data['device_type_ranking'][2], $data['device_type_ranking'][3]))
                        <div>
                            <div class="p-4 pb-16 text-center bg-white rounded-card sm:p-6 sm:pb-16">
                                <h3 class="text-base font-bold tracking-widest text-black uppercase">{{ __('messages.most_repaired_devices') }}</h3>
                                <div class="max-w-xl mx-auto">
                                    <div class="flex flex-wrap justify-center -mx-3 -mt-12">
                                        <div class="order-1 w-full px-3 mt-6 sm:order-2 sm:w-1/3">
                                            <div class="flex items-end justify-center h-full">
                                                <svg width="184px" height="129px" viewBox="0 0 184 129"
                                                     version="1.1"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>Rank 1</title>
                                                    <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none"
                                                       fill-rule="evenodd">
                                                        <g id="Repair-Connects-→-Organisatie-(publiek)"
                                                           transform="translate(-634.000000, -1047.000000)"
                                                           stroke="#333333"
                                                           stroke-width="0.5">
                                                            <g id="Group-15"
                                                               transform="translate(173.000000, 493.000000)">
                                                                <g id="Group-21"
                                                                   transform="translate(0.000000, 483.000000)">
                                                                    <g id="Group-17"
                                                                       transform="translate(260.000000, 71.000000)">
                                                                        <g id="Group-18"
                                                                           transform="translate(201.500000, 0.000000)">
                                                                            <rect id="Rectangle" fill="#FFFFFF"
                                                                                  x="0.25" y="13.25"
                                                                                  width="155.5"
                                                                                  height="115.5"></rect>
                                                                            <path
                                                                                d="M155.75,13.25 L171.75,29.1035534 L171.75,128.75 L155.75,128.75 L155.75,13.25 Z"
                                                                                id="Path-2" fill="#71B8C5"></path>
                                                                            <text id="18"
                                                                                  font-family="TitilliumWeb-Black, Titillium Web"
                                                                                  font-size="36" font-weight="800"
                                                                                  line-spacing="20"
                                                                                  fill="#333333">
                                                                                <tspan text-anchor="middle" x="75"
                                                                                       y="53">{{ $data['device_type_ranking'][1]['total'] }}</tspan>
                                                                            </text>
                                                                            <g id="Group-13"
                                                                               transform="translate(127.500000, 0.000000)">
                                                                                <polygon id="Path" fill="#333333"
                                                                                         points="15 52 15 75 28 62 41 75 41 52"></polygon>
                                                                                <circle id="Oval" fill="#FF9623"
                                                                                        cx="28" cy="28"
                                                                                        r="27.75"></circle>
                                                                                <circle id="Oval" cx="28" cy="28"
                                                                                        r="23.75"></circle>
                                                                                <g id="1"
                                                                                   transform="translate(15.000000, 14.880000)"
                                                                                   fill="#FFFFFF">
                                                                                    <path
                                                                                        d="M13,0.564887865 L16.6545878,7.96989148 L24.8264944,9.15733902 L18.9132472,14.9213305 L20.3091755,23.060217 L13,19.2175561 L5.69082448,23.060217 L7.08675279,14.9213305 L1.17350557,9.15733902 L9.34541224,7.96989148 L13,0.564887865 Z"
                                                                                        id="Star"></path>
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
                                                <svg width="172px" height="104px" viewBox="0 0 172 104"
                                                     version="1.1"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>Rank 2</title>
                                                    <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none"
                                                       fill-rule="evenodd">
                                                        <g id="Repair-Connects-→-Organisatie-(publiek)"
                                                           transform="translate(-433.000000, -1072.000000)">
                                                            <g id="Group-15"
                                                               transform="translate(173.000000, 493.000000)">
                                                                <g id="Group-21"
                                                                   transform="translate(0.000000, 483.000000)">
                                                                    <g id="Group-17"
                                                                       transform="translate(260.000000, 71.000000)">
                                                                        <g id="Group-19"
                                                                           transform="translate(0.000000, 25.000000)">
                                                                            <rect id="Rectangle" fill="#DCDDDE"
                                                                                  x="0" y="0"
                                                                                  width="156" height="104"></rect>
                                                                            <text id="18"
                                                                                  font-family="TitilliumWeb-Black, Titillium Web"
                                                                                  font-size="36" font-weight="800"
                                                                                  line-spacing="20"
                                                                                  fill="#333333">
                                                                                <tspan text-anchor="middle" x="75"
                                                                                       y="40">{{ $data['device_type_ranking'][2]['total'] }}</tspan>
                                                                            </text>
                                                                            <g id="Group-22"
                                                                               transform="translate(155.500000, 0.000000)"
                                                                               stroke="#333333" stroke-width="0.5">
                                                                                <path
                                                                                    d="M0.25,0.25 L16.25,16.1035534 L16.25,103.75 L0.25,103.75 L0.25,0.25 Z"
                                                                                    id="Path-2"></path>
                                                                                <line x1="8.5" y1="104" x2="8.5"
                                                                                      y2="8.25"
                                                                                      id="Path-3"></line>
                                                                                <line x1="12.5" y1="104" x2="12.5"
                                                                                      y2="12.25"
                                                                                      id="Path-3"></line>
                                                                                <line x1="4.5" y1="104" x2="4.5"
                                                                                      y2="4.25"
                                                                                      id="Path-3"></line>
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
                                                <svg width="172px" height="92px" viewBox="0 0 172 92" version="1.1"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>Rank 3</title>
                                                    <g id="REPLOG-362" stroke="none" stroke-width="1" fill="none"
                                                       fill-rule="evenodd">
                                                        <g id="Repair-Connects-→-Organisatie-(publiek)"
                                                           transform="translate(-836.000000, -1084.000000)">
                                                            <g id="Group-15"
                                                               transform="translate(173.000000, 493.000000)">
                                                                <g id="Group-21"
                                                                   transform="translate(0.000000, 483.000000)">
                                                                    <g id="Group-17"
                                                                       transform="translate(260.000000, 71.000000)">
                                                                        <g id="Group-20"
                                                                           transform="translate(403.000000, 37.000000)">
                                                                            <rect id="Rectangle" stroke="#333333"
                                                                                  stroke-width="0.5"
                                                                                  fill="#DBBAB4" x="0.25" y="0.25"
                                                                                  width="155.5"
                                                                                  height="91.5"></rect>
                                                                            <text id="18"
                                                                                  font-family="TitilliumWeb-Black, Titillium Web"
                                                                                  font-size="36" font-weight="800"
                                                                                  line-spacing="20"
                                                                                  fill="#333333">
                                                                                <tspan text-anchor="middle" x="75"
                                                                                       y="40">{{ $data['device_type_ranking'][3]['total'] }}</tspan>
                                                                            </text>
                                                                            <polygon id="Path-2" fill="#000000"
                                                                                     points="172 92 156 92 155.5 92 155.5 1.91513472e-15 156 1.91513472e-15 172 16"></polygon>
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
            </div>
        </div>
    </div>
    @php
        // Calculation
        //Set initial data to 0
        $first = 0;
        $second = 0;
        $third = 0;
        $fourth = 0;
        $fifth = 0;
        if ($data['devices']) {
            $first = $data['devices']['end_of_life']['relative'];
            $second = $first + $data['devices']['repaired']['relative'];
            $third  = $second + $data['devices']['advice']['relative'];
            $fourth = $third + $data['devices']['spare_parts']['relative'];
            $fifth  = $fourth + $data['devices']['resign_repair']['relative'];
        }

    @endphp
    <style>
        .donut {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 225px;
            height: 225px;
            border-radius: 50%;

            /* Data */
            background: conic-gradient(
                #71b8c5 0% {{ $first}}%,
                #9C7A97 {{ $first }}% {{ $second }}%,
                #2ECC71 {{ $second }}% {{ $third }}%,
                #F2CA27 {{ $third }}% {{ $fourth }}%,
                #F57C7C {{ $fourth }}% {{ $fifth }}%
            );
        }
        .hole {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            background: #fff;
        }
    </style>
@endsection
