# How to work with Repositories


### Side note
This implementation was introduced halfway the project. So a lot of code still needs to be moved.
### Global info about Repositories
Info about what repositories are can be found here [Implement repositories](https://asperbrothers.com/blog/implement-repository-pattern-in-laravel/).

### How to
Bind your Interface to your `Repository class` in the `RepositoryServiceProvider` class. <br/>
This way every time you call the interface you get the `Repository class`.

```php
$this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
```

- Your `Repository class` needs to implement the same function as your `RepositoryInterface`

## Ease of use in your project
For ease of use, so you don't need to add your interface to your function or constructor,
You can write a `Facade` for your Repository Interface:
```php
namespace App\Facades;


use App\Repository\EmployeeOrganisationRepositoryInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getRepairerLocation(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder getRepairersByLocation(\App\Models\Location $location)
 * @method static bool isRepairerLocation(\App\Models\User $user, \App\Models\Location $location)
 * @method static bool canViewLocation(\App\Models\User $user, \App\Models\Location $location)
 * @method static bool isEventOrganizerLocation(\App\Models\User $user, \App\Models\Location $location)
 */
class UserLocationRepository extends Facade
{

    protected static function getFacadeAccessor()
    {
        return EmployeeOrganisationRepositoryInterface::class;
    }
}
```

By adding the above `Facade` you can easily access the function for this Repository without adding the interface to your constructor or method call:
```php
EmployeeOrganisationRepositoryInterface::canViewLocation(Auth::user(), $location)
```


The repository class itself contains all your logic.
```php
    class EventRepository extends BaseRepository implements EventRepositoryInterface

    public function getAllForOrganisation($uuid): Builder
    {
        return $this->model::query()->selectRaw('events.*')->with([
            'organisation',
            'people',
        ])->organisation($uuid);
    }
```


