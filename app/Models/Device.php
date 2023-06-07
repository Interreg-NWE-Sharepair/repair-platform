<?php

namespace App\Models;

use App\Facades\PersonRepository;
use App\Scopes\TempScope;
use App\Traits\HasQueryParameters;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Device
 *
 * @mixin IdeHelperDevice
 */
class Device extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasSlug;
    use HasUuid;
    use HasQueryParameters;

    const PADDING_LENGTH = 6;

    const STATUS_COLORS = [
        RepairLog::STATUS_OPEN => 'green',
        RepairLog::STATUS_REOPENED => 'yellow',
        RepairLog::STATUS_IN_REPAIR => 'orange',
        RepairLog::STATUS_COMPLETED => 'gray',
    ];

    const REGISTER_TYPE_PERSON = 'person';

    const REGISTER_TYPE_EVENT = 'event';

    protected $appends = [
        'status_color',
        'created_timestamp',
        'device_type_name',
        'image',
        'status_last_updated_at',
        'padded_id',
        'show_postal_code',
        'owner_name',
        'repairer_name',
        'log_notes',
        'device_notes',
        'general_image',
        'defect_images',
        'barcode_images',
        'images',
        'has_future_event',
    ];

    protected $with = [
        'media',
        'organisation',
    ];

    protected $fillable = [
        'brand_name',
        'model_name',
        'device_type_id',
        'device_description',
        'issue_description',
        'organisation_id',
        'manufacture_year',
        'first_name',
        'last_name',
        'email',
        'telephone',
        'closed_at',
        'temp',
        'locale',
        'postal_code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'closed_at',
    ];

    protected $casts = [
        'manufacture_year' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TempScope());
    }

    public function getPaddedId()
    {
        return str_pad($this->id, self::PADDING_LENGTH, '0', STR_PAD_LEFT);
    }

    public function getName()
    {
        return $this->brand_name . ' ' . $this->model_name;
    }

    public function getOwnerName()
    {
        return convertSpecialCharToNormalChar($this->first_name . ' ' . $this->last_name[0] . '.');
    }

    public function getFullName() {
        return convertSpecialCharToNormalChar($this->first_name . ' ' . $this->last_name);
    }

    // ATTRIBUTES

    public function getOwnerNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return convertSpecialCharToNormalChar($this->first_name . ' ' . $this->last_name[0] . '.');
        }

        return null;
    }

    public function getPaddedIdAttribute()
    {
        return $this->getPaddedId();
    }

    /**
     * Returns the current status color based on repair log
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->latest_status] ?? 'green';
    }

    public function getRepairerNameAttribute()
    {
        $repairLog = $this->repairLog;
        if (!$repairLog) {
            return null;
        }

        $person = $repairLog->person;
        if ($person) {
            return ucfirst($person->first_name) . ' ' . ucfirst(substr($person->last_name, 0, 1)) . '.';
        }

        return trans('messages.person_deleted');
    }

    public function getCreatedTimestampAttribute()
    {
        if ($this->created_at) {
            return $this->created_at->isoFormat('DD MMMM YYYY');
        }
    }

    public function getDeviceTypeNameAttribute()
    {
        return $this->deviceType->name ?? null;
    }

    public function getImageAttribute()
    {
        $image = $this->getGeneralImage()->first();
        if (!$image) {
            $image = $this->getDefectImages()->first();
        }
        if (!$image) {
            $image = $this->getBarcodeImages()->first();
        }

        if ($image) {
            if ($image->disk === 'public') {
                return $image->getFullUrl();
            }

            return $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
        }

        return null;
    }

    public function getGeneralImageAttribute()
    {
        $image = $this->getGeneralImage()->first();
        if ($image && $image->disk === 'public') {
            return $image->getFullUrl();
        }

        if ($image) {
            return $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
        }

        return null;
    }

    public function getDefectImagesAttribute()
    {
        $images = $this->getDefectImages()->all();
        $defectImages = [];
        if ($images) {
            foreach ($images as $image) {
                if ($image->disk === 'public') {
                    $defectImages[] = $image->getFullUrl();
                } else {
                    $defectImages[] = $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
                }
            }
        }

        return $defectImages;
    }

    public function getBarcodeImagesAttribute()
    {
        $images = $this->getBarcodeImages()->all();
        $barcodeImages = [];
        if ($images) {
            foreach ($images as $image) {
                if ($image->disk === 'public') {
                    $barcodeImages[] = $image->getFullUrl();
                } else {
                    $barcodeImages[] = $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
                }
            }
        }

        return $barcodeImages;
    }

    public function getImagesAttribute()
    {
        return $this->getImagesByUrl();
    }

    public function getStatusLastUpdatedAtAttribute()
    {
        if ($this->repairLog) {
            return $this->repairLog->updated_at->isoFormat('DD MMMM YYYY');
        }
    }

    /**
     * Only show the postal code on overview when new or when back to selected status
     */
    public function getShowPostalCodeAttribute()
    {
        $latestRepairLog = $this->repairLog;

        return !$latestRepairLog || ($this->latest_status === RepairLog::STATUS_REOPENED);
    }

    public function getLogNotesAttribute()
    {
        $notes = [];
        try {
            /** @var \App\Models\RepairLog $repairLog */
            $repairLog = $this->repairLog;
            if ($repairLog && $repairLog->repairNotes()->exists()) {
                $repairLogsNotes = $repairLog->repairNotes()->orderByDesc('created_at')
                                             ->where('repair_log_id', $repairLog->id)->get();
                $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
                foreach ($repairLogsNotes as $note) {
                    /** @var \App\Models\RepairLogNote $note */
                    $isDisabled = false;
                    if (!$note->repairLog->person || $note->repairLog->person->id !== $note->person->id) {
                        $isDisabled = true;
                    }

                    if ($person->id === $note->person_id) {
                        $isDisabled = false;
                    }
                    $noteArray = [
                        'id' => $note->id,
                        'content' => $note->content,
                        'created_timestamp' => $note->created_at->timestamp,
                        'formatted_timestamp' => $note->created_at->format('d/m/Y'),
                        'repairer' => $note->person ? $note->person->full_name : trans('messages.person_deleted'),
                        'disabled' => $isDisabled,
                    ];
                    $notes[] = $noteArray;
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return collect($notes);
    }

    public function getDeviceNotesAttribute()
    {
        $notes = [];
        if ($this->notes()->exists()) {
            foreach ($this->notes()->get() as $note) {
                $noteArray = [
                    'id' => $note->id,
                    'content' => $note->content,
                    'created_timestamp' => $note->created_at->timestamp,
                    'formatted_timestamp' => $note->created_at->format('d/m/Y'),
                    'repairer' => $note->person ? $note->person->full_name : trans('messages.person_deleted'),
                    'disabled' => false,
                ];
                $notes[] = $noteArray;
            }
        }

        return collect($notes);
    }

    public function getHasFutureEventAttribute()
    {
        if ($this->event && $this->event->getDateTimeStart()->gt(Carbon::now())) {
            return true;
        }

        return false;
    }

    // RELATIONS

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function repairLogs()
    {
        return $this->hasMany(RepairLog::class, 'device_id', 'id')->with('person', 'repairLinks', 'repairBarriers')
                    ->orderBy('created_at');
    }

    public function repairLog()
    {
        return $this->hasOne(RepairLog::class, 'device_id', 'id')->latest()->withDefault(null);
    }

    public function completedLog()
    {
        return $this->repairLog()->completed();
    }

    public function completedRepairStatus()
    {
        return $this->belongsTo(CompletedRepairStatus::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(DeviceNote::class)->orderBy('created_at', 'desc');
    }

    public function note()
    {
        return $this->hasOne(DeviceNote::class)->latest();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ImageCategory::IMAGE_GENERAL)->singleFile()->withResponsiveImages()
             ->useDisk('digitalocean');
        $this->addMediaCollection(ImageCategory::IMAGE_BARCODE)->withResponsiveImages()->useDisk('digitalocean');
        $this->addMediaCollection(ImageCategory::IMAGE_DEFECT)->withResponsiveImages()->useDisk('digitalocean');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')->width(150)->height(150)->fit(Manipulations::FIT_CROP, 150, 150)->sharpen(8)
             ->keepOriginalImageFormat()->nonOptimized();
        $this->addMediaConversion('preview')->fit(Manipulations::FIT_CROP, 150, 150)->nonOptimized()->nonQueued();
    }

    public function getGeneralImage()
    {
        return $this->getMedia(ImageCategory::IMAGE_GENERAL);
    }

    public function setGeneralImage($image)
    {
        if ($image) {
            $this->clearMediaCollection(ImageCategory::IMAGE_GENERAL);
            $this->addMedia($image)->toMediaCollection(ImageCategory::IMAGE_GENERAL);
        }
    }

    public function getDefectImages()
    {
        return $this->getMedia(ImageCategory::IMAGE_DEFECT);
    }

    public function setDefectImages($images)
    {
        foreach ($images ?? [] as $image) {
            $this->addMedia($image)->toMediaCollection(ImageCategory::IMAGE_DEFECT);
        }
    }

    public function getBarcodeImages()
    {
        return $this->getMedia(ImageCategory::IMAGE_BARCODE);
    }

    public function setBarcodeImages($images)
    {
        foreach ($images ?? [] as $image) {
            $this->addMedia($image)->toMediaCollection(ImageCategory::IMAGE_BARCODE);
        }
    }

    /**
     * Returns all the images with their full url
     *
     * @return array
     */
    public function getImagesByUrl()
    {
        $images = [];
        $generalImages = $this->getGeneralImage();
        if ($generalImages) {
            foreach ($generalImages as $generalImage) {
                /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $generalImage * */
                if ($generalImage->disk === 'public') {
                    $images[] = [
                        'id' => $generalImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $generalImage->hasGeneratedConversion('small') ? $generalImage->getFullUrl('small') : $generalImage->getFullUrl(),
                        'url' => $generalImage->getFullUrl(),
                    ];
                } else {
                    $images[] = [
                        'id' => $generalImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $generalImage->hasGeneratedConversion('small') ? $generalImage->getTemporaryUrl(Carbon::now()
                                                                                                                         ->addMinutes(5), 'small') : $generalImage->getTemporaryUrl(Carbon::now()
                                                                                                                                                                                          ->addMinutes(5)),
                        'url' => $generalImage->getTemporaryUrl(Carbon::now()->addMinutes(5)),
                    ];
                }
            }
        }
        $defectImages = $this->getDefectImages();
        if ($defectImages) {
            foreach ($defectImages as $defectImage) {
                if ($defectImage->disk === 'public') {
                    $images[] = [
                        'id' => $defectImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $defectImage->hasGeneratedConversion('small') ? $defectImage->getFullUrl('small') : $defectImage->getFullUrl(),
                        'url' => $defectImage->getFullUrl(),
                    ];
                } else {
                    $images[] = [
                        'id' => $defectImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $defectImage->hasGeneratedConversion('small') ? $defectImage->getTemporaryUrl(Carbon::now()
                                                                                                                       ->addMinutes(5), 'small') : $defectImage->getTemporaryUrl(Carbon::now()
                                                                                                                                                                                       ->addMinutes(5)),
                        'url' => $defectImage->getTemporaryUrl(Carbon::now()->addMinutes(5)),
                    ];
                }
            }
        }

        $barcodeImages = $this->getBarcodeImages();
        if ($barcodeImages) {
            foreach ($barcodeImages as $barcodeImage) {
                if ($barcodeImage->disk === 'public') {
                    $images[] = [
                        'id' => $barcodeImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $barcodeImage->hasGeneratedConversion('small') ? $barcodeImage->getFullUrl('small') : $barcodeImage->getFullUrl(),
                        'url' => $barcodeImage->getFullUrl(),
                    ];
                } else {
                    $images[] = [
                        'id' => $barcodeImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $barcodeImage->hasGeneratedConversion('small') ? $barcodeImage->getTemporaryUrl(Carbon::now()
                                                                                                                         ->addMinutes(5), 'small') : $barcodeImage->getTemporaryUrl(Carbon::now()
                                                                                                                                                                                          ->addMinutes(5)),
                        'url' => $barcodeImage->getTemporaryUrl(Carbon::now()->addMinutes(5)),
                    ];
                }
            }
        }

        return $images;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom([
            'brand_name',
            'model_name',
        ])->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    //SCOPES

    // Don't think this is used anymore, if errors occur, uncomment, if not, delete
    //public function scopeRepairable(Builder $query)
    //{
    //    return $query->whereIn('status', [RepairLog::STATUS_OPEN, RepairLog::STATUS_REOPENED]);
    //}

    /**
     * @param $query
     * @return mixed
     */
    public function scopeStillBroken(Builder $query)
    {
        return $query->whereIn('latest_status', [
            RepairLog::STATUS_OPEN,
            RepairLog::STATUS_REOPENED,
        ]);
    }

    // Don't think this is used anymore, if errors occur, uncomment, if not, delete
    //public function scopeOpen(Builder $query)
    //{
    //    return $query->whereIn('status', [RepairLog::STATUS_OPEN]);
    //}

    public function scopeOrganisation(Builder $query, $uuid)
    {
        $query->whereHas('organisation', function ($q) use ($uuid) {
            $q->where('uuid', $uuid);
        });
    }

    public function scopeEvent(Builder $query, $id)
    {
        $query->whereHas('event', function ($q) use ($id) {
            $q->where('id', $id);
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFixed(Builder $query, $person = null)
    {
        return $query->whereHas('repairLogs', function (Builder $query) use ($person) {
            if ($person) {
                $query->whereHas('person', function ($query) use ($person) {
                    $query->where('id', $person->id);
                });
            }
            $query->where('latest_status', RepairLog::STATUS_COMPLETED)->groupBy(['device_id']);
        });
    }

    public function scopeStatusOrder(Builder $query): Builder
    {
        return $query->orderByRaw("CASE
                        WHEN latest_status = 'open' THEN 1
                        WHEN latest_status = 'reopened' THEN 2
                        WHEN latest_status = 'in_repair' THEN 3
                        WHEN latest_status = 'completed' THEN 4
                 ELSE 5 END ASC
                ");
    }

    public function scopeLastRepairStatusIsNot(Builder $query, $repairStatuses): Builder
    {
        return $query->whereNotIn('latest_status', $repairStatuses);
    }

    public function scopeSearch(Builder $query, $request)
    {
        if (!$request) {
            $request = request();
        }

        $updatedAfter = parseQueryDate($request->input('updated_after'));
        if ($updatedAfter) {
            $query->updatedAfter($updatedAfter);
        }

        return $query;
    }

    public function scopeupdatedAfter(Builder $query, $updatedAfter)
    {
        return $query->where('updated_at', '>', $updatedAfter)->orWhereHas('repairLog', function ($query) use (
            $updatedAfter
        ) {
            return $query->where('updated_at', '>', $updatedAfter);
        });
    }

    public function store($data)
    {
        //Save fields
        $this->fill($data);
        //Save media
        $this->setGeneralImage($data['image_general']);
        $this->setDefectImages($data['images_defect']);
        $this->setBarcodeImages($data['images_barcode']);
        //Save object
        $this->save();
    }

    public function setTemp(bool $bool)
    {
        $this->temp = $bool;
    }

    public function getCompletedAtAttribute()
    {
        return $this->closed_at ?? $this->completedLog->created_at;
    }

    public function isCompleted()
    {
        return $this->latest_status === RepairLog::STATUS_COMPLETED;
    }

    /**
     * Add a new note entry to a device
     *
     * @param $content
     */
    public function addNote($content, Person $person): void
    {
        if ($content) {
            $note = new DeviceNote();
            $note->content = $content;
            $note->device()->associate($this);
            $note->person()->associate($person);
            $note->save();
        }
    }

    /**
     * Edit a note linked to a device
     *
     * @param $id
     * @param $content
     */
    public function editNote($id, $content, Person $person): void
    {
        $note = $this->note()->where('id', $id)->first();
        if (!$note) {
            $note = new DeviceNote();
        }
        if (!$note->person) {
            $note->person()->associate($person);
        }
        if (!$note->device) {
            $note->device()->associate($this);
        }

        $note->content = $content;
        $note->save();
    }
}
