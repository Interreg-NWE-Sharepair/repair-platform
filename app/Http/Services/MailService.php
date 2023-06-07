<?php

namespace App\Http\Services;

use App\Mails\AccountActivatedMail;
use App\Mails\ContactMail;
use App\Mails\DeviceEventRegisteredMail;
use App\Mails\DeviceEventReminderMail;
use App\Mails\DeviceFixedMail;
use App\Mails\DeviceLinkedToEventMail;
use App\Mails\DeviceRegisteredMail;
use App\Mails\DeviceRegisteredOnEventMail;
use App\Mails\DeviceReminderMail;
use App\Mails\DeviceReopenedMail;
use App\Mails\DeviceUnlinkedFromEventMail;
use App\Mails\NewLocationSuggestionMail;
use App\Mails\OpenDevicesMail;
use App\Mails\OrganisationSuggestionMail;
use App\Mails\RepairerRegisteredMail;
use App\Mails\SelectedForRepairMail;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Event;
use App\Models\LocationSuggestion;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MailService
{
    public function sendContactMail(Contact $contact, $renderOnly = false)
    {
        if ($contact->organisation) {
            $recipients = [];
            $entityAdmins = $contact->organisation->entityAdmins()->get();
            foreach ($entityAdmins as $entityAdmin) {
                if (!$entityAdmin->person->user->ignore_automated_emails) {
                    $recipients[] = $entityAdmin->person->user;
                }
            }
        } else {
            $recipients = ["sigma@statik.be"];
        }

        $contentVars = [
            'name' => $contact->name,
            'email' => $contact->email,
            'location_name' => optional($contact->organisation)->name,
            'message' => $contact->message,
        ];
        $recipientVars = [
            'entity_admins' => $recipients,
        ];

        $mail = new ContactMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendDeviceRegisteredMail(Device $device, $renderOnly = false)
    {
        $contentVars = [
            'device_id' => $device->id,
            'device_type' => $device->deviceType->name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => ($device->model_name) ?: '-',
            'device_description' => ($device->device_description) ?: '-',
            'device_issue' => $device->issue_description,
            'first_name' => $device->first_name,
            'last_name' => $device->last_name,
            'email' => $device->email,
            'telephone' => ($device->telephone) ?: '-',
            'postal_code' => ($device->postal_code) ?: '-',
            'organisation' => $device->organisation->name,
            'event' => ($device->event) ? ($device->event->locale_name . ' - ' . $device->event->date_formatted) : '-',
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceRegisteredMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendDeviceEventRegisteredMail(Device $device, $renderOnly = false)
    {
        $event = $device->event;
        $timeStart = Carbon::createFromTimeString($event->time_start);
        $contentVars = [
            'device_id' => $device->id,
            'device_type' => $device->deviceType->name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => ($device->model_name) ?: '-',
            'device_description' => ($device->device_description) ?: '-',
            'device_issue' => $device->issue_description,
            'first_name' => $device->first_name,
            'last_name' => $device->last_name,
            'email' => $device->email,
            'telephone' => ($device->telephone) ?: '-',
            'postal_code' => ($device->postal_code) ?: '-',
            'organisation' => $device->organisation->name,
            'event_name' => $event->locale_name,
            'event_date' => $event->date_formatted,
            'event_time' => $timeStart->isoFormat('HH:mm'),
            'event_address' => $event->address,
            'event_organisation' => $event->organisation->name,
            'event_url' => route('location_show', ['organisation' => $event->organisation]),
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceEventRegisteredMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendMailRegisteredEvent(Device $device, $renderOnly = false)
    {
        $event = $device->event;
        $eventOrganisation = $device->event->organisation;
        $recipients = [];

        if ($eventOrganisation) {
            $eventAdmins = $eventOrganisation->eventAdmins()->get() ?? [];
            foreach ($eventAdmins as $eventAdmin) {
                if ($eventAdmin && $eventAdmin->person && $eventAdmin->person->user && !$eventAdmin->person->user->ignore_automated_emails) {
                    $recipients[] = $eventAdmin->person->user;
                }
            }
        } else {
            $recipients[] = ['sigma@statik.be'];
        }

        $contentVars = [
            'event_name' => $event->locale_name,
            'event_date' => $event->date_formatted,
            'device_list' => $this->generateDevicesListHtml([$device], 1),
            'device_id' => $device->id,
        ];

        $recipientVars = [
            'event_admins' => $recipients,
        ];

        $mail = new DeviceRegisteredOnEventMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendMailUnlinkedEvent(Device $device, Event $event, $renderOnly = false)
    {
        $contentVars = [
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => $device->model_name,
            'device_id' => $device->id,
            'event_name' => $event->locale_name,
            'event_date' => $event->date_formatted,
            'event_organisation' => $event->organisation->name,
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceUnlinkedFromEventMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendMailLinkedEvent(Device $device, Event $event, $renderOnly = false)
    {
        $contentVars = [
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => $device->model_name,
            'device_id' => $device->id,
            'event_name' => $event->locale_name,
            'event_date' => $event->date_formatted,
            'event_organisation' => $event->organisation->name,
            'event_url' => route('location_show', ['organisation' => $event->organisation]),
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceLinkedToEventMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendMailSelectedForRepair(Device $device,
        Person $person,
        $sendToDeviceOwner = true,
        $renderOnly = false
    ) {
        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,
            'device_id' => $device->id,
            'device_url' => LaravelLocalization::getURLFromRouteNameTranslated($person->user->locale, 'routes.device_show', ['slug' => $device->slug]),
            'device_type' => $device->deviceType->name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => ($device->model_name) ? $device->model_name : '-',
            'device_description' => ($device->device_description) ? $device->device_description : '-',
            'device_issue' => $device->issue_description,
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_email' => $device->email,
            'device_telephone' => ($device->telephone) ? $device->telephone : '-',
            'device_postal_code' => ($device->postal_code) ? $device->postal_code : '-',
            'organisation' => $device->organisation->name,
            'event' => ($device->event) ? ($device->event->locale_name . ' - ' . $device->event->date_formatted) : '-',
        ];

        $repairer = $person->user;
        $deviceOwner = null;

        if ($sendToDeviceOwner) {
            $deviceOwner = [
                'device_owner' => [
                    'mail' => $device->email,
                    'locale' => $device->locale,
                ],
            ];
        }

        $recipientVars = [
            'repairer' => $repairer,
            'device_owner' => $deviceOwner,
        ];

        $mail = new SelectedForRepairMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    //NOT USING THIS FOR NOW
    //public function sendDeviceMailRepaired(Device $device, Person $person, $renderOnly = false)
    //{
    //    $this->initialize();
    //
    //    if ($renderOnly) {
    //        return $this->beautymail->view('mails.customer.device-repaired-survey', [
    //            'device' => $device,
    //            'person' => $person,
    //            'contact' => route('device_show', ['slug' => $device->slug]),
    //        ]);
    //    }
    //
    //    $locale = app()->getLocale();
    //    app()->setLocale($device->locale);
    //
    //    $this->beautymail->send('mails.customer.device-repaired-survey', [
    //        'device' => $device,
    //        'person' => $person,
    //        'contact' => route('device_show', ['slug' => $device->slug]),
    //    ], function (Message $message) use ($device) {
    //        $message->from(config('mail.email'), config('mail.email_name'));
    //        if ($this->debugMail) {
    //            $message->to($this->debugMail);
    //        } else {
    //            $message->to($device->email);
    //        }
    //        $message->subject(trans('mail.device_selected_repair'));
    //    });
    //
    //    app()->setLocale($locale);
    //}

    public function sendDeviceFixed(Device $device, Person $person, $renderOnly = false)
    {
        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,
            'device_id' => $device->id,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => ($device->model_name) ? $device->model_name : '-',
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_email' => $device->email,
            'detail_url' => route('device_show_repaired', ['slug' => $device->slug]),
            'organisation' => $device->organisation->name,
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceFixedMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendOpenDevicesToRepairer(Person $person,
        $devices,
        $organisation,
        $deviceAmount,
        $renderOnly = false
    ) {
        if (!$person->user) {
            return;
        }

        if ($person->user->ignore_automated_emails) {
            return;
        }

        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,
            'devices' => $this->generateDevicesListHtml($devices, $deviceAmount, $organisation, $person->user->locale),
            'organisation' => $organisation->name,
        ];

        $recipientVars = [
            'repairer' => $person->user,
        ];

        $mail = new OpenDevicesMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }

        $mail->sendMail($contentVars, $recipientVars);
    }

    private function generateDevicesListHtml($devices, $deviceAmount, $organisation = null, $newLocale = null)
    {
        if ($newLocale) {
            $locale = app()->getLocale();
            app()->setLocale($newLocale);
            setlocale(LC_ALL, config("laravellocalization.supportedLocales.$newLocale.regional"));
        } else {
            $newLocale = app()->getLocale();
        }

        $devicesListHtml = "<ul style=\"padding-left: 0; margin-left: 0;\">";
        foreach ($devices as $device) {
            $url = LaravelLocalization::getURLFromRouteNameTranslated($newLocale, 'routes.device_show', ['slug' => $device->slug]);
            $deviceType = $device->deviceType->name;
            $deviceName = $device->brand_name . ' ' . $device->model_name . ' (' . $deviceType . ')';
            $deviceOwnerName = $device->getOwnerName();
            $deviceCreatedAt = $device->created_at->isoFormat('D MMM YYYY');
            $deviceDescription = Str::limit($device->device_description, 100);
            $eventName = optional($device->event)->name;
            $eventCopy = __('mail.mail_event');

            $devicesListHtml .= "<li style=\"list-style: none; border-bottom: 1px solid #dddddd; padding: 10px 0;\">";
            $devicesListHtml .= "<a href=\"{$url}\">{$deviceName}</a><br>";
            $devicesListHtml .= "<small>{$deviceOwnerName} - {$deviceCreatedAt}</small>";
            if ($deviceDescription) {
                $devicesListHtml .= "<br><small>{$deviceDescription}</small>";
            }
            if ($eventName) {
                $devicesListHtml .= "<br><small>{$eventCopy}: {$eventName}</small>";
            }
            $devicesListHtml .= "</li>";
        }
        $devicesListHtml .= "</ul>";

        if ($deviceAmount > 10 && $organisation) {
            $urlShowMore = LaravelLocalization::getURLFromRouteNameTranslated($newLocale, 'routes.location_devices_overview', ['locationCode' => $organisation->slug]);
            $showMoreCopy = __('mail.devices_overview');
            $devicesListHtml .= "<a href=\"{$urlShowMore}\">{$showMoreCopy}</a>";
        }

        if ($locale ?? null) {
            app()->setLocale($locale);
            setlocale(LC_ALL, config("laravellocalization.supportedLocales.$locale.regional"));
        }

        return $devicesListHtml;
    }

    public function sendDeviceReminderToRepairer(Person $person, Device $device, $renderOnly = false)
    {

        if ($person->user && $person->user->ignore_automated_emails) {
            return;
        }
        $locale = app()->getLocale();
        $repairerLocale = $person->user->locale;
        app()->setLocale($repairerLocale);
        setlocale(LC_ALL, config("laravellocalization.supportedLocales.$repairerLocale.regional"));

        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,

            'device_id' => $device->id,
            'device_start_date' => $device->repairLog->created_at->isoFormat('D MMM YYYY'),
            'device_url' => LaravelLocalization::getURLFromRouteNameTranslated($person->user->locale, 'routes.device_show', ['slug' => $device->slug]),
            'device_type' => $device->deviceType->name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => ($device->model_name) ? $device->model_name : '-',
            'device_description' => ($device->device_description) ? $device->device_description : '-',
            'device_issue' => $device->issue_description,
            'device_owner' => $device->getOwnerName(),
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_email' => $device->email,
            'device_telephone' => ($device->telephone) ? $device->telephone : '-',
            'device_postal_code' => ($device->postal_code) ? $device->postal_code : '-',

            'overview_url' => LaravelLocalization::getURLFromRouteNameTranslated($person->user->locale, 'routes.repairer_personal_overview'),
            'contact_url' => LaravelLocalization::getURLFromRouteNameTranslated($person->user->locale, 'routes.contact_index'),

            'organisation' => $device->organisation->name,
        ];

        app()->setLocale($locale);
        setlocale(LC_ALL, config("laravellocalization.supportedLocales.$locale.regional"));

        $recipientVars = [
            'repairer' => $person->user,
        ];

        $mail = new DeviceReminderMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendOrganisationSuggestionMail($organisationRequest, $renderOnly = false)
    {
        $contentVars = [
            'email' => $organisationRequest->email,
            'suggestion' => $organisationRequest->organisation_name,
            'postal_code' => $organisationRequest->postal_code,
            'municipality' => $organisationRequest->municipality,
        ];

        $recipientVars = [//Recipient variables are filled in the mail template
        ];

        $mail = new OrganisationSuggestionMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendAccountActivatedMail(Person $person, $locale = null, $renderOnly = false)
    {
        $contentVars = [
            'first_name' => $person->first_name,
            'organisation' => $person->employees()->first()->organisation->name,
        ];

        $recipientVars = [
            'new_user' => [
                'mail' => $person->user,
                'locale' => $locale,
            ],
        ];

        $mail = new AccountActivatedMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendRepairerRegisteredMail(Employee $employee, $renderOnly = false)
    {
        $person = $employee->person;
        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,
            'repairer_telephone' => $person->telephone,
            'organisation' => $employee->organisation->name,
        ];

        $admins = [];
        foreach ($employee->organisation->entityAdmins()->get() as $adminEmployee) {
            $admins[] = $adminEmployee->person->user;
        }

        $recipientVars = [
            'repairer' => $employee->person->user,
            'admins' => $admins,
        ];

        $mail = new RepairerRegisteredMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendDeviceReopenedMail(Device $device, Person $person, $renderOnly = false)
    {
        $contentVars = [
            'repairer_first_name' => $person->first_name,
            'repairer_last_name' => $person->last_name,
            'repairer_email' => $person->user->email,

            'device_id' => $device->id,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => $device->model_name,
            'device_owner' => $device->getOwnerName(),
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,

            'organisation' => $device->organisation->name,
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceReopenedMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendNewLocationSuggestionMail(LocationSuggestion $locationSuggestion, $renderOnly = false)
    {
        $contentVars = [
            'url' => url(config('nova.path') . '/resources/location-suggestions/' . $locationSuggestion->id),
        ];

        $adminsEmails = [
            'rosalie@repairshare.be',
            'sigma@statik.be',
            'repairconnects@repairtogether.be',
        ];

        $admins = [];
        foreach ($adminsEmails as $email) {
            //If we find a user for an email use that, if not use the plain email
            $user = User::where('email', $email)->first();
            if ($user) {
                $admins[] = $user;
                continue;
            }

            $admins[] = [
                'mail' => $email,
            ];
        }

        $recipientVars = [
            'admins' => $admins,
        ];

        $mail = new NewLocationSuggestionMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }

    public function sendEventReminderMail(Device $device, Event $event, $renderOnly = false)
    {
        $timeStart = Carbon::createFromTimeString($event->time_start);
        $timeStop = Carbon::createFromTimeString($event->time_stop);
        $contentVars = [
            'device_id' => $device->id,
            'device_first_name' => $device->first_name,
            'device_last_name' => $device->last_name,
            'device_brand_name' => $device->brand_name,
            'device_model_name' => $device->model_name,
            'device_type' => $device->deviceType->name,
            'device_description' => $device->device_description,
            'event_name' => $event->locale_name,
            'event_address' => $event->address,
            'event_date' => $event->date_formatted,
            'event_time' => $timeStart->isoFormat('HH:mm') . ' - ' . $timeStop->isoFormat('HH:mm'),
            'event_organisation' => $event->organisation->name,
            'event_url' => route('location_show', ['organisation' => $event->organisation]),
            'organisation_url' => route('location_show', ['organisation' => $event->organisation])
        ];

        $recipientVars = [
            'device_owner' => [
                'mail' => $device->email,
                'locale' => $device->locale,
            ],
        ];

        $mail = new DeviceEventReminderMail();

        if ($renderOnly) {
            return $mail->renderMail($contentVars, $recipientVars);
        }
        $mail->sendMail($contentVars, $recipientVars);
    }
}
