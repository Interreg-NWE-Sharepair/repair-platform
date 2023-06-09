<?php

use App\Mails\DeviceFixedMail;

return [

    /*
    |--------------------------------------------------------------------------
    | Debug mail
    |--------------------------------------------------------------------------
    |
    | This mail address will be used to send mails to when in debug mode.
    |
    */
    'debug_mail' => env('MAIL_DEBUG', null),

    /*
    |--------------------------------------------------------------------------
    | Mail Templates
    |--------------------------------------------------------------------------
    |
    | Here you can add all the provided mail templates that can be selected for
    | customization.
    |
    | The key of this array is saved to the mail-template, this way you are not
    | limited by the namespace of the template.
    |
    | F.E. 'test-mail' => App\Mail\TestMail::class,
    |
    */
    'mails' => [
        'contact-email' => \App\Mails\ContactMail::class,
        'device-registered-email' => \App\Mails\DeviceRegisteredMail::class,
        'device-event-registered-email' => \App\Mails\DeviceEventRegisteredMail::class,
        'selected-for-repair-email' => \App\Mails\SelectedForRepairMail::class,
        'open-devices-email' => \App\Mails\OpenDevicesMail::class,
        'device-reminder-email' => \App\Mails\DeviceReminderMail::class,
        'organisation-suggestion-email' => \App\Mails\OrganisationSuggestionMail::class,
        'repairer-registered-email' => \App\Mails\RepairerRegisteredMail::class,
        'account-activated-email' => \App\Mails\AccountActivatedMail::class,
        'device-reopened-email' => \App\Mails\DeviceReopenedMail::class,
        'device-fixed-email' => \App\Mails\DeviceFixedMail::class,
        'device-registered-on-event-email' => \App\Mails\DeviceRegisteredOnEventMail::class,
        'device-unlinked-from-event-mail' => \App\Mails\DeviceUnlinkedFromEventMail::class,
        'device-linked-to-event-mail' => \App\Mails\DeviceLinkedToEventMail::class,
        'new-location-suggestion-mail' => \App\Mails\NewLocationSuggestionMail::class,
        'device-event-reminder-mail' => \App\Mails\DeviceEventReminderMail::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail Designs
    |--------------------------------------------------------------------------
    |
    | Mail templates can have different designs, these are views that are
    | filled with $content.
    |
    | Supported:    - package based view notation (statikbe::mail.designs.default)
    |               - default include notation (mails.designs.default)
    |
    */
    'designs' => [
        'statikbe::mail.designs.default' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Render engines
    |--------------------------------------------------------------------------
    |
    | The possible render engines your system provides. These engines receive
    | the mailable, design and body and define how the mail is rendered.
    |
    | The key of this array is saved to the mail-template, this way you are not
    | limited by the namespace of the template.
    |
    | Provided: - Html engine for regular HTML wysiwyg
    |           - Editor-js engine for Editor.js wysiwyg json data
    |
    */
    'render_engines' => [
        'html' => Statikbe\LaravelMailEditor\MailRenderEngines\HtmlEngine::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Mail Class
    |--------------------------------------------------------------------------
    |
    | It is possible to provide your own BaseMail class
    | for custom implementations.
    |
    */
    'base_mail' => \Statikbe\LaravelMailEditor\BaseMail::class,
];
