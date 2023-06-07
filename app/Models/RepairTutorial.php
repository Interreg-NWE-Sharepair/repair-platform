<?php

namespace App\Models;

use App\Nova\Flexible\Layouts\HeaderLayout;
use App\Nova\Flexible\Layouts\ImageLayout;
use App\Nova\Flexible\Layouts\ImagesLayout;
use App\Nova\Flexible\Layouts\SimpleWysiwygLayout;
use App\Nova\Flexible\Layouts\SourceLayout;
use App\Nova\Flexible\Layouts\VideoLayout;
use App\Nova\Flexible\Layouts\WysiwygImageLayout;
use App\Traits\HasTranslations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

/**
 * @mixin IdeHelperRepairTutorial
 */
class RepairTutorial extends Model implements LocalizedUrlRoutable, HasMedia
{
    use HasFactory;
    use HasTranslations;
    use HasFlexible;
    use HasTranslatableSlug;
    use InteractsWithMedia;

    public $translatable = [
        'title',
        'description',
        'intro',
        'slug',
    ];

    protected $hidden = ['id'];

    protected $appends = ['flexible'];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function commonDeviceTypeIssue()
    {
        return $this->belongsTo(CommonDeviceTypeIssue::class);
    }

    /**
     * Media conversion to make all images "cropped" to specific size -> Square
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media
     * @return void
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        //extra specifications: https://spatie.be/docs/image/v1/image-manipulations/overview
        $this->addMediaConversion('resize')->width(772)->height(772)->sharpen(10)->optimize();

        $this->addMediaConversion('crop')->sharpen(10)->fit(Manipulations::FIT_CROP, 772, 772)->optimize();
    }

    /**
     * Rebuilds the whole flexible content for frontend because working with the media library didn't work wel..
     * not default included in flexible content response
     *
     * @return array
     */
    public function getFlexibleAttribute()
    {
        $result = [];
        $content = $this->flexible('content', [
            'header' => HeaderLayout::class,
            'image' => ImageLayout::class,
            'images' => ImagesLayout::class,
            'wysiwyg-image' => WysiwygImageLayout::class,
            'video' => VideoLayout::class,
            'wysiwyg' => SimpleWysiwygLayout::class,
            'source' => SourceLayout::class,
        ]);

        if ($content) {
            foreach ($content as $index => $item) {
                if (is_array($item)) {
                    return;
                }
                /** @var \Whitecube\NovaFlexibleContent\Layouts\Layout $item */
                if (in_array($item->name(), [
                    'wysiwyg-image',
                    'image',
                    'images',
                ])) {
                    if (in_array($item->name(), [
                        'image',
                        'wysiwyg-image',
                    ])) {

                        /** @var Media $media */
                        $media = $this->getMedia('image', ['flexibleKey' => $item->key()])->first();
                        $image = null;
                        if ($media) {
                            if ($media->disk === 'public') {
                                $image['original'] = $media->getFullUrl();
                                $image['resize'] = $media->getFullUrl('resize');
                                $image['crop'] = $media->getFullUrl('crop');
                            } else {
                                $image['original'] = $media->getTemporaryUrl(Carbon::now()->addMinutes(5));
                                $image['resize'] = $media->getTemporaryUrl(Carbon::now()->addMinutes(5), 'resize');
                                $image['crop'] = $media->getTemporaryUrl(Carbon::now()->addMinutes(5), 'crop');
                            }
                            $item->setAttribute('image', $image);
                        }
                    } elseif ($item->name() === 'images') {
                        $items = [];
                        $images = $this->getMedia('images', ['flexibleKey' => $item->key()])->all();
                        foreach ($images as $pos => $image) {
                            if ($image->disk === 'public') {
                                $items[$pos]['original'] = $image->getFullUrl();
                                $items[$pos]['resize'] = $image->getFullUrl('resize');
                                $items[$pos]['crop'] = $image->getFullUrl('crop');
                            } else {
                                $items[$pos]['original'] = $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
                                $items[$pos]['resize'] = $image->getTemporaryUrl(Carbon::now()
                                                                                       ->addMinutes(5), 'resize');
                                $items[$pos]['crop'] = $image->getTemporaryUrl(Carbon::now()->addMinutes(5), 'crop');
                            }
                        }
                        $item->setAttribute('images', $items);
                    }
                    $content[$index] = $item;
                }
                $attributes = [];
                $locale = app()->getLocale();
                foreach ($item->getAttributes() as $attributeIndex => $attributeItem) {
                    if (isset($attributeItem->$locale)) {
                        $attributes[$attributeIndex] = $attributeItem->$locale;
                    } else {
                        $attributes[$attributeIndex] = $attributeItem;
                    }
                }

                $result[$index] = [
                    'attributes' => $attributes,
                    'layout' => $item->name(),
                ];
            }
        }

        return $result;
    }

    public function scopeIsLocalized($query, $locale)
    {
        return $query->whereNotNull('title' . '->' . $locale);
    }

    public function scopeFindByLocalizedSlug($query, $slug)
    {
        $slugField = $this->getSlugOptions()->slugField;

        return $query->where(function ($query) use ($slug, $slugField) {
            $locales = config('app.supported_locales');
            foreach ($locales as $locale) {
                $query->orWhere($slugField . '->' . $locale, $slug);
            }
        });
    }

    public function scopeIsHighlighted($query)
    {
        return $query->where('is_highlight', 1);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return self::query()->findByLocalizedSlug($value)->first();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getLocalizedRouteKey($locale)
    {
        return $this->getTranslation($this->getRouteKeyName(), $locale);
    }
}
