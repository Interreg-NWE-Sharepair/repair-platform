@props(['data'])

<div class="w-full mb-10 border-4 md:w-3/4">
    <div class="p-6">
        <h4>{{ trans('repgui.source_information') }}</h4>
        <div class="redactor">
            {!! $data['body'] !!}
        </div>
    </div>
</div>
