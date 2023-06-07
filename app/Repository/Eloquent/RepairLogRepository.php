<?php

namespace App\Repository\Eloquent;

use App\Facades\PersonRepository;
use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\ImageCategory;
use App\Models\Person;
use App\Models\RepairBarrier;
use App\Models\RepairLog;
use App\Repository\RepairLogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RepairLogRepository extends BaseRepository implements RepairLogRepositoryInterface
{
    public function __construct(RepairLog $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model::all();
    }

    public function findByUuid($uuid): RepairLog
    {
        return $this->model::query()->where('uuid', $uuid)->with([
            'person',
            'device',
        ])->firstOrFail();
    }

    public function hasDevicesAssigned(Person $person, array $repairStatuses): Builder
    {
        return $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('id', $person->id);
        })->whereIn('repair_logs.status', $repairStatuses);
    }

    public function hasMaxAmountDevices(Person $person): bool
    {
        $amountSelectedDevices = $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('id', $person->id);
        })->where('status', RepairLog::STATUS_IN_REPAIR)->count();

        return $amountSelectedDevices >= RepairLog::MAX_ASSIGNED_LOGS;
    }

    /**
     * @param \App\Models\Device $device
     * @param \App\Models\Person $person
     * @param \App\Models\RepairLog|null $oldLog
     * @return \App\Models\RepairLog
     */
    public function createLog(Device $device, Person $person, RepairLog $oldLog = null, $data = null): RepairLog
    {
        if ($oldLog) {
            $log = $oldLog->replicate();
            $log->uuid = null;

            // IF OLD LOG IS IN REPAIR, FIRST CHANGE STATUS TO STILL BROKEN (just to keep everything in line with normal flow)
            if ($oldLog->status === RepairLog::STATUS_IN_REPAIR) {
                $oldLog->status = RepairLog::STATUS_REOPENED;
                $oldLog->save();
            }
        } else {
            $log = new RepairLog();
        }
        $log->person()->associate($person);
        $log->device()->associate($device);

        $log->status = RepairLog::STATUS_IN_REPAIR;

        $log->save();
        $log->refresh();

        if ($oldLog) {
            $media = $oldLog->media;
            if ($media) {
                foreach ($media as $item) {
                    /** @var Media $item */
                    $item->model()->associate($log);
                    $item->save();
                }
            }
            $repairNotes = $oldLog->repairNotes;
            if ($repairNotes) {
                foreach ($repairNotes as $note) {
                    /** @var \App\Models\RepairLogNote $note */
                    $note->repairLog()->associate($log);
                    $note->save();
                }
            }


            if (isset($data['reopen_note']) && $data['reopen_note']) {
                $log->addNote($data['reopen_note']);
            }

            //When creating new log with taking old data, do not take repair barriers because they belong to previous log
           /* $repairBarriers = $oldLog->repairBarriers;
            if ($repairBarriers) {
                foreach ($repairBarriers as $barrier) {
                    $log->repairBarriers()->attach($barrier->id);
                }
            }*/
        }

        return $log;
    }

    /**
     */
    public function updateLog(RepairLog $repairLog, $data, $isAdmin = null): void
    {
        if (!empty($data['used_materials'])) {
            $data['is_using_spare_parts'] = true;
        }

        $currentRepairNotes = $repairLog->repairNotes()->pluck('id')->all();
        if (isset($currentRepairNotes)) {
            $currentRepairNotes = collect($currentRepairNotes);
        }
        $savedNoteIds = [];

        $person = null;
        if ($isAdmin) {
            $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
        }
        if (isset($data['notes'])) {
            foreach ($data['notes'] as $note) {
                if (!isset($note['id'])) {
                    $repairLog->addNote($note['content'], $person);
                } else {
                    $savedNoteIds[] = $note['id'];
                    $repairLog->editNote($note['id'], $note['content']);
                }
            }
        }

        $savedNoteIds = collect($savedNoteIds);
        $diff = $currentRepairNotes->diff($savedNoteIds);
        if ($diff) {
            $oldNotes = $repairLog->repairNotes()->whereIn('id', $diff)->get();
            if ($oldNotes) {
                foreach ($oldNotes as $oldNote) {
                    $oldNote->delete();
                }
            }
        }

        $repairLog->fill($data);
        $repairLog->save();
    }

    public function reopenDevice(RepairLog $repairLog, $data): void
    {
        if ($data['reopen_note']) {
            $repairLog->addNote($data['reopen_note']);
        }

        if ($data['note']) {
            $repairLog->addNote($data['note']);
        }

        if (isset($data['notes'])) {
            foreach ($data['notes'] as $note) {
                if (!isset($note['id'])) {
                    $repairLog->addNote($note['content']);
                } else {
                    $repairLog->editNote($note['id'], $note['content']);
                }
            }
        }

        $repairLog->status = RepairLog::STATUS_REOPENED;

        $repairLog->fill($data);

        $repairLog->save();
    }

    public function closeLog(RepairLog $repairLog, $data): void
    {
        if (isset($data['note'])) {
            $repairLog->addNote($data['note']);
        }

        if (isset($data['notes'])) {
            foreach ($data['notes'] as $note) {
                if (!isset($note['id'])) {
                    $repairLog->addNote($note['content']);
                } else {
                    $repairLog->editNote($note['id'], $note['content']);
                }
            }
        }

        $repairLog->status = RepairLog::STATUS_COMPLETED;
        $completedRepairStatus = CompletedRepairStatus::where('code', $data['completed_status'])->first();
        $repairLog->completedRepairStatus()->associate($completedRepairStatus);

        if (isset($data['archive_reason'])) {
            $repairBarrier = RepairBarrier::query()->findOrFail($data['archive_reason']);
            $repairLog->repairBarriers()->attach($repairBarrier->id);
        }

        if (isset($data['repair_barrier'])) {
            $repairBarrier = RepairBarrier::query()->findOrFail($data['repair_barrier']);
            $repairLog->repairBarriers()->attach($repairBarrier->id);
        }

        $repairLog->fill($data);

        $repairLog->save();
    }

    /**
     */
    public function updateDevice(RepairLog $repairLog, $data): void
    {
        $device = $repairLog->device;

        $device->fill($data);

        $device->save();
    }
}
