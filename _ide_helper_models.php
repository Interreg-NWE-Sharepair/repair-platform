<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ActivitySector
 *
 * @property int $id
 * @property string $uuid
 * @property string $code
 * @property array $name
 * @property int $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation> $organisations
 * @property-read int|null $organisations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector isVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector notVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivitySector withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperActivitySector {}
}

namespace App\Models{
/**
 * App\Models\CommonDeviceTypeIssue
 *
 * @property int $id
 * @property array|null $issue
 * @property int $device_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $description
 * @property-read \App\Models\DeviceType $deviceType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairTutorial> $repairTutorials
 * @property-read int|null $repair_tutorials_count
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereDeviceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommonDeviceTypeIssue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCommonDeviceTypeIssue {}
}

namespace App\Models{
/**
 * App\Models\CompletedRepairStatus
 *
 * @property int $id
 * @property string $uuid
 * @property string $code
 * @property array $name
 * @property array|null $tooltip
 * @property int|null $order
 * @property int|null $is_visible
 * @property string|null $ords_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $locale_name
 * @property-read string $locale_tooltip
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus order()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus visible()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereOrdsValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereTooltip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedRepairStatus whereUuid($value)
 * @mixin \Eloquent
 */
	class IdeHelperCompletedRepairStatus {}
}

namespace App\Models{
/**
 * Class Contact
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property int|null $location_id
 * @property int|null $organisation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tenant_id
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Tenant|null $tenant
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperContact {}
}

namespace App\Models{
/**
 * App\Models\ContactDetail
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $value
 * @property string|null $type
 * @property string $contactable_type
 * @property int $contactable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $contactable
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactDetail withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperContactDetail {}
}

namespace App\Models{
/**
 * Class Device
 *
 * @property int $id
 * @property string $uuid
 * @property string $brand_name
 * @property string|null $model_name
 * @property int|null $device_type_id
 * @property string|null $device_description
 * @property string|null $issue_description
 * @property int|null $location_id
 * @property int|null $organisation_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $postal_code
 * @property string $locale
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property int $temp
 * @property string|null $manufacture_year
 * @property int|null $event_id
 * @property int|null $event_follow_up_id
 * @property string|null $latest_status
 * @property int|null $completed_repair_status_id
 * @property-read \App\Models\CompletedRepairStatus|null $completedRepairStatus
 * @property-read \App\Models\DeviceType|null $deviceType
 * @property-read \App\Models\Event|null $event
 * @property-read mixed $barcode_images
 * @property-read mixed $completed_at
 * @property-read mixed $created_timestamp
 * @property-read mixed $defect_images
 * @property-read mixed $device_notes
 * @property-read mixed $device_type_name
 * @property-read mixed $general_image
 * @property-read mixed $has_future_event
 * @property-read mixed $image
 * @property-read mixed $images
 * @property-read mixed $log_notes
 * @property-read mixed $owner_name
 * @property-read mixed $padded_id
 * @property-read mixed $repairer_name
 * @property-read mixed $show_postal_code
 * @property-read string $status_color
 * @property-read mixed $status_last_updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\DeviceNote|null $note
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceNote> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\RepairLog|null $repairLog
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLog> $repairLogs
 * @property-read int|null $repair_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Device event($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Device fixed($person = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Device getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|Device lastRepairStatusIsNot($repairStatuses)
 * @method static \Illuminate\Database\Eloquent\Builder|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|Device optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|Device organisation($uuid)
 * @method static \Illuminate\Database\Eloquent\Builder|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|Device search($request)
 * @method static \Illuminate\Database\Eloquent\Builder|Device statusOrder()
 * @method static \Illuminate\Database\Eloquent\Builder|Device stillBroken()
 * @method static \Illuminate\Database\Eloquent\Builder|Device updatedAfter($updatedAfter)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereBrandName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCompletedRepairStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereDeviceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereDeviceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereEventFollowUpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereIssueDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereLatestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereManufactureYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUuid($value)
 * @mixin \Eloquent
 */
	class IdeHelperDevice {}
}

namespace App\Models{
/**
 * App\Models\DeviceNote
 *
 * @property int $id
 * @property string $content
 * @property int $person_id
 * @property int $device_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Device $device
 * @property-read mixed $created_timestamp
 * @property-read \App\Models\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDeviceNote {}
}

namespace App\Models{
/**
 * Class DeviceType
 * 
 * Device types come from here:
 * https://github.com/TheRestartProject/restarters.net/wiki/Repair-Data-fields
 *
 * @property int $id
 * @property string $uuid
 * @property array $name
 * @property string $code
 * @property int $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $max_repair_age
 * @property array|null $repair_success_rate
 * @property array|null $eco_impact
 * @property int|null $is_fixed_by_repair_cafe
 * @property int|null $show_on_guidance
 * @property string|null $ords_value
 * @property int|null $show_on_connects
 * @property int|null $show_on_mapping
 * @property float $product_weight_kg
 * @property float $product_co_kg
 * @property float $displacement_rate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommonDeviceTypeIssue> $commonDeviceTypeIssues
 * @property-read int|null $common_device_type_issues_count
 * @property-read mixed $locale_name
 * @property-read DeviceType|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairTutorial> $repairTutorials
 * @property-read int|null $repair_tutorials_count
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType isVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType notVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType showOnGuidance()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType showOnMapping()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType showOnRepair()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType visible()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereDisplacementRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereEcoImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereIsFixedByRepairCafe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereMaxRepairAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereOrdsValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereProductCoKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereProductWeightKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereRepairSuccessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereShowOnConnects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereShowOnGuidance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereShowOnMapping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceType withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperDeviceType {}
}

namespace App\Models{
/**
 * App\Models\Employee
 *
 * @property int $id
 * @property int $person_id
 * @property int $organisation_id
 * @property string $employee_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read mixed $full_name
 * @property-read mixed $roles_as_string
 * @property-read mixed $show_employee_info
 * @property-read \App\Models\Organisation $organisation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Person $person
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Employee active()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee organisation($organisation)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee type($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee user($user)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperEmployee {}
}

namespace App\Models{
/**
 * Class Event
 *
 * @property int $id
 * @property array $name
 * @property int|null $is_online
 * @property int|null $location_id
 * @property int|null $organisation_id
 * @property array $description
 * @property \Illuminate\Support\Carbon $date_start
 * @property string $time_start
 * @property string $time_stop
 * @property string|null $address
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $timezone
 * @property string|null $organizer
 * @property int|null $max_devices
 * @property string|null $restarters_id
 * @property string|null $restarters_data_synced_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read string $attending_repairers
 * @property-read string $date_formatted
 * @property-read string $ends_at
 * @property-read bool $is_attending
 * @property-read bool $is_from_restarters
 * @property-read bool $is_future
 * @property-read bool $is_organizer
 * @property-read string $locale_description
 * @property-read string $locale_name
 * @property-read int $registered_devices
 * @property-read string $starts_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $people
 * @property-read int|null $people_count
 * @method static \Illuminate\Database\Eloquent\Builder|Event fromRestarters()
 * @method static \Illuminate\Database\Eloquent\Builder|Event future()
 * @method static \Illuminate\Database\Eloquent\Builder|Event futurePlusPastDays($days = 2)
 * @method static \Illuminate\Database\Eloquent\Builder|Event locale($locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event organisation($uuid)
 * @method static \Illuminate\Database\Eloquent\Builder|Event past()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereMaxDevices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganizer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRestartersDataSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRestartersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTimeStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperEvent {}
}

namespace App\Models{
/**
 * App\Models\EventPeople
 *
 * @property int $id
 * @property int|null $person_id
 * @property int|null $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\Person|null $person
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople hasEvent(\App\Models\Event $event)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople hasPerson(\App\Models\Person $person)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventPeople whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperEventPeople {}
}

namespace App\Models{
/**
 * App\Models\ImageCategory
 *
 * @property int $id
 * @property array $name
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperImageCategory {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $uuid
 * @property int|null $organisation_id
 * @property array $name
 * @property array|null $description
 * @property array $slug
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $street
 * @property string|null $number
 * @property string|null $bus
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $country
 * @property string|null $country_code
 * @property int $is_visible
 * @property int $is_headquarters
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read mixed $address
 * @property-read string|null $email
 * @property-read string|null $facebook
 * @property-read string|null $google
 * @property-read string|null $image_url
 * @property-read string|null $instagram
 * @property-read mixed $locales
 * @property-read string|null $telephone
 * @property-read string|null $website
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Organisation|null $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|Location available()
 * @method static \Illuminate\Database\Eloquent\Builder|Location bbox($bbox = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Location deviceTypes($deviceTypeCodes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Location distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|Location geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|Location getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|Location headquarterFirst()
 * @method static \Illuminate\Database\Eloquent\Builder|Location isVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location notVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|Location optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|Location organisationTypes($organisationTypes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location search()
 * @method static \Illuminate\Database\Eloquent\Builder|Location virtual($virtual = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|Location virtualLast()
 * @method static \Illuminate\Database\Eloquent\Builder|Location visible()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereBus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsHeadquarters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperLocation {}
}

namespace App\Models{
/**
 * App\Models\LocationSuggestion
 *
 * @property int $id
 * @property int|null $location_id
 * @property array|null $name
 * @property array|null $description
 * @property array|null $product_description
 * @property bool|null $has_warranty
 * @property array|null $warranty_info
 * @property array|null $address
 * @property int|null $organisation_type_id
 * @property array|null $device_types
 * @property array|null $activity_sectors
 * @property array|null $contacts
 * @property array|null $locales
 * @property string|null $submitter_email
 * @property string|null $submitter_relation
 * @property array|null $original_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property-read \App\Models\Location|null $location
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\OrganisationType|null $organisationType
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereActivitySectors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereDeviceTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereHasWarranty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereLocales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereOrganisationTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereOriginalDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereSubmitterEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereSubmitterRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion whereWarrantyInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationSuggestion withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperLocationSuggestion {}
}

namespace App\Models{
/**
 * App\Models\Organisation
 *
 * @property int $id
 * @property string $uuid
 * @property array $name
 * @property array|null $description
 * @property array $slug
 * @property array|null $product_description
 * @property bool|null $has_warranty
 * @property array|null $warranty_description
 * @property int $is_visible
 * @property bool $is_virtual
 * @property bool $is_rc_active
 * @property int $show_employee_info
 * @property int|null $organisation_type_id
 * @property string|null $responsible_group
 * @property string|null $restarters_id
 * @property string|null $restarters_data_synced_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivitySector> $activitySectors
 * @property-read int|null $activity_sectors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceType> $deviceTypes
 * @property-read int|null $device_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read mixed $description_short
 * @property-read mixed $email
 * @property-read string|null $facebook
 * @property-read string|null $google
 * @property-read mixed $image
 * @property-read string|null $instagram
 * @property-read bool $is_from_restarters
 * @property-read mixed $locales
 * @property-read string|null $telephone
 * @property-read mixed $website
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location> $locations
 * @property-read int|null $locations_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrganisationLocale> $organisationLocales
 * @property-read int|null $organisation_locales_count
 * @property-read \App\Models\OrganisationType|null $organisationType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $persons
 * @property-read int|null $persons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation available()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation findByLocalizedSlug($slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation fromRestarters()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation isVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation notVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation organisationType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation virtual($virtual = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation virtualLast()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation visible()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereHasWarranty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereIsRcActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereIsVirtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereOrganisationTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereResponsibleGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereRestartersDataSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereRestartersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereShowEmployeeInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereWarrantyDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperOrganisation {}
}

namespace App\Models{
/**
 * App\Models\OrganisationLocale
 *
 * @todo rework to json instead of relation
 * @property int $id
 * @property int $organisation_id
 * @property string $locale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationLocale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOrganisationLocale {}
}

namespace App\Models{
/**
 * App\Models\OrganisationRequest
 *
 * @property int $id
 * @property string $email
 * @property string $organisation_name
 * @property string $postal_code
 * @property string $municipality
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereMunicipality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereOrganisationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOrganisationRequest {}
}

namespace App\Models{
/**
 * App\Models\OrganisationType
 *
 * @property int $id
 * @property string $uuid
 * @property string $code
 * @property array $name
 * @property int $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation> $organisations
 * @property-read int|null $organisations_count
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType getByQueryParameters()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType isVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType notVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType optionalLimit()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType optionalPaginate()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationType withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperOrganisationType {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property array $title
 * @property array $intro
 * @property array $body
 * @property mixed|null $content
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $slug
 * @property array|null $seo_description
 * @property array|null $seo_keywords
 * @property int|null $tenant_id
 * @property-read array $flexible
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenant|null $tenant
 * @method static \Illuminate\Database\Eloquent\Builder|Page findByLocalizedSlug($slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Page locale($locale, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page tenant($code)
 * @method static \Illuminate\Database\Eloquent\Builder|Page type($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPage {}
}

namespace App\Models{
/**
 * App\Models\Person
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $user_id
 * @property string|null $location
 * @property string|null $specialization
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read mixed $full_name
 * @property-read mixed $telephone
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation> $organisations
 * @property-read int|null $organisations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLogNote> $repairLogNotes
 * @property-read int|null $repair_log_notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLog> $repairLogs
 * @property-read int|null $repair_logs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Person withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperPerson {}
}

namespace App\Models{
/**
 * Class RepairBarrier
 *
 * @property int $id
 * @property array $name
 * @property array|null $tooltip
 * @property string $code
 * @property int $is_visible
 * @property string|null $ords_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $locale_tooltip
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier byStatusCode($statusCode)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier visible()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereOrdsValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereTooltip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairBarrier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairBarrier {}
}

namespace App\Models{
/**
 * App\Models\RepairGuidanceFormDeviceTypeIssue
 *
 * @property int $id
 * @property int|null $common_device_type_issue_id
 * @property int $repair_guidance_form_log_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CommonDeviceTypeIssue|null $commonDeviceTypeIssue
 * @property-read \App\Models\RepairGuidanceFormLog|null $repairGuidanceLog
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue whereCommonDeviceTypeIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue whereRepairGuidanceFormLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormDeviceTypeIssue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairGuidanceFormDeviceTypeIssue {}
}

namespace App\Models{
/**
 * App\Models\RepairGuidanceFormLog
 *
 * @property int $id
 * @property int|null $device_type_id
 * @property string|null $device_type_name
 * @property string|null $brand_name
 * @property string|null $model_name
 * @property string|null $product_description
 * @property string|null $product_age
 * @property int|null $common_device_type_issue_id
 * @property string|null $common_issue_text
 * @property string|null $locale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed|null $extra_properties
 * @property string|null $uuid
 * @property string $source
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommonDeviceTypeIssue> $commonDeviceTypeIssues
 * @property-read int|null $common_device_type_issues_count
 * @property-read \App\Models\DeviceType|null $deviceType
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereBrandName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereCommonDeviceTypeIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereCommonIssueText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereDeviceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereDeviceTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereExtraProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereProductAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairGuidanceFormLog whereUuid($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairGuidanceFormLog {}
}

namespace App\Models{
/**
 * Class RepairLog
 *
 * @property int $id
 * @property int|null $device_id
 * @property string|null $fix_description
 * @property string|null $diagnosis
 * @property string|null $root_cause
 * @property string|null $used_materials
 * @property string|null $used_links
 * @property int|null $repairer_id
 * @property int|null $person_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $uuid
 * @property string|null $reminder_mail_sent
 * @property int|null $is_using_spare_parts
 * @property string|null $status
 * @property int|null $completed_repair_status_id
 * @property-read \App\Models\CompletedRepairStatus|null $completedRepairStatus
 * @property-read \App\Models\Device|null $device
 * @property-read mixed $barriers
 * @property-read mixed $images
 * @property-read mixed $latest_note
 * @property-read mixed $links
 * @property-read mixed $notes
 * @property-read mixed $repair_images
 * @property-read mixed $repair_status
 * @property-read mixed $status_code
 * @property-read mixed $timestamp
 * @property-read mixed $updated_timestamp
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Person|null $person
 * @property-read \App\Models\RepairBarrier|null $repairBarrier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairBarrier> $repairBarriers
 * @property-read int|null $repair_barriers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLogLink> $repairLinks
 * @property-read int|null $repair_links_count
 * @property-read \App\Models\RepairLogNote|null $repairNote
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLogNote> $repairNotes
 * @property-read int|null $repair_notes_count
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog completed()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog inProgress()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereCompletedRepairStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereFixDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereIsUsingSpareParts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereReminderMailSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereRepairerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereRootCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereUsedLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereUsedMaterials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLog whereUuid($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairLog {}
}

namespace App\Models{
/**
 * App\Models\RepairLogBarrier
 *
 * @property int $id
 * @property int|null $repair_log_id
 * @property int|null $repair_barrier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RepairBarrier|null $repairBarrier
 * @property-read \App\Models\RepairLog|null $repairLog
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier whereRepairBarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier whereRepairLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogBarrier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairLogBarrier {}
}

namespace App\Models{
/**
 * Class RepairLogLink
 *
 * @property \App\Models\RepairLog $repairLog
 * @property string url
 * @property int $id
 * @property int|null $repair_log_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink whereRepairLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogLink whereUrl($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairLogLink {}
}

namespace App\Models{
/**
 * Class RepairLogLink
 *
 * @property RepairLog $repairLog
 * @property string url
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property string $content
 * @property int|null $repair_log_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $repairer_id
 * @property int|null $person_id
 * @property-read mixed $created_timestamp
 * @property-read \App\Models\Person|null $person
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereRepairLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereRepairerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairLogNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairLogNote {}
}

namespace App\Models{
/**
 * App\Models\RepairTutorial
 *
 * @property int $id
 * @property array $title
 * @property array $description
 * @property mixed $content
 * @property array $slug
 * @property int|null $device_type_id
 * @property int|null $common_device_type_issue_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_highlight
 * @property array $intro
 * @property int $has_disclaimer
 * @property-read \App\Models\CommonDeviceTypeIssue|null $commonDeviceTypeIssue
 * @property-read \App\Models\DeviceType|null $deviceType
 * @property-read array $flexible
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial findByLocalizedSlug($slug)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial isHighlighted()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial isLocalized($locale)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereCommonDeviceTypeIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereDeviceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereHasDisclaimer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereIsHighlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepairTutorial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRepairTutorial {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRole {}
}

namespace App\Models{
/**
 * App\Models\Tenant
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property array $domains
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDomain($domain)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDomains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTenant {}
}

namespace App\Models{
/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $email_verified_at
 * @property string $locale
 * @property int $id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $provider
 * @property string|null $provider_id
 * @property int|null $location_id
 * @property int $ignore_automated_emails
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Person|null $person
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RepairLog> $repairLogs
 * @property-read int|null $repair_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIgnoreAutomatedEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

