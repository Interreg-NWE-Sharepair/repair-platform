@props(['data'])

<div class="w-full mb-10 md:w-3/4">
    @if (isset($data['image']['resize']) && $data['image'])
        <a class="block m-2 overflow-hidden js-modal-image modal__image-trigger" data-group="image-gallery"
           href="{{ $data['image']['original'] }}" @if(isset($data['caption']))data-caption="{{ $data['caption'] }}" @endif role="button">
            <img src="{{ $data['image']['resize'] }}" class="" alt="">
        </a>
    @endif
</div>
