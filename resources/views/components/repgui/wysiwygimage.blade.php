@props(['data' => ['body' => null, 'position' => 'left', 'image' => null, 'caption' => null]])

<div class="flex flex-wrap items-center mb-10 -mx-4 -mt-4">
    <div class="w-full px-4 mt-4 md:w-1/2">
        @if (isset($data['image']) && $data['image'])
            <figure>
                <a href="{{ $data['image']['original']  }}" class="overflow-hidden js-modal-image" @if(isset($data['caption'])) data-caption="{{ $data['caption'] }}" @endif>
                    <img src="{{ $data['image']['resize']  }}" @if(isset($data['caption'])) alt="{{ $data['caption'] }}" @endif class="w-full"/>
                </a>
                @if ($data['caption'])
                    <figcaption class="mt-1 text-sm italic text-left">
                        {{ $data['caption'] }}
                    </figcaption>
                @endif
            </figure>
        @endif
    </div>
    <div class="w-full md:w-1/2 px-4 @if($data['position'] === 'right') order-first @endif">
        <div class="wysiwyg">
            {!! $data['body']  !!}
        </div>
    </div>
</div>
