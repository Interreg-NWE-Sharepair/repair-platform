@props(['data'])
<div class="mb-10">
    @if ($data['title'])
        <h3 class="text-h3">
            {{ $data['title'] }}
        </h3>
    @endif

    <figure>
        <div class="embed-container">
            @if ($data['video'])
                <iframe
                    class="border-0"
                    src="{{ $data['video'] }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture fullscreen"
                    title="{{ $data['caption'] }}"
                    alt="{{ $data['caption'] }}"></iframe>
            @elseif($data['iframe'])
                {!!  $data['iframe']  !!}
            @endif
        </div>
        @if($data['caption'])
            <figcaption class="mt-1 text-sm italic text-left">
                {{ $data['caption'] }}
            </figcaption>
        @endif
    </figure>
</div>

