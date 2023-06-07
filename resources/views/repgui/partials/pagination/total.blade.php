<div>
    <p class="text-xl text-black">
        <span>{!! trans('repgui.paginator_showing') !!}</span>
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        <span> - </span>
        <span class="font-medium">{{ $paginator->lastItem() }}</span>
        <span>{!! trans('repgui.paginator_of') !!}</span>
        <span class="font-medium">{{ $paginator->total() }}</span>
        <span>{!! trans('repgui.result_repair_guides') !!}</span>
    </p>
</div>
