<?php

namespace App\Http\Controllers\Replog\Api;

use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\PersonRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Models\Employee;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class RepairerController extends ReplogController
{
    /**
     * @param $event
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function isAttendingEvent($event)
    {
        /** @var \App\Models\Event $event */
        $event = $this->hasEvent($event);
        if (!$event) {
            return response()->json([
                'isAttending' => false,
                'message' => 'Event not found',
            ]);
        }

        /** @var Employee $employee */
        $employee = $this->hasRepairer($event);
        if (!$employee) {
            return response()->json([
                'isAttending' => false,
                'message' => 'Repairer not found',
            ]);
        }

        $isAttending = $this->isAttending($employee->person, $event);
        if ($isAttending) {
            return response()->json(['isAttending' => true]);
        }
    }

    public function eventStatusSwitch($event)
    {
        $event = $this->hasEvent($event);
        if (!$event) {
            return response()->json([
                'isAttending' => false,
                'message' => 'Event not found',
            ]);
        }

        /** @var Employee $employee
         */
        $employee = $this->hasRepairer($event);

        if (!$employee) {
            return response()->json([
                'isAttending' => false,
                'message' => 'Repairer not found',
            ]);
        }

        $person = $employee->person;
        $isAttending = PersonRepository::attendsEvent($person, $event)->exists();
        if ($isAttending) {
            $person->events()->detach($event->id);

            return response()->json(['isAttending' => false]);
        }

        $person->events()->attach($event->id);

        return response()->json(['isAttending' => true]);
    }

    private function hasEvent($event)
    {
        return EventRepository::find($event)->first();
    }

    /**
     * @param \App\Models\Event $event
     * @return null
     */
    private function hasRepairer(Event $event)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        return EmployeeRepository::getByUser($user)->type(Employee::TYPE_REPAIRER)->organisation($event->organisation)
                                 ->first();
    }

    private function isAttending($person, $event): bool
    {
        return PersonRepository::attendsEvent($person, $event)->exists();
    }
}
