<?php

namespace App\Http\Resources\V1;

use App\Helpers\EnvironmentMediaPathGenerator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
        $media = $this->resource;

        return [
            'file_name' => $media->name,
            'width' => $this->when($media->hasCustomProperty('width'), $media->getCustomProperty('width')),
            'height' => $this->when($media->hasCustomProperty('height'), $media->getCustomProperty('height')),
            'url' => $media->getUrl(),
            'expires_at' => null,
            //Can be removed in v2
            'responsive_sizes' => $this->when(count($media->responsiveImages()->files) > 0, $this->buildResponsiveData($media)),
        ];
    }

    private function buildResponsiveData(Media $media)
    {
        $data = [];
        $responsiveImages = $media->responsiveImages()->files;

        /** @var \Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage $responsiveImage */
        foreach ($responsiveImages as $responsiveImage) {
            $path = (new EnvironmentMediaPathGenerator)->getPathForResponsiveImages($media);
            $path = $path . $responsiveImage->fileName;
            $url = Storage::disk($media->disk)->url($path);

            $data[] = [
                'file_name' => $responsiveImage->fileName,
                'width' => $responsiveImage->width(),
                'height' => $responsiveImage->height(),
                'url' => $url,
                'expires_at' => null,
                //Can be removed in v2
            ];
        }

        return $data;
    }
}
