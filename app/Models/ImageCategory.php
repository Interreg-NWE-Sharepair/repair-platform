<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin IdeHelperImageCategory
 */
class ImageCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    const MIMES = [
        'image/jpeg',
        'image/png',
        'image/bmp',
        'image/gif',
        'image/svg',
        'image/webp',
    ];

    // Image type constants
    const IMAGE_GENERAL = 'GENERAL';

    const IMAGE_DEFECT = 'DEFECT';

    const IMAGE_BARCODE = 'BARCODE';

    const IMAGE_REPAIR = 'REPAIR';
}
