<?php

namespace App\Http\Livewire;

use App\Models\CommonDeviceTypeIssue;
use App\Models\DeviceType;
use App\Models\RepairTutorial;
use App\Services\OrdpApiService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Tutorials extends Component
{
    use WithPagination;

    private $query = null;

    public $search = '';

    public $type = '';

    public $faults = [];

    public $ordpSearch = '';

    public $filters = true;
    public $searchSetting = true;
    public $externalGuides = true;

    public $target = '';

    public $locale = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'type'   => ['except' => ''],
        'faults' => ['except' => ''],
        'page'   => ['except' => 1],
        'filters' => ['except' => true],
        'searchSetting' => ['except' => true],
        'externalGuides' => ['except' => true],
        'target' => ['except' => ''],
        'locale' => ['except' => '']
    ];

    private ?string $params;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->reset('faults');
        $this->resetPage();
    }

    public function updatingFaults(): void
    {
        $this->resetPage();
    }

    public function search(): void
    {
        if (!$this->query) {
            $this->query = RepairTutorial::query();
        }

        $this->updateSearchQuery();
    }

    private function updateSearchQuery(): void
    {
        $locale = app()->getLocale();

        if ($this->search) {
            $this->query->where(function ($query) use ($locale) {
                $query->where(DB::raw("lower(title->'$.$locale')"), 'like', '%' . strtolower($this->search) . '%')
                      ->orWhere(DB::raw("lower(description->'$.$locale')"), 'like', '%' . strtolower($this->search) . '%')
                      ->orWhere(DB::raw("lower(content->'$.$locale')"), 'like', '%' . strtolower($this->search) . '%')
                      ->orWhereHas('deviceType', function ($q) use ($locale) {
                          $q->where(DB::raw("lower(name->'$.$locale')"), 'like', '%' . strtolower($this->search) . '%');
                      });
            });
        }
    }

    public function render(): Factory|View|Application
    {
        $deviceTypes = $this->getDeviceTypeOptions();
        $deviceTypeIssues = $this->getCommonDeviceTypeIssues();
        if (!$deviceTypeIssues) {
            $this->reset(['faults']);
        }

        if (!$this->query) {
            $this->query = RepairTutorial::query();
        }

        $this->updateSearchQuery();

        if ($this->type) {
            $this->query->whereHas('deviceType', function ($q) {
                $q->where('uuid', $this->type);
            });
        }

        if ($this->faults) {
            $this->query->whereHas('commonDeviceTypeIssue', function (Builder $q) {
                $q->whereIn('id', array_values($this->faults));
            });
        }

        $this->getqueryParams();


        return view('livewire.tutorials', [
            'tutorials'        => $this->query->paginate(9),
            'deviceTypes'      => $deviceTypes,
            'deviceTypeIssues' => ($deviceTypeIssues && $deviceTypeIssues->isNotEmpty()) ? $deviceTypeIssues : null,
            'ordpGuides'       => $this->getOrdpTutorials(),
            'ordpGuideString' => $this->getOrdpGuideString(),
            'params' => $this->params
        ]);
    }

    private function getOrdpTutorials()
    {
        $tutorials = null;
        $deviceType = DeviceType::query()->where('uuid', $this->type)->first();
        $searchArray[] = $deviceType?->getTranslation('name', 'en');
        $search = implode(' ', $searchArray);
        if (!empty($search)) {
            $this->ordpSearch = $search;
            $tutorials = (new OrdpApiService)->getGuidelines($search);
            foreach ($tutorials as $index => $tutorial) {
                $tutorial['class'] = 'ordp';
            }
        }

        return $tutorials;
    }

    private function getDeviceTypeOptions(): array
    {
        $options = [];
        $deviceTypes = DeviceType::visible()->with('parent')->orderBy('id')->whereHas('repairTutorials')->get();
        if ($deviceTypes) {
            foreach ($deviceTypes as $deviceType) {
                if (!$deviceType->parent) {
                    continue;
                }
                if (!isset($options[$deviceType->parent->uuid])) {
                    $options[$deviceType->parent->uuid]['name'] = $deviceType->parent->name;
                }

                $options[$deviceType->parent->uuid]['options'][] = $deviceType;
            }
        }

        return $options;
    }

    private function getCommonDeviceTypeIssues()
    {
        $deviceTypeIssues = null;

        if ($this->type) {
            $deviceTypeIssues = CommonDeviceTypeIssue::query()->whereHas('deviceType', function ($q) {
                $q->where('uuid', $this->type);
            })->get();
        }

        return $deviceTypeIssues;
    }

    private function getOrdpGuideString(): ?string
    {

        if ($this->type) {
            $searchArray[] = optional(DeviceType::query()->where('uuid', $this->type)->first())->getTranslation('name', 'en');

            return implode(' ', $searchArray);
        }

        return null;
    }

    private function getqueryParams() {
        $this->params = request()->getQueryString();
    }
}
