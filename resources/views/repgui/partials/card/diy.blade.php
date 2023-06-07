@if(empty($bgColor))
  @php
    $bgColor = false;
  @endphp
@endif

<div class="w-full px-2 mt-4 sm:w-1/2 lg:w-1/3">
    <div class="relative flex flex-col h-full p-6 @if ($bgColor) bg-light @else bg-white @endif rounded-card">
        <h3 class="mb-3 text-2xl font-semibold ">
            <svg class="flex-shrink-0 mb-1 mr-1 icon" aria-hidden="true">
                <use xlink:href="/repgui/icon/sprite.svg#file-document"></use>
            </svg>
            {{ $repairGuide->title }}
        </h3>
        @if(isset($deviceType) && $deviceType)
          <span class="text-base font-bold">{{ $repairGuide->deviceType->name }}</span>
        @endif
        @if ($repairGuide->commonDeviceTypeIssue)
            <span class="text-sm font-thin">{{ $repairGuide->commonDeviceTypeIssue->issue }}</span>
        @endif
        <div class="my-4">
            @if($repairGuide->intro)
                {{ $repairGuide->intro }}
            @else
                {{ $repairGuide->description }}
            @endif
        </div>
        <a class="mt-auto link link--ext link--extended"
           @php
           if (!$target || $target === 'blank') {
               $url = \LaravelLocalization::getLocalizedUrl(app()->getLocale(), trans('routes.tutorial_show'), ['repairTutorial' => $repairGuide]);
           } elseif( $target === 'self') {
               $url = route('api.tutorial.show', ['repairTutorial' => $repairGuide]);
               if ($params) {
                   $url .= '?' . $params;
               }
           }
           @endphp
        href="{{ $url }}" @if($target === 'blank') target="_blank" @elseif($target === 'self') target="_self" @else target="_top" @endif>{{ trans('repgui.view_tutorial') }}</a>
    </div>
</div>
