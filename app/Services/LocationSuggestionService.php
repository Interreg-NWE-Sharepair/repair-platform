<?php

namespace App\Services;

use App\Http\Services\MailService;
use App\Models\ContactDetail;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\LocationSuggestion;
use App\Models\Organisation;
use App\Models\OrganisationLocale;
use App\Models\OrganisationType;
use Illuminate\Support\Facades\DB;
use Throwable;

class LocationSuggestionService
{
    public function createNewLocationSuggestion($data, Location $location = null)
    {
        try {
            DB::beginTransaction();
            $locationSuggestion = new LocationSuggestion();
            $locationSuggestion->original_details = $data;
            $locationSuggestion->name = $data['name'];
            $locationSuggestion->description = $data['description'] ?? null;
            $locationSuggestion->product_description = $data['product_description'] ?? null;
            $locationSuggestion->has_warranty = $data['has_warranty'];
            $locationSuggestion->warranty_info = $data['warranty_info'] ?? null;
            $locationSuggestion->address = $data['address'];
            $organisationType = OrganisationType::where('uuid', $data['organisation_type'])->first();
            $locationSuggestion->organisationType()->associate($organisationType);

            $deviceTypes = [];
            foreach ($data['device_types'] ?? [] as $deviceType) {
                //Only search child device Types, parents should not be added.
                $deviceTypes[] = optional(DeviceType::where('uuid', $deviceType)->whereNotNull('parent_id')
                                                    ->first())->id;
            }
            $locationSuggestion->deviceTypes()->attach(array_filter($deviceTypes));

            if ($data['logo'] ?? null) {
                $locationSuggestion->addMediaFromBase64($data['logo'])->toMediaCollection('logo');
            }
            foreach ($data['images'] ?? [] as $image) {
                if ($image) {
                    $locationSuggestion->addMediaFromBase64($image)->toMediaCollection('images');
                }
            }

            $activitySectors = [];
            foreach ($data['activity_sectors'] ?? [] as $activitySector) {
                $activitySectors[] = optional(DeviceType::where('uuid', $activitySector)->first())->id;
            }
            $locationSuggestion->activitySectors()->attach(array_filter($activitySectors));

            $locationContacts = [];
            foreach ($data['contacts'] ?? [] as $contactType => $contacts) {
                if (in_array($contactType, ContactDetail::TYPES)) {
                    foreach ($contacts as $contact) {
                        if ($contact && isset($contact['value'])) {
                            $locationContacts[] = [
                                'name' => $contact['name'] ?? $contact['value'],
                                'value' => $contact['value'],
                                'type' => $contactType,
                            ];
                        }
                    }
                }
            }
            $locationSuggestion->contacts = $locationContacts;
            $locationSuggestion->locales = $data['locales'];

            $locationSuggestion->submitter_email = $data['submitter_email'];
            $locationSuggestion->submitter_relation = $data['submitter_relation'] ?? null;

            if ($location) {
                $locationSuggestion->location()->associate($location);
            }

            $locationSuggestion->save();

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        $mailService = new MailService();
        $mailService->sendNewLocationSuggestionMail($locationSuggestion);

        return $locationSuggestion;
    }

    public function approveLocationSuggestion(LocationSuggestion $locationSuggestion)
    {
        try {
            DB::beginTransaction();

            $location = $locationSuggestion->location ?? new Location;
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $logo */
            $logo = $locationSuggestion->getMedia('logo')->first();
            $images = $locationSuggestion->getMedia('images');

            $location->name = $locationSuggestion->name;
            $location->description = $locationSuggestion->description;
            $location->is_visible = true;

            $location->street = $locationSuggestion->address['street'] ?? null;
            $location->number = $locationSuggestion->address['number'] ?? null;
            $location->bus = $locationSuggestion->address['bus'] ?? null;
            $location->postal_code = $locationSuggestion->address['postal_code'] ?? null;
            $location->city = $locationSuggestion->address['city'] ?? null;
            $location->country = $locationSuggestion->address['country'] ?? null;
            $location->save();

            $newOrganisation = false;
            $organisation = $location->organisation;
            if (!$organisation) {
                $organisation = new Organisation;
                $organisation->name = $locationSuggestion->name;
                $organisation->description = $locationSuggestion->description;
                $newOrganisation = true;
            }

            $organisation->product_description = $locationSuggestion->product_description;
            $organisation->has_warranty = $locationSuggestion->has_warranty;
            $organisation->warranty_description = $locationSuggestion->warranty_info;
            $organisation->is_visible = true;
            $organisation->organisationType()->associate($locationSuggestion->organisationType);
            $organisation->save();

            $organisation->deviceTypes()->sync($locationSuggestion->device_types, true);
            $organisation->activitySectors()->sync($locationSuggestion->activity_sectors, true);
            $organisation->save();

            if ($newOrganisation && $logo) {
                $organisation->clearMediaCollection('logo');
                $logo->copy($organisation, 'logo');
            }

            $contactDetails = [];
            foreach ($locationSuggestion['contacts'] as $contact) {
                $contactDetail = new ContactDetail();
                $contactDetail->fill($contact);
                $contactDetails[] = $contactDetail;
            }
            $organisation->contactDetails()->delete();
            $organisation->contactDetails()->saveMany($contactDetails);

            $organisationLocales = [];
            foreach ($locationSuggestion['locales'] as $locale) {
                $organisationLocale = new OrganisationLocale();
                $organisationLocale->locale = $locale;
            }
            $organisation->contacts()->delete();
            $organisation->contacts()->saveMany($organisationLocales);

            $location->organisation()->associate($organisation);
            $location->save();

            $location->clearMediaCollection('logo');
            $location->clearMediaCollection('images');
            if ($logo) {
                $logo->move($location, 'logo');
            }
            foreach ($images as $image) {
                if ($image) {
                    $image->move($location, 'images');
                }
            }

            $locationSuggestion->approved_at = now();
            $locationSuggestion->save();

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $location;
    }
}
