<div class="flex flex-wrap justify-center flex-grow mb-4 -mx-2 sm:mb-10">
    @if ($titles)
        <div class="w-full px-2 sm:w-1/3">
            @if ($titles)
                <div class="flex items-center max-w-xs @if($step !== 1) opacity-50 @endif">
                    <div class="pr-2 text-6xl font-black text-secondary">1</div>
                    <div class="flex-grow font-bold leading-none lg:heading--arrow">{{ trans('repgui.step_tell_about_device') }}</div>
                </div>
            @endif
        </div>
        <div class="w-full px-2 mt-6 sm:mt-0 sm:w-1/3">
            @if($titles)
                <div class="flex items-center max-w-xs @if($step !== 2) opacity-50 @endif">
                    <div class="pr-2 text-6xl font-black text-secondary">2</div>
                    <div class="flex-grow font-bold leading-none lg:heading--arrow">{{ trans('repgui.step_describe_problem') }}</div>
                </div>
            @endif
        </div>
        <div class="justify-end w-full px-2 mt-6 sm:flex sm:mt-0 sm:w-1/3">
            @if($titles)
                <div class="flex items-center max-w-xs @if($step !== 3) opacity-50 @endif">
                    <div class="pr-2 text-6xl font-black text-secondary">3</div>
                    <div class="font-bold leading-none">{{ trans('repgui.step_check_repair_options') }}</div>
                </div>
            @endif
        </div>
    @endif
    <div class="flex justify-between w-full mt-6 -mx-2">
        <div class="px-2 @if($top)hidden md:block @endif">
            @if($step !== 1)
                <a class="btn {{ $step === 1 ? 'btn--disabled btn--prev text-sm sm:text-base' : 'btn--secondary btn--prev text-sm sm:text-base' }}"
                    href="{{ route("guide_step_$previousStep") }}">{{ trans('messages.form_previous_step') }}</a>
            @endif
        </div>
        @if ($step !== 3)
            <div class="px-2 @if($top)hidden md:block @endif">
                <button class="text-sm btn btn--secondary btn--ext sm:text-base" type="submit">{{ trans('messages.form_next_step') }}</button>
            </div>
        @endif
    </div>
</div>
