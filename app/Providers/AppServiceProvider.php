<?php

namespace App\Providers;

use App\Jobs\DeviceEventReminderJob;
use App\Jobs\RepairerDeviceReminderJob;
use App\Jobs\WeeklyRepairerOpenDevicesJob;
use App\Models\CompletedRepairStatus;
use App\Models\Contact;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Location;
use App\Models\Organisation;
use App\Models\OrganisationRequest;
use App\Models\Page;
use App\Models\Person;
use App\Models\RepairBarrier;
use App\Models\RepairLog;
use App\Models\RepairTutorial;
use App\Models\Tenant;
use App\Models\User;
use DmitryBubyakin\NovaMedialibraryField\TransientModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Spatie\NovaTranslatable\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Translatable::defaultLocales(array_keys(config('translatable.default_locales')));

        Relation::morphMap([
            'user' => User::class,
            'device' => Device::class,
            'location' => Location::class,
            'device_type' => DeviceType::class,
            'repair_barrier' => RepairBarrier::class,
            'event' => Event::class,
            'page' => Page::class,
            'organisation_request' => OrganisationRequest::class,
            'completed_repair_status' => CompletedRepairStatus::class,
            'repair_log' => RepairLog::class,
            'contact' => Contact::class,
            'organisation' => Organisation::class,
            'person' => Person::class,
            'employee' => Employee::class,
            'nova_media' => TransientModel::class,
            'repair_tutorial' => RepairTutorial::class,
        ]);

        //WHERE LIKE MACRO TO SEARCH FOR A CERTAIN WORD (OR COMBINATION) IN A MODEL
        Builder::macro('whereLike', function ($model, $attributes, string $searchTerm) {
            return $model->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(str_contains($attribute, '.'), function (Builder $query) use (
                        $attribute,
                        $searchTerm
                    ) {
                        $buffer = explode('.', $attribute);
                        $attributeField = array_pop($buffer);
                        $relationPath = implode('.', $buffer);
                        $query->orWhereHas($relationPath, function (Builder $query) use ($attributeField, $searchTerm) {
                            $query->where($attributeField, 'LIKE', "%{$searchTerm}%");
                        });
                    }, function (Builder $query) use ($attribute, $searchTerm) {
                        $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                    });
                }
            });
        });

        /**
         * Add Tenant ID to queue for specific tenants
         * For now this is for Repair jobs, so they are Tenant aware
         */
        $repairConnectJobs = [
            WeeklyRepairerOpenDevicesJob::class,
            RepairerDeviceReminderJob::class,
            DeviceEventReminderJob::class,
        ];
        app('queue')->createPayloadUsing(function ($connectionName, $queue, $payload) use ($repairConnectJobs) {
            if (in_array($payload['displayName'], $repairConnectJobs, true)) {
                /** @var Tenant $tenant */
                $tenant = Tenant::whereCode(Tenant::REPLOG)->first();

                return ['tenantId' => $tenant->id];
            }

            return [];
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
