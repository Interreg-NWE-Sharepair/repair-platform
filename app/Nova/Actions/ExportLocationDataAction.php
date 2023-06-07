<?php

namespace App\Nova\Actions;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportLocationDataAction extends DownloadExcel
{
    use InteractsWithQueue, Queueable;

    public function query()
    {
        $query = Location::query();
        if (!$this->request->all()['resources'] === 'all') {
            $locations = explode(',', $this->request->all()['resources']);
            $query->whereIn('id', $locations);
        }

        $query->with([
            'organisation',
            'organisation.deviceTypes',
            'organisation.contactDetails',
            'organisation.organisationType',
            'contactDetails',
        ]);

        return $query;
    }

    public function map($location): array
    {
        /**
         * @var Location $location
         */
        $organisation = $location->organisation;
        $locationDetails = $location->contactDetails()->pluck('value')->implode('value', ',');
        $organisationDetails = '/';
        $organisationDeviceTypes = '/';
        if ($organisation) {
            $organisationDetails = $organisation->contactDetails()->pluck('value')->implode( ',');
            $organisationDeviceTypes = $organisation->deviceTypes()->pluck('name')->implode( ',');
        }

        return [
            $location->name,
            $location->description,
            $location->street,
            $location->number,
            $location->bus,
            $location->postal_code,
            $location->city,
            $location->country,
            $location->country_code,
            $location->latitude,
            $location->longitude,
            $locationDetails,
            optional($organisation)->name,
            optional($organisation)->description,
            ($organisation && $organisation->organisationType) ? $organisation->organisationType->name : '/',
            optional($organisation)->responsible_group,
            $organisationDeviceTypes ?? null,
            $organisationDetails ?? null,
        ];
    }

    public function headings(): array
    {

        return [
            'name',
            'description',
            'street',
            'number',
            'bus',
            'postal_code',
            'city',
            'country',
            'country_code',
            'latitude',
            'longitude',
            'contact_details',
            'organisation_name',
            'organisation_description',
            'organisation_type',
            'responsible_group',
            'organisation_device_types',
            'organisation_contact_details',
        ];
    }
}
