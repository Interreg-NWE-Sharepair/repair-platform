<?php

namespace App\Mails;

class DeviceUnlinkedFromEventMail extends AbstractMail
{
    public static function name()
    {
        return __('Device Unlinked From Event Mail');
    }

    /**
     * This array lists the possible variable data
     * included in the content of the mail.
     *
     * @return array
     */
    public static function getContentVariables()
    {
        return [
            'device_first_name' => __('Device owner first name'),
            'device_last_name' => __('Device owner last name'),
            'device_brand_name' => __('messages.form_brand_name'),
            'device_model_name' => __('messages.model_name'),
            'device_id' => __('Device id'),

            'event_name' => __('Event name'),
            'event_date' => __('Event date'),
            'event_organisation' => __('Event organisation'),
        ];
    }

    /**
     * This array lists the possible variable
     * recipients that can receive this mail.
     *
     * @return array
     */
    public static function getRecipientVariables()
    {
        return [
            'device_owner' => __('Device owner'),
        ];
    }
}

