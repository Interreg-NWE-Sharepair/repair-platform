<?php

namespace App\Repository\Api;

use App\Http\Clients\RestartersApiClient;
use App\Models\ContactDetail;
use App\Models\Event;
use App\Models\Organisation;
use App\Repository\RestartersRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestartersRepository implements RestartersRepositoryInterface
{
    const CACHE_GROUPS = 'groups';
    const CACHE_GROUP = 'group';
    const CACHE_GROUP_EVENTS = 'group.events';

    /**
     * @var \App\Http\Clients\RestartersApiClient
     */
    private RestartersApiClient $apiClient;

    public function __construct(RestartersApiClient $apiClient)
    {
        $this->apiClient = $apiClient;

        $this->cacheShortTTL = config('cache.duration.short');
        $this->cacheMediumTTL = config('cache.duration.medium');
        $this->cacheLongTTL = config('cache.duration.long');
    }

    public function getGroups()
    {
        return Cache::remember($this->getCacheKey(self::CACHE_GROUPS), $this->cacheLongTTL, function () {
            return $this->apiClient->getGroups();
        });
    }

    public function getGroup($id, $flush = false)
    {
        $cacheKey = $this->getCacheKey(self::CACHE_GROUP, $id);

        if ($flush) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $this->cacheLongTTL, function () use ($id) {
            return $this->apiClient->getGroupById($id);
        });
    }

    public function getEventsByGroup($id, $flush = false)
    {
        $cacheKey = $this->getCacheKey(self::CACHE_GROUP_EVENTS, $id);

        if ($flush) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $this->cacheLongTTL, function () use ($id) {
            return $this->apiClient->getEventsByGroup($id);
        });
    }

    public function syncOrganisationData(Organisation $organisation)
    {
        $groupId = $organisation->restarters_id;
        $groupData = $this->getGroup($groupId, $flush = true);
        if (!$groupData || empty($groupData)) {
            throw new NotFoundHttpException();
        }

        $this->fillOrganisationByData($organisation, $groupData);
        $organisation->save();

        $events = $this->getEventsByGroup($groupId, $flush = true);
        foreach ($events as $eventData) {
            $restartersEventId = $eventData['id'];
            $event = Event::getByRestartersId($restartersEventId) ?? new Event();
            $event->restarters_id = $restartersEventId;
            $event->organisation()->associate($organisation);
            $this->fillEventByData($event, $eventData);
            $event->save();
        }
    }

    public function fillOrganisationByData(Organisation $organisation, $data)
    {
        $organisation->setLocale('en');
        $organisation->name = $data['name'] ?? '';
        $organisation->setTranslation('description', 'nl', $data['description'] ?? null);
        $organisation->setTranslation('description', 'en', $data['description'] ?? null);
        $organisation->setTranslation('description', 'de', $data['description'] ?? null);
        $organisation->setTranslation('description', 'fr', $data['description'] ?? null);
        $organisation->addMediaFromUrl('https://restarters.net/uploads/'.$data['image'])
//        $organisation->addMediaFromUrl($data['image_url']) //Add again when image field is changed on the api
            ->toMediaCollection('logo');
        $organisation->restarters_data_synced_at = now();

        $organisation->save();
        $organisation->refresh();

        $contactDetails = $organisation->contactDetails;
        if ($data['website'] ?? null) {
            $currentWebsites = $contactDetails->where('type', ContactDetail::TYPE_WEBSITE);
            //The restarters value is not found in the organisation, we will have to add it
            if ($currentWebsites->where('value', $data['website'])->isEmpty()) {
                $contactDetails = new ContactDetail();
                $contactDetails->value = $data['website'];
                $contactDetails->name = $data['website'];
                $contactDetails->type = ContactDetail::TYPE_WEBSITE;
                $organisation->contactDetails()->save($contactDetails);
            }
        }

        if ($data['facebook'] ?? null) {
            $currentFacebookLinks = $contactDetails->where('type', ContactDetail::TYPE_FACEBOOK);
            //The restarters value is not found in the organisation, we will have to add it
            if ($currentFacebookLinks->where('value', $data['facebook'])->isEmpty()) {
                $contactDetails = new ContactDetail();
                $contactDetails->value = $data['facebook'];
                $contactDetails->name = $data['facebook'];
                $contactDetails->type = ContactDetail::TYPE_FACEBOOK;
                $organisation->contactDetails()->save($contactDetails);
            }
        }

        if ($data['phone'] ?? null) {
            $currentPhones = $contactDetails->where('type', ContactDetail::TYPE_PHONE);
            //The restarters value is not found in the organisation, we will have to add it
            if ($currentPhones->where('value', $data['phone'])->isEmpty()) {
                $contactDetails = new ContactDetail();
                $contactDetails->value = $data['phone'];
                $contactDetails->name = $data['phone'];
                $contactDetails->type = ContactDetail::TYPE_PHONE;
                $organisation->contactDetails()->save($contactDetails);
            }
        }
    }


    private function getCacheKey(string $key, $details = null)
    {
        if (!$details) {
            return Str::lower($key);
        }

        if (!is_array($details)) {
            $details = [$details];
        }

        return Str::lower($key . '.' . implode('.', $details));
    }

    public function fillEventByData(Event $event, $eventData)
    {
        $event->setLocale('en');
        $event->name = $eventData['title'];
        $start = Carbon::parse($eventData['start'])->tz($eventData['timezone']);
        $end = Carbon::parse($eventData['end'])->tz($eventData['timezone']);

        $event->date_start = $start;
        $event->time_start = $start->format('H:i:s');
        $event->time_stop = $end->format('H:i:s');
        $event->timezone = $eventData['timezone'];
        $event->is_online = $eventData['online'];
        $event->description = $eventData['description'] ?? '';
        $event->address = $eventData['location'] ?? '';
    }
}
