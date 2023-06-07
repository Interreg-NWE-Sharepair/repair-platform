<?php

namespace App\Repository\Eloquent;

use App\Facades\EmployeeRepository;
use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\Event;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\RepairBarrier;
use App\Models\RepairLog;
use App\Models\Role;
use App\Repository\DeviceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * The Device model uses a Global Scope (TempScope)
 * This will remove all the newly registered devices that never got past step 1 in the registration process
 */
class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    /**
     * @param \App\Models\Device $model
     */
    public function __construct(Device $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model::query()->get();
    }

    public function getBySlug($slug): Builder
    {
        return $this->model::query()->where('slug', $slug);
    }

    public function getActive($uuid = null, $all = false): Builder
    {
        if (!$uuid && !$all) {
            $person = \App\Facades\PersonRepository::getByUser(auth()->user())->firstOrFail();
            $employee = \App\Facades\EmployeeRepository::getByPerson($person)->firstOrFail();
            $uuid = $employee->organisation->uuid;
        }

        $query = $this->model::query()->select('devices.*')->with([
            'deviceType',
            'repairLog',
            'event',
        ]);

        if (!$all) {
            $query->organisation($uuid);
        }

        return $query;
    }

    public function getFixedPersonOrganisation(Person $person, $uuid): Builder
    {
        return $this->model::query()->with([
            'repairLog',
            'event',
        ])->organisation($uuid)->fixed($person);
    }

    public function getLastRepairStatus($statuses, $person, $location): Builder
    {
        return $this->model->whereIn('latest_status', $statuses)->organisation($location->uuid)->whereHas(
                'repairLog',
                function ($query) use ($person) {
                    return $query->where('person_id', $person->id);
                }
            );
    }

    public function getLastNewDevices($uuid, $limit = 5): Builder
    {
        return $this->model::query()->stillBroken()->organisation($uuid)->limit($limit)->orderByDesc(
                'devices.created_at'
            );
    }

    /**
     * Filter devices based on the selected parameters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $fixedOnly
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(Builder $query, $fixedOnly = false): Builder
    {
        $request = request();
        $event = $request->query('event');
        if ($event) {
            $query->where(function (Builder $q) use ($event) {
                $q->whereHas('event', function ($q) use ($event) {
                    $q->where('id', $event);
                });
            });
        }
        $category = $request->query('type');
        if (is_array($category)) {
            $query->whereIn('device_type_id', $category);
        }
        if (!$fixedOnly) {
            $statuses = $request->query('status') ?? [];
            if (count($statuses) > 0) {
                $query->whereIn('latest_status', $statuses);
            }
        } else {
            $query->where('latest_status', RepairLog::STATUS_COMPLETED);
        }
        $searchTerm = $request->query('search');
        $lang = $request->query('lang');
        if ($searchTerm) {
            $terms = collect([
                'id',
                'brand_name',
                'model_name',
                'device_description',
                'issue_description',
                "deviceType.name->$lang",
                'repairLog.fix_description',
                'repairLog.diagnosis',
                'repairLog.root_cause',
                'repairLog.repairNotes.content',
            ]);
            $employee = EmployeeRepository::getByUser(Auth::user())->firstOrFail();
            if ($employee->hasAnyRole([
                Role::ENTITY_ADMIN,
                Role::EVENT_ORGANIZER,
                Role::ADMIN,
                Role::STATIK,
            ])) {
                $terms->add('first_name')->add('last_name')->add('email');
            }
            $query::whereLike($query, $terms->toArray(), $searchTerm)->get();
        }
        $order = $request->query('sort');
        if ($order && !$fixedOnly) {
            if ($order === 'desc') {
                return $query->orderByDesc('devices.created_at');
            }
            if ($order === 'status') {
                return $query->statusOrder();
            }
        }

        return $query->orderBy('devices.created_at');
    }

    public function updateContact(Device $device, $data): void
    {
        $device->first_name = $data['first_name'];
        $device->last_name = $data['last_name'];
        $device->email = $data['email'];
        $device->telephone = $data['telephone'];

        $device->save();
    }

    public function queryByOrganisationAndStatus(array $organisations, $status = null, $grouped = false): Builder
    {
        $query = $this->model::query();
        $query->whereHas('organisation', function($q) use ($organisations) {
            $q->whereIn('uuid', $organisations);
        });

        if ($status) {
            if ($status === 'repaired') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->whereIn('code', [
                        CompletedRepairStatus::STATUS_FIXED,
                        CompletedRepairStatus::STATUS_NEVER_DEFECT,
                    ]);
                });
            }
            if ($status === 'end_of_life') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_END_OF_LIFE);
                });
            }
            if ($status === 'archived') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
            }
            if ($status === 'open') {
                $query->whereIn('latest_status', [
                    'open',
                    'in_repair',
                    'reopened',
                ]);
            }
        }
        if ($grouped) {
            $query->with('deviceType')->join('device_types', 'device_types.id', '=', 'devices.device_type_id');

            return $query;
        }

        return $query;
    }

    public function queryByEventAndStatus(Event $event, $status = null, $grouped = false): Builder
    {
        $query = $this->model::query();
        $query->event($event->id);

        if ($status) {
            if ($status === 'repaired') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->whereIn('code', [
                        CompletedRepairStatus::STATUS_FIXED,
                        CompletedRepairStatus::STATUS_NEVER_DEFECT,
                    ]);
                });
            }
            if ($status === 'end_of_life') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_END_OF_LIFE);
                });
            }
            if ($status === 'archived') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
            }
            if ($status === 'advice') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
                $query->whereHas('repairLog', function ($q) {
                    $q->whereHas('repairBarriers', function ($q) {
                        $q->where('code', RepairBarrier::ADVICE_GIVEN_TO_OWNER);
                    });
                });
            }
            if ($status === 'spare_parts') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
                $query->whereHas('repairLog', function ($q) {
                    $q->whereHas('repairBarriers', function ($q) {
                        $q->where('code', RepairBarrier::BARRIER_OWNER_BUYS_SPARE_PARTS);
                    });
                });
            }
            if ($status === 'resign_repair') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
                $query->whereHas('repairLog', function ($q) {
                    $q->whereHas('repairBarriers', function ($q) {
                        $q->where('code', RepairBarrier::BARRIER_USER_ABANDONED_REPAIR);
                    });
                });
            }
            if ($status === 'open') {
                $query->whereIn('latest_status', [
                    'open',
                    'in_repair',
                    'reopened',
                ]);
            }
        }
        if ($grouped) {
            $query->with('deviceType')->join('device_types', 'device_types.id', '=', 'devices.device_type_id');

            return $query;
        }

        return $query;
    }

    public function queryByStatus($status = null, $grouped = false): Builder
    {
        $query = $this->model::query();

        if ($status) {
            if ($status === 'repaired') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->whereIn('code', [
                        CompletedRepairStatus::STATUS_FIXED,
                        CompletedRepairStatus::STATUS_NEVER_DEFECT,
                    ]);
                });
            }
            if ($status === 'end_of_life') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_END_OF_LIFE);
                });
            }
            if ($status === 'archived') {
                $query->where('latest_status', 'completed');
                $query->whereHas('completedRepairStatus', function ($q) {
                    $q->where('code', CompletedRepairStatus::STATUS_ARCHIVE);
                });
            }
            if ($status === 'open') {
                $query->whereIn('latest_status', [
                    'open',
                    'in_repair',
                    'reopened',
                ]);
            }
        }
        if ($grouped) {
            $query->with('deviceType')->join('device_types', 'device_types.id', '=', 'devices.device_type_id');

            return $query;
        }

        return $query;
    }

    public function returnCount(Builder $query): int
    {
        return $query->count();
    }

    public function getByDeviceType(Builder $query, $locale)
    {
        $query->groupBy('device_type_id')->selectRaw(
                "COUNT(devices.id) as total, JSON_EXTRACT(device_types.name, '$.$locale') as name"
            )->orderByDesc('total')->limit(3);
        $collection = $query->get();
        if ($collection instanceof Collection) {
            $data = [];
            foreach ($collection as $index => $item) {
                $data[$index + 1] = [
                    'total' => $item->total,
                    'name' => str_replace('"', '', $item->name),
                ];
            }

            return $data;
        }

        return [];
    }

    public function getEcoImpact(array $organisations = [], Event $event = null): array
    {
        $data = [];
        $totalRepairWeight = 0;
        $totalCo2Weight = 0;
        $query = $this->model::query()->with('deviceType');
        $query->selectRaw('COUNT(devices.id) as total, devices.*');
        if ($organisations) {
            $query->whereHas('organisation', function ($q) use ($organisations) {
                $q->whereIn('uuid', $organisations);
            });

        }
        if ($event) {
            $query->event($event->id);
        }
        $query->where('latest_status', 'completed');
        $query->whereHas('completedRepairStatus', function ($q) {
            $q->whereIn('code', [
                CompletedRepairStatus::STATUS_FIXED,
                CompletedRepairStatus::STATUS_NEVER_DEFECT,
            ]);
        });

        $query->groupBy('device_type_id');
        $devices = $query->get();

        if (!$devices) {
            return [
                'total_weight' => 0,
                'total_co2' => 0,
            ];
        }
        foreach ($devices as $device) {
            $totalRepairWeight += ($device->total * $device->deviceType->product_weight_kg);
            $totalCo2Weight += ($device->total * $device->deviceType->product_co_kg) * $device->deviceType->displacement_rate;
        }

        $data['total_weight'] = $totalRepairWeight; //number_format($totalRepairWeight, 0, ',', '.');
        $data['total_co2'] = $totalCo2Weight; //number_format($totalCo2Weight, 0, ',', '.');

        return $data;
    }

    public function countRepairersByEvent(Event $event): Builder
    {
        $query = $this->model::query();
        $query->selectRaw('person_id');
        $query->join('repair_logs', 'device_id', '=', 'devices.id', 'left');
        $query->event($event->id);
        $query->whereNotNull('repair_logs.person_id');

        $query->groupBy('repair_logs.person_id');

        return $query;
    }
}
