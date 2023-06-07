<?php

namespace App\Repository\Eloquent;

use App\Models\ContactDetail;
use App\Models\Event;
use App\Models\EventPeople;
use App\Models\Person;
use App\Repository\PersonRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonRepository extends BaseRepository implements PersonRepositoryInterface
{
    public function __construct(Person $model)
    {
        parent::__construct($model);
    }

    public function getByUser($user): Builder
    {
        return $this->model::query()->whereHas('user', function (Builder $q) use ($user) {
            $q->where('id', $user->id);
        });
    }

    public function getById($id)
    {
        return $this->model::query()->findOrFail($id);
    }

    public function search(Builder $query, Request $request)
    {
        $searchTerm = $request->query('search');
        if ($searchTerm) {
            $query::whereLike($query, [
                'first_name',
                'last_name',
                'telephone',
                'specialization',
                'location',
                'user.email',
            ], $searchTerm);
        }
    }

    public function attendsEvent(Person $person, Event $event)
    {
        return (new EventPeople())::hasEvent($event)->hasPerson($person);
    }

    public function update(Person $person, $data)
    {
        $email = $data['email'];
        unset($data['email']);

        $person->fill($data);
        $user = $person->user;

        $dirtyUser = false;
        if ($person->isDirty('first_name', 'last_name')) {
            $dirtyUser = true;
        }

        if ($email !== $person->user->email) {
            $dirtyUser = true;
            $user->email = $email;
        }

        $phoneContactDetail = $person->contactDetails->where('type', ContactDetail::TYPE_PHONE)->first();
        if (!$person->telephone && $data['telephone']) {
            $phoneContactDetail = $this->makeContactDetail($data['telephone'], ContactDetail::TYPE_PHONE);
            $person->contactDetails()->save($phoneContactDetail);
        } elseif ($person->telephone !== $data['telephone']) {
            $phoneContactDetail->value = $data['telephone'];
            $phoneContactDetail->save();
        }
        if (!$data['telephone'] && $phoneContactDetail) {
            $phoneContactDetail->delete();
        }

        $person->save();
        $person->refresh();

        if ($dirtyUser) {
            $user->name = $person->full_name;
            $user->save();
        }
    }

    private function makeContactDetail($value, string $type)
    {
        $contactDetail = new ContactDetail();
        $contactDetail->type = $type;
        $contactDetail->name = Str::limit($value, 252);
        $contactDetail->value = str_replace([
            ' ',
            '(',
            ')',
        ], '', $value);

        return $contactDetail;
    }
}
