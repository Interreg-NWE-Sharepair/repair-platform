<?php

namespace App\Nova\Actions;

use App\Http\Services\MailService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class ActivatePerson extends Action
{
    public function handle(ActionFields $field, Collection $models)
    {
        $mailService = new MailService();
        $locale = $field->get('locale');
        if (!$locale) {
            return Action::danger(trans('nova.no_locale_selected'));
        }

        foreach ($models as $model) {
            /** @var \App\Models\Person $model */
            if (!$model->user->hasVerifiedEmail()) {
                $model->user->markEmailAsVerified();
                $model->user->ignore_automated_emails = 0;
                $model->user->save();
                App::setLocale($locale);
                $mailService->sendAccountActivatedMail($model, $locale);
            }
        }

        return Action::message(trans('nova.repairer_activation_mail_send'));
    }

    public function fields()
    {
        return [
            Select::make('Language Mail', 'locale')->options(config('translatable.default_locales'))->rules('required'),
        ];
    }

    public function name()
    {
        $label = 'Grant person access?';

        return $label;
    }
}
