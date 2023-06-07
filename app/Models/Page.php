<?php

namespace App\Models;

use App\Nova\Flexible\Layouts\HeaderLayout;
use App\Nova\Flexible\Layouts\ImageLayout;
use App\Nova\Flexible\Layouts\ImagesLayout;
use App\Nova\Flexible\Layouts\SimpleWysiwygLayout;
use App\Nova\Flexible\Layouts\SourceLayout;
use App\Nova\Flexible\Layouts\VideoLayout;
use App\Nova\Flexible\Layouts\WysiwygImageLayout;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

/**
 * @mixin IdeHelperPage
 */
class Page extends Model implements LocalizedUrlRoutable, HasMedia
{
    use HasTranslations;
    use HasFlexible;
    use HasTranslatableSlug;
    use InteractsWithMedia;

    public $translatable = [
        'title',
        'intro',
        'body',
        'slug',
        'seo_description',
        'seo_keywords',
    ];

    protected $appends = [
        'flexible',
    ];

    const REGISTER_DEVICE = 'register_device';

    const REGISTER_REPAIRER = 'register_repairer';

    const CONTACT_CONFIRMATION = 'contact_confirmation';

    const ABOUT = 'about';

    const PRIVACY_POLICY = 'privacy';

    const COOKIE_POLICY = 'cookie';

    const TERMS_CONDITIONS = 'terms';

    const PARTICIPATION = 'participation';

    const INSTRUCTIONS = 'instructions-and-feedback';

    const ORGANISATION_CONFIRMATION = 'organisation_confirmation';

    const CONTRIBUTE = 'contribute';

    const GENERAL_TIPS = 'tips';

    const RECYCLE = 'recycle';

    public function getBodyAttribute($body)
    {
        return convertSpecialCharToNormalChar($body);
    }

    public function getTitleAttribute($title)
    {
        return convertSpecialCharToNormalChar($title);
    }

    public function getIntroAttribute($intro)
    {
        return convertSpecialCharToNormalChar($intro);
        //return utf8_encode($intro);
    }
    public function getSeoDescription($seo_description) {
        return convertSpecialCharToNormalChar($seo_description);
        //return utf8_encode($seo_description);
    }

    public function getSeoKeywordsAttributue($seo_keywords) {
        return convertSpecialCharToNormalChar($seo_keywords);
        //return utf8_encode($seo_keywords);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
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
                    $images = $this->getMedia('image', ['flexibleKey' => $item->key()]);
                    if (in_array($item->name(), [
                        'image',
                        'wysiwyg-image',
                    ])) {
                        /** @var Media $image */
                        $media = $images->first();
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
                        $images = $images->all();
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

    public function getAboutContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::ABOUT)->firstOrFail();
    }

    public function getPrivacyContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::PRIVACY_POLICY)->firstOrFail();
    }

    public function getCookieContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::COOKIE_POLICY)->firstOrFail();
    }

    public function getTermsContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::TERMS_CONDITIONS)->firstOrFail();
    }

    public function getContactConfirmationContent($tenant)
    {
        return self::query()->tenant($tenant)->where('type', self::CONTACT_CONFIRMATION)->firstOrFail();
    }

    public function getDeviceCreatedContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::REGISTER_DEVICE)->firstOrFail();
    }

    public function getRepairerCreatedContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::REGISTER_REPAIRER)->firstOrFail();
    }

    public function getParticipationContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::PARTICIPATION)->firstOrFail();
    }

    public function getInstructionsContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::INSTRUCTIONS)->firstOrFail();
    }

    public function getOrganisationConfirmationContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::ORGANISATION_CONFIRMATION)->firstOrFail();
    }

    public function getContributeContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::CONTRIBUTE)->firstOrFail();
    }

    public function getGeneralTipsContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::GENERAL_TIPS)->firstOrFail();
    }

    public function getRecycleContent($tenant)
    {
        return self::query()->tenant($tenant)->type(self::RECYCLE)->firstOrFail();
    }

    public function scopeLocale(Builder $query, $locale, $slug)
    {
        return $query->where("slug->$locale", '=', $slug);
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

    public function scopeTenant(Builder $query, $code)
    {
        return $query->whereHas('tenant', function ($q) use ($code) {
            return $q->where('code', $code);
        });
    }

    public function scopeType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return self::query()->findByLocalizedSlug($value)->first();
    }

    public function getSlugOptions(): SlugOptions
    {
        if (!$this->type) {
            return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->allowDuplicateSlugs();
        }

        return SlugOptions::create()->doNotGenerateSlugsOnCreate()->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getLocalizedRouteKey($locale)
    {
        return $this->getTranslation($this->getRouteKeyName(), $locale);
    }

    /**
     * @return void
     * @deprecated
     */
    public function moveIntroFromFlexible()
    {
        $content = $this->flexible('content', [
            'header' => HeaderLayout::class,
            'image' => ImageLayout::class,
            'images' => ImagesLayout::class,
            'wysiwyg-image' => WysiwygImageLayout::class,
            'video' => VideoLayout::class,
            'wysiwyg' => SimpleWysiwygLayout::class,
            'source' => SourceLayout::class,
        ]);

        if ($content === null) {
            return;
        }

        foreach (json_decode($content) as $item) {
            if (isset($item->layout)) {
                if ($item->layout === 'header') {
                    $attributes = $item->attributes;
                    if (isset($attributes->intro)) {
                        $this->intro = (array) $attributes->intro;
                        $this->save();
                    }
                }
            }
        }
    }
}
