<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted' => ':attribute müssen akzeptiert werden',
    'active_url' => ':attribute ist keine gültige URL.',
    'after' => ':attribute muss ein Datum nach :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach oder gleich wie :date sein.',
    'alpha' => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => ':attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => ':attribute darf nur Buchstaben und Zahlen.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder gleich :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min und :max sein.',
        'file' => ':attribute muss zwischen :min und :max kilobytes sein.',
        'string' => ':attribute muss zwischen :min and :max Charakter lang sein.',
        'array' => ':attribute muss einen Wert zwischen :min and :max haben.',
    ],
    'boolean' => ':attribute Feld kann nur true oder false sein.',
    'confirmed' => ':attribute Bestätigung stimmt nicht überein',
    'date' => ':attribute ist kein gültiges Datum',
    'date_equals' => ':attribute muss ein Datum sein, das gleich ist wie :date.',
    'date_format' => ':attribute entspricht nicht dem Format :format.',
    'different' => ':attribute und :other sollten unterschiedlich sein.',
    'digits' => ':attribute müssen :digits Ziffern sein.',
    'digits_between' => ':attribute muss zwischen :min und :max Ziffern sein.',
    'dimensions' => ':attribute hat ungültige Bildmasse',
    'distinct' => ':attribute Feld hat einen doppelten Wert.',
    'email' => ':attribute muss eine gültige Email-Adresse sein.',
    'ends_with' => ':attribute muss mit einem der folgenden Zeichen enden: :values',
    'exists' => 'Das gewählte :attribute ist ungültig.',
    'file' => ':attribute muss ein File sein.',
    'filled' => ':attribute Feld muss einen Wert haben.',
    'gt' => [
        'numeric' => ':attribute muss grösser sein als :value.',
        'file' => ':attribute muss grösser sein als :value kilobytes.',
        'string' => ':attribute mus grösser :value Zeichen haben.',
        'array' => ':attribute muss mehr als :value Elemente haben.',
    ],
    'gte' => [
        'numeric' => ':attribute muss grösser oder gleich :value sein.',
        'file' => ':attribute muss grösser oder gleich :value kilobytes sein.',
        'string' => ':attribute muss grösser oder gleich :value Zeichen haben.',
        'array' => ':attribute muss mindestens :value Elemente enthalten.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Das gewählte :attribute ist ungültig.',
    'in_array' => ':attribute Feld existiert nicht in :other.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'ip' => ':attributemuss eine gültige IP Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4 Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6 Adresse sein.',
    'json' => ':attribute muss eine gültig JSON string sein.',
    'lt' => [
        'numeric' => ':attribute muss kleiner als :value sein.',
        'file' => ':attribute muss kleiner als :value Kilobytes sein.',
        'string' => ':attribute muss kleiner als :value Zeichen sein.',
        'array' => ':attribute muss weniger als :value Elemente enthalten.',
    ],
    'lte' => [
        'numeric' => ':attribute muss kleiner oder gleich als :value sein.',
        'file' => ':attribute muss kleiner oder gleich :value Kilobytes sein.',
        'string' => ':attribute muss kleiner oder gleich :value Zeichen enthalten.',
        'array' => ':attribute darf nicht mehr als :value Zeichen enthalten.',
    ],
    'max' => [
        'numeric' => ':attribute darf nicht grösser als :max sein.',
        'file' => ':attribute darf nicht grösser als :max Kilobytes sein.',
        'string' => ':attribute darf nicht mehr als :max Zeichen enthalten.',
        'array' => ':attribute darf nicht mehr als :max Elemente enthalten.',
    ],
    'mimes' => ':attribute muss ein File des Typs: :values sein.',
    'mimetypes' => ':attribute muss ein File des Typs: :values sein.',
    'min' => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file' => ':attribute muss mindestens :min Kilobytes sein.',
        'string' => ':attribute muss mindestens :min Zeichen enthalten.',
        'array' => ':attribute muss mindestens :min Elemente enthalten.',
    ],
    'not_in' => 'Das gewählte :attribute ist ungültig.',
    'not_regex' => ':attribute Format ist ungültig.',
    'numeric' => ':attribute muss eine Zahl sein.',
    'password' => 'Das Passwort ist nicht korrekt',
    'present' => 'Das :attribute Feld muss ausgefüllt sein.',
    'regex' => ':attribute Format ist ungültig.',
    'required' => ':attribute Feld muss ausgefüllt sein.',
    'required_if' => ':attribute Feld ist notwendig wenn :other :value ist.',
    'required_unless' => ':attribute Feld notwendig, ausser :other ist in :values.',
    'required_with' => ':attribute Feld ist notwendig, wenn :values vorhanden sind.',
    'required_with_all' => ':attribute Feld ist notwendig, wenn :values vorhanden sind.',
    'required_without' => ':attribute Feld ist notwendig, wenn :values nicht vorhanden sind.',
    'required_without_all' => ':attribute Feld ist notwendig, wenn :values nicht vorhanden sind.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => ':attribute muss :size sein.',
        'file' => ':attribute muss :size Kilobytes sein.',
        'string' => ':attribute muss :size Zeichen haben.',
        'array' => ':attribute muss :size Elemente haben.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Zeichen beginnen: :values',
    'string' => ':attribute muss eine Zeichenfolge sein.',
    'timezone' => ':attribute muss eine gültige Zeitzone sein.',
    'unique' => ':attribute wurde bereits verwendet.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => ':attribute Format ist ungültig.',
    'uuid' => ':attribute muss eine gültige UUID sein.',
    'wrong_password' => 'Falsches Passwort',
    'multiple_of' => ':attribute must be a multiple of one of the following values: :value',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'Name',
        'username'              => 'Benutzername',
        'email'                 => 'E-Mail Adresse',
        'first_name'            => 'Vorname',
        'last_name'             => 'Nachname',
        'password'              => 'Passwort',
        'password_confirmation' => 'Passwort Bestätigung',
        'city'                  => 'Stadt',
        'country'               => 'Land',
        'address'               => 'Adresse',
        'phone'                 => 'Telefonnummer',
        'mobile'                => 'Handynummer',
        'age'                   => 'Alter',
        'sex'                   => 'Geschlecht',
        'gender'                => 'Geschlecht',
        'day'                   => 'Tag',
        'month'                 => 'Monat',
        'year'                  => 'Jahr',
        'hour'                  => 'Stunde',
        'minute'                => 'Minute',
        'second'                => 'Sekunde',
        'title'                 => 'Titel',
        'content'               => 'Inhalt',
        'description'           => 'Beschreibung',
        'excerpt'               => 'Auszug',
        'date'                  => 'Datum',
        'time'                  => 'Uhrzeit',
        'available'             => 'verfügbar',
        'size'                  => 'Größe',
    ],
];
