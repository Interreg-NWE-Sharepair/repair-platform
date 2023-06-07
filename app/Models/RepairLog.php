<?php

namespace App\Models;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class RepairLog
 *
 * @mixin IdeHelperRepairLog
 */
class RepairLog extends Model implements HasMedia
{
    use InteractsWithMedia;
    use GeneratesUuid;
    use BelongsToThrough;

    const STATUS_OPEN = 'open';

    const STATUS_IN_REPAIR = 'in_repair';

    const STATUS_REOPENED = 'reopened';

    const STATUS_COMPLETED = 'completed';

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_REPAIR,
        self::STATUS_REOPENED,
        self::STATUS_COMPLETED,
    ];

    protected $appends = [
        'timestamp',
        'updated_timestamp',
        'repair_images',
        'links',
        'barriers',
        'images',
        'repair_status',
    ];

    protected $with = [
        'person',
    ];

    /**
     * The max amount of selected logs that can be assigned to 1 repairer
     */
    const MAX_ASSIGNED_LOGS = 50;

    protected $fillable = [
        'repairer_id',
        'repair_status_id',
        'device_id',
        'reminder_mail_sent',
        'fix_description',
        'diagnosis',
        'root_cause',
        'used_materials',
        'is_using_spare_parts',
        'used_links',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (RepairLog $repairLog) {
            $repairLog->syncStatus();
        });
    }

    public function completedRepairStatus()
    {
        return $this->belongsTo(CompletedRepairStatus::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function repairLinks()
    {
        return $this->hasMany(RepairLogLink::class);
    }

    public function repairNotes()
    {
        return $this->hasMany(RepairLogNote::class)->orderBy('created_at', 'desc');
    }

    public function repairNote()
    {
        return $this->hasOne(RepairLogNote::class)->latest();
    }

    public function repairBarriers()
    {
        return $this->belongsToMany(RepairBarrier::class, 'repair_log_barriers')->withTimestamps();
    }

    public function repairBarrier()
    {
        return $this->hasOneThrough(RepairBarrier::class, RepairLogBarrier::class, 'repair_log_id', 'id', 'id', 'repair_barrier_id');
    }

    public function organisation()
    {
        return $this->belongsToThrough(Organisation::class, Device::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ImageCategory::IMAGE_REPAIR)->withResponsiveImages()->useDisk('digitalocean');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')->width(150)->height(150)->fit(Manipulations::FIT_CROP, 150, 150)->sharpen(8)
             ->keepOriginalImageFormat();
        $this->addMediaConversion('preview')->fit(Manipulations::FIT_CROP, 150, 150)->nonOptimized()->nonQueued();
    }

    public function getRepairedImages()
    {
        return $this->getMedia(ImageCategory::IMAGE_REPAIR);
    }

    public function updateRepairedImages($images)
    {
        if ($images && $this->hasMedia(ImageCategory::IMAGE_REPAIR)) {
            $this->clearMediaCollection(ImageCategory::IMAGE_REPAIR);
        }
        foreach ($images ?? [] as $image) {
            $this->addMedia($image)->toMediaCollection(ImageCategory::IMAGE_REPAIR);
        }
    }

    public function getImagesByUrl()
    {
        $images = [];
        $repairImages = $this->getRepairedImages();
        if ($repairImages) {
            foreach ($repairImages as $repairImage) {
                /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $repairImage * */
                if ($repairImage->disk === 'public') {
                    $images[] = [
                        'id' => $repairImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $repairImage->hasGeneratedConversion('small') ? $repairImage->getFullUrl('small') : $repairImage->getFullUrl(),
                        'url' => $repairImage->getFullUrl(),
                    ];
                } else {
                    $images[] = [
                        'id' => $repairImage->id,
                        'name' => trans('messages.image_general'),
                        'small' => $repairImage->hasGeneratedConversion('small') ? $repairImage->getTemporaryUrl(Carbon::now()
                                                                                                                       ->addMinutes(5), 'small') : $repairImage->getTemporaryUrl(Carbon::now()
                                                                                                                                                                                       ->addMinutes(5)),
                        'url' => $repairImage->getTemporaryUrl(Carbon::now()->addMinutes(5)),
                    ];
                }
            }
        }

        return $images;
    }

    public function getParsedMedia()
    {
        $images = [];
        $repairImages = $this->getRepairedImages();
        if ($repairImages) {
            if ($repairImages) {
                /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $repairImages * */
                foreach ($repairImages as $repairImage) {
                    $images[] = [
                        'id' => $repairImage->id,
                        'value' => $repairImage->id,
                        'text' => $repairImage->name,
                    ];
                }
            }
        }

        return $images;
    }

    public function updateRepairLinks($links)
    {
        $this->repairLinks()->delete();
        foreach ($links ?? [] as $link) {
            if (isset($link['url'])) {
                $repairLink = new RepairLogLink();
                $repairLink->url = $link['url'];
                $repairLink->repairLog()->associate($this);

                $repairLink->save();
            }
        }
    }

    // Don't think this is used anymore, if errors occur, uncomment, if not, delete
    //public function setStatus($status)
    //{
    //    $repairStatus = RepairStatus::where('code', $status)->first();
    //    if ($repairStatus) {
    //        $this->repairStatus()->associate($repairStatus);
    //        $this->save();
    //    }
    //}

    public function getTimestampAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getUpdatedTimestampAttribute()
    {
        return $this->updated_at->format('d/m/Y');
    }

    public function getImagesAttribute()
    {
        return $this->getImagesByUrl();
    }

    public function getRepairImagesAttribute()
    {

        return $this->getRepairedImages()->all();
        $images = $this->getRepairedImages()->all();
        $repairImages = [];
        if ($images) {
            foreach ($images as $image) {
                if ($image->disk === 'public') {
                    $repairImages[] = $image->getFullUrl();
                } else {
                    $repairImages[] = $image->getTemporaryUrl(Carbon::now()->addMinutes(5));
                }
            }
        }

        return $repairImages;
    }

    public function getLinksAttribute()
    {
        return $this->repairLinks();
    }

    public function getNotesAttribute()
    {
        return $this->repairNotes();
    }

    public function getLatestNoteAttribute()
    {
        return $this->repairNotes()->latest()->first();
    }

    public function getBarriersAttribute()
    {
        return $this->repairBarriers()->get();
    }

    public function getStatusCodeAttribute()
    {
        return $this->status;
    }

    public function getRepairStatusAttribute()
    {
        return optional($this->completedRepairStatus)->code ?? null;
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function scopeInProgress(Builder $query)
    {
        return $query->where('status', self::STATUS_IN_REPAIR);
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Add a new note entry to a repair log
     *
     * @param $content
     */
    public function addNote($content, Person $admin = null): void
    {
        if ($content) {
            $note = new RepairLogNote();
            $note->content = $content;
            $note->repairLog()->associate($this);
            if ($admin) {
                $note->person()->associate($admin);
            } else {
                $note->person()->associate($this->person);
            }
            $note->save();
        }
    }

    /**
     * Edit a note linked to a repair note
     *
     * @param $id
     * @param $content
     */
    public function editNote($id, $content, Person $admin = null): void
    {
        $note = $this->repairNotes()->where('id', $id)->first();
        if (!$note) {
            $note = new RepairLogNote();
        }
        if (!$note->person) {
            if ($admin) {
                $note->person()->associate($admin);
            } else {
                $note->person()->associate($this->person);
            }
        }
        if (!$note->repairLog) {
            $note->repairLog()->associate($this);
        }

        $note->content = $content;
        $note->save();
    }

    public function isFixed()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function syncStatus($force = false)
    {
        if ($this->isDirty('status') || $force) {
            $this->device->latest_status = $this->status;
            $this->device->save();
        }

        if ($this->isDirty('completed_repair_status_id') || $force) {
            $this->device->completed_repair_status_id = $this->completed_repair_status_id;
            $this->device->save();
        }
    }
}
