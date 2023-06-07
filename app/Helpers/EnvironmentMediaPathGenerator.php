<?php

namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

// Customize the path where the image gets stored (on the local filesystem, on S3, etc)
class EnvironmentMediaPathGenerator extends DefaultPathGenerator
{
    protected $path;

    public function __construct()
    {
        $this->path = app()->env . '/media/';
    }

    protected function getBasePath(Media $media): string
    {
        if ($media->disk === 'digitalocean') {
            return $this->path . parent::getBasePath($media);
        }

        return parent::getBasePath($media);
    }
}
