<?php

namespace App\Http\Controllers\Replog\Event;

use App\Http\Controllers\Replog\ReplogController;
use App\Models\Device;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class EventController extends ReplogController
{
    public function show($slug)
    {
        $event = Event::query()->where('slug', $slug)->firstOrFail();
        $organisation = $event->organisation;

        $devices = Device::query()->event($event->id)->whereNotNull('event_follow_up_id')->orderBy('event_follow_up_id')->get();

        return Inertia::render('Organisation/Event/EventDetail', [
            'event' => $event,
            'organisation' => $organisation,
            'showOrganizer' => true,
            'devices' => $devices,
            'title' => $event->name,
            'eventAdmin' => $this->isEventOrganizer($organisation),
            'entityAdmin' => $this->isEntityAdmin($organisation),
        ]);
    }
}
