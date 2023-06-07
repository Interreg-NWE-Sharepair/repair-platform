<?php

namespace App\Http\Controllers\Statistics;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\OrganisationRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LocationCollection;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Location;
use App\Models\OrganisationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use function React\Promise\all;

class StatisticsController extends Controller
{
    public function index()
    {
        \SEO::setTitle('Repair Statistics');

        $parameters = request()->query();
        $locale = app()->getLocale();

        //If a location is provided and the organisation is missing
        if (isset($parameters['location']) && !isset($parameters['organisation'])) {
            //We use this location to find the corresponding organisation
            $location = Location::whereUuid($parameters['location'])->first();
            if ($location && $location->organisation) {
                $parameters['organisation'] = $location->organisation->uuid ?? null;
            }
        }

        if (isset($parameters['organisation'])) {
            $cacheKey = 'impact-' . $locale . '-' . $parameters['organisation'] . 'view-organisation';
            $data = Cache::remember($cacheKey, now()->addHour(), function () use ($parameters) {
                $data = $this->getByOrganisation($parameters);
                return json_encode($data, JSON_THROW_ON_ERROR);
            });

            if (!is_array($data)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
                $data['organisation'] = OrganisationRepository::findByCode($data['organisation']['uuid'])->first();
            }

            return view('repsta.view-organisation', ['data' => $data]);
        }

        if (isset($parameters['event'])) {
            $cacheKey = 'impact-' . $locale . '-' . $parameters['event'] . 'view-event';
            $data = Cache::remember($cacheKey, now()->addHour(), function () use ($parameters) {
                $data = $this->getByEvent($parameters);
                return json_encode($data, JSON_THROW_ON_ERROR);
            });

            if (!is_array($data)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
                $data['event'] = EventRepository::getBySlug($data['event']['slug'])->first();
            }

            return view('repsta.view-event', ['data' => $data]);
        }

        if (isset($parameters['bbox'])) {
            $bbox = collect(explode(',', $parameters['bbox']))->map(function ($item) {
                return Str::substr(Str::reverse($item), 0, 4);
            })->implode('');
            $cacheKey = 'impact-' . $locale . '-' . $bbox . 'view-bbox';
            $data = Cache::remember($cacheKey, now()->addHour(), function () {
                $data = $this->getByCoordinates();
                return json_encode($data, JSON_THROW_ON_ERROR);
            });

            if (!is_array($data)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            }

            $bboxUrl = $parameters['bbox'] ?? null;
            $baseUrl = $parameters['base_url'] ?? null;
            $cta1 = $parameters['cta1'] ?? 'https://mapping.sharepair.org';
            $cta2 = $parameters['cta2'] ?? 'https://mapping.sharepair.org';
            $cta1OrganisationType = $parameters['cta1_organisation_type'] ?? null;
            $cta2OrganisationType = $parameters['cta2_organisation_type'] ?? null;

            if ($bbox) {
                $cta1 .= '?bbox=' . $bboxUrl;
                $cta2 .= '?bbox=' . $bboxUrl;
            }

            if (isset($parameters['header'])) {
                $image = $parameters['image'] ?? null;
                return view('repsta.city-header', ['data' => $data, 'image' => $image]);
            }
            return view('repsta.view-coordinates', ['data' => $data, 'bbox' => $bboxUrl, 'baseUrl' => $baseUrl, 'cta1' => $cta1, 'cta2' => $cta2, 'cta1OrganisationType' => $cta1OrganisationType, 'cta2OrganisationType' => $cta2OrganisationType]);
        }

        $cacheKey = $locale . '-' . 'impact-general-view';
        $data = Cache::remember($cacheKey, now()->addHour(), function () {
            $data = $this->getGeneralStats();
            return json_encode($data, JSON_THROW_ON_ERROR);
        });

        if (!is_array($data)) {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        }

        return view('repsta.view-general', ['data' => $data]);
    }

    private function getByOrganisation($parameters)
    {
        $uuid = $parameters['organisation'];
        $locale = app()->getLocale();
        $organisation = OrganisationRepository::findByCode($uuid)->firstOrFail();

        $data['organisation'] = $organisation;
        $data['repairers'] = EmployeeRepository::countByOrganisation($organisation);
        $data['events'] = EventRepository::countByOrganisation([$organisation->uuid]);

        $data['devices'] = [
            'registered' => DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid])),
            'repaired' => DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'repaired')),
            'end_of_life' => DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'end_of_life')),
            'archived' => DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'archived')),
            'to_repair' => DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'open')),
        ];

        $data['device_type_ranking'] = DeviceRepository::getByDeviceType(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'repaired', true), $locale);

        $impact = DeviceRepository::getEcoImpact([$organisation->uuid]);

        return array_merge($data, $impact);
    }

    private function getByCoordinates()
    {

        $locale = app()->getLocale();
        $data = [
            'repairers' => 0,
            'repairCafe' => 0,
            'events' => 0,
            'devices' => [
                'archived' => ['absolute' => 0, 'relative' => 0], 'repaired' => ['absolute' => 0, 'relative' => 0],
                'end_of_life' => ['absolute' => 0, 'relative' => 0], 'total' => ['absolute' => 0]
                ],
            'device_type_ranking' => []
        ];
        $impact = ['total_weight' => 0, 'total_co2' => 0];
        $rankings = collect();

        $cta1OrganisationType = request()->query('cta1_organisation_type');
        $cta2OrganisationType = request()->query('cta2_organisation_type');
        $bbox = parseQueryArray(request()->input('bbox'));

        $organisationTypes = collect([$cta1OrganisationType, $cta2OrganisationType])->filter();
        $organisations = [];
        /** @var Collection $locations */
        // The search function will read the request parameters itself, so don't need to add them in function
        $locations = Location::with('organisation')->whereHas('organisationType', function (Builder $query) use ($organisationTypes) {
            if ($organisationTypes->isNotEmpty()) {
                $query->whereIn('organisation_types.code', $organisationTypes)
                      ->orWhereIn('organisation_types.uuid', $organisationTypes);
            }
        })->bbox($bbox)->get();


        $countProfessionalRepairers = 0;
        $countRepairCafe = 0;

        if ($locations) {
            foreach ($locations as $location) {
                $organisation = $location->organisation;
                if (!$organisation) {
                    continue;
                }
                if (optional($organisation->organisationType)->code === OrganisationType::PROFESSIONAL_REPAIRER) {
                    $countProfessionalRepairers++;
                }
                if (optional($organisation->organisationType)->code === OrganisationType::REPAIR_CAFE) {
                    $countRepairCafe++;
                }
                $organisations[$location->organisation->uuid] = $location->organisation;
            }
        }

        if ($organisations) {
            $organisationKeys = array_keys($organisations);

            $data['repairers'] = $countProfessionalRepairers;
            $data['repairCafe'] = $countRepairCafe;

            $data['events'] += EventRepository::countByOrganisation($organisationKeys);

            // $registered = DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus($organisationKeys));
            $repaired = DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus($organisationKeys, 'repaired'));
            $endOfLife = DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus($organisationKeys, 'end_of_life'));
            $archived = DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus($organisationKeys, 'archived'));
            $total = $repaired + $endOfLife + $archived;


            // (value / total value) * 100
            if ($total > 0) {
                $data['devices'] = [
                    'archived' => ['absolute' => $archived, 'relative' => number_format(($archived / $total) * 100, 0, ',', '.')],
                    'repaired' => ['absolute' => $repaired, 'relative' => number_format(($repaired / $total) * 100, 0, ',', '.')],
                    'end_of_life' => ['absolute' => $endOfLife, 'relative' => number_format(($endOfLife / $total) * 100, 0, ',', '.')],
                    'total' => ['absolute' => $total],
                ];
            } else {
                //Just if really nothing set array
                $data['devices'] = [
                    'archived' => ['absolute' => 0, 'relative' => 0],
                    'repaired' => ['absolute' => 0, 'relative' => 0],
                    'end_of_life' => ['absolute' => 0, 'relative' => 0],
                    'total' => ['absolute' => 0],
                ];
            }

            $data['device_type_ranking'] = DeviceRepository::getByDeviceType(DeviceRepository::queryByOrganisationAndStatus($organisationKeys, 'repaired', true), $locale);

            $organisationImpact = DeviceRepository::getEcoImpact($organisationKeys);

            $impact['total_weight'] += $organisationImpact['total_weight'];
            $impact['total_co2'] += $organisationImpact['total_co2'];
        }

        return array_merge($data, $impact);

    }

    private function getByEvent($parameters)
    {
        $slug = $parameters['event'];
        $locale = app()->getLocale();
        $event = EventRepository::getBySlug($slug)->firstOrFail();
        $data['event'] = $event;
        $data['repairers'] = DeviceRepository::countRepairersByEvent($event)->get()->count();
        $data['devices'] = [];

        $registered = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event));
        $toRepair = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'open'));
        $repaired = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'repaired'));
        $endOfLife = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'end_of_life'));
        $advice = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'advice'));
        $spareParts = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'spare_parts'));
        $resignRepair = DeviceRepository::returnCount(DeviceRepository::queryByEventAndStatus($event, 'resign_repair'));
        $total = $repaired + $endOfLife + $advice + $spareParts + $resignRepair;

        // (value / total value) * 100
        if ($total > 0) {
            $data['devices'] = [
                'registered' => ['absolute' => $registered, 'relative' => number_format(($registered / $total) * 100, 0, ',', '.')],
                'to_repair' => ['absolute' => $toRepair, 'relative' => number_format(($toRepair / $total) * 100, 0, ',', '.')],
                'repaired' => ['absolute' => $repaired, 'relative' => number_format(($repaired / $total) * 100, 0, ',', '.')],
                'end_of_life' => ['absolute' => $endOfLife, 'relative' => number_format(($endOfLife / $total) * 100, 0, ',', '.')],
                //'archived' => ['absolute' => $archived, 'relative' => ($archived / $total) * 100],
                'advice' => ['absolute' => $advice, 'relative' => number_format(($advice / $total) * 100, 0, ',', '.')],
                'spare_parts' => ['absolute' => $spareParts, 'relative' => number_format(($spareParts / $total) * 100, 0, ',', '.')],
                'resign_repair' => ['absolute' => $resignRepair, 'relative' => number_format(($resignRepair / $total) * 100, 0, ',', '.')],
                'total' => ['absolute' => $total],
            ];
        }

        $data['device_type_ranking'] = DeviceRepository::getByDeviceType(DeviceRepository::queryByEventAndStatus($event, 'repaired', true), $locale);

        $impact = DeviceRepository::getEcoImpact([], $event);

        return array_merge($data, $impact);
    }

    private function getGeneralStats()
    {
        $locale = app()->getLocale();
        $data['repairers'] = Employee::query()->active()->type(Employee::TYPE_REPAIRER)->count();
        $data['events'] = Event::query()->past()->count();

        $data['devices'] = [
            'registered' => DeviceRepository::returnCount(DeviceRepository::queryByStatus()),
            'repaired' => DeviceRepository::returnCount(DeviceRepository::queryByStatus('repaired')),
            'end_of_life' => DeviceRepository::returnCount(DeviceRepository::queryByStatus('end_of_life')),
            'archived' => DeviceRepository::returnCount(DeviceRepository::queryByStatus('archived')),
            'to_repair' => DeviceRepository::returnCount(DeviceRepository::queryByStatus('open')),
        ];

        $data['device_type_ranking'] = DeviceRepository::getByDeviceType(DeviceRepository::queryByStatus(null, true), $locale);

        $impact = DeviceRepository::getEcoImpact();

        return array_merge($data, $impact);
    }
}
