@extends('repgui.layouts.layout')
@section('content_body')
    <div class="section section--light">
        <div class="container">
            <div>
                <form method="POST" action="{{ route('guide_step_2_store') }}" class="form">
                    @csrf
                    @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => true, 'top' => true])
                    <div>
                        <h2>{{ trans('repgui.describe_the_problem') }}</h2>
                        <p>{{ trans('repgui.describe_the_problem_intro') }}</p>
                    </div>

                    <div class="p-6 my-8 bg-white border-4 rounded border-primary-200">
                        <dl class="space-y-2">
                            <dd>
                                <strong>{{ trans('repgui.product_category') }}:</strong>
                                {{ $repairGuidanceLog->deviceType->name }}
                            </dd>

                            @if ($repairGuidanceLog->product_age)
                                <dd>
                                    <strong>{{ trans('repgui.product_age') }}:</strong> {{ $repairGuidanceLog->product_age }}
                                </dd>
                            @endif
                        </dl>
                    </div>
                    @if($commonDeviceTypeIssues)
                        <div>
<!--                             ONLY ABLE TO SELECT ONE!!! -->

                            <label class="form__label" for="common_device_issue">{{ trans('repgui.common_device_issue') }}</label>
                            @php
                            $issueIds = old('common_device_issues');
                            if (!$issueIds && $repairGuidanceLog->commonDeviceTypeIssues) {
                                foreach ($repairGuidanceLog->commonDeviceTypeIssues as $issue) {
                                    $issueIds[] = $issue->id;
                                }

                            }
                            @endphp
                            <ul class="space-y-2">
                                @foreach ($commonDeviceTypeIssues as $option)
                                    <li>
                                        <label for="{{ $option->id }}">
                                            <input id="{{ $option->id }}" type="checkbox" name="common_device_issues[]"
                                            value="{{ $option->id }}" @if($issueIds && in_array($option->id, $issueIds)) checked="checked" @endif> {{ $option->issue }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="my-6 text-2xl">{{ trans('repgui.or') }}</div>
                    @endif
                    <div class="w-full md:w-3/5">
                        <label class="form__label" for="problem_now">{{ trans('repgui.problem_now') }}</label>
                        @php
                        $problem_now = old('problem_now');
                        if (!$problem_now && $repairGuidanceLog->common_issue_text) {
                            $problem_now = $repairGuidanceLog->common_issue_text;
                        }
                        @endphp
                        <input class="form__input" type="text" id="problem_now" name="problem_now" placeholder="{{ trans('repgui.problem_now_placeholder') }}" value="{{ $problem_now }}">
                    </div>

                    @include('repgui.partials.guidance.steps', ['step' => $step, 'titles' => false, 'top' => false])
                </form>
            </div>
        </div>
    </div>
@endsection
