@props(['data'])
@if (isset($data['images']))
    <div class="flex flex-wrap justify-center mb-10 -mx-4 -mt-4">
        @foreach ($data['images'] as $image)
            @if ($image)
                <div class="w-1/2 px-4 mt-4 md:w-1/3">
                    <a class="block m-2 overflow-hidden js-modal-image modal__image-trigger" data-group="image-gallery" href="{{ $image['original'] }}" @if(isset($image['caption']))data-caption="{{ $image['caption'] }}" @endif role="button">
                        <img src="{{ $image['crop'] }}" class="" alt="">
                    </a>
                </div>
            @endif
        @endforeach
    </div>
@endif
