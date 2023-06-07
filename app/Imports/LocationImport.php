<?php

namespace App\Imports;

use App\Models\ContactDetail;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\Organisation;
use App\Models\OrganisationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Throwable;

class LocationImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var null
     */
    private $responsible;

    /**
     * @var null
     */
    private $countryCode;

    public function __construct($locale = 'nl', $responsible = null, $countryCode = null)
    {
        $this->locale = $locale;
        $this->responsible = $responsible;
        $this->countryCode = $countryCode;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!$row) {
            return null;
        }

        $this->parseRow($row);

        if ($this->isInvalidRow($row)){
            return null;
        }

        try {
            DB::beginTransaction();

            $location = $this->updateOrCreateLocation($row);
            $organisation = $this->updateOrCreateOrganisation($row, $location);

            DB::commit();

            return $location;
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    private function parseRow(array &$row)
    {
        $this->parseTranslatedColumns($row);
        $row['type_of_organisation'] = $this->getRowColumnAlternatives($row, 'type_of_organisation_taxonomy', 'type_of_organisation', 'type_of_organisation_sharepair');
        $row['product_categories'] = $this->getRowColumnAlternatives($row, 'product_categories', 'types_of_products', 'product_types');
        $row['warranty'] = $this->getRowColumnAlternatives($row, 'warranty_yes_no_na', 'warranty');
        $row['warranty_description'] = $this->getRowColumnAlternatives($row, 'warranty_description');
        $row['description'] = $this->getRowColumnAlternatives($row, 'description');
        $row['product_description'] = $this->getRowColumnAlternatives($row,'description_of_the_products', 'products_description');
    }

    /**
     * Return the value if it is available in the row
     *
     * @param array $row
     * @param mixed ...$attributes
     * @return mixed
     */
    private function getRowColumnAlternatives(array $row, ...$attributes)
    {
        foreach ($attributes as $attribute) {
            if (isset($row[$attribute])) {
                return $row[$attribute];
            }
        }

        return null;
    }

    private function parseTranslatedColumns(array &$row)
    {
        $locales = config('app.supported_locales');
        foreach ($row as $column => $value) {
            foreach ($locales as $locale) {
                $columnLocaleSuffix = '_'.$locale;
                if (Str::endsWith($column, $columnLocaleSuffix)) {
                    $columnWithoutLocale = Str::beforeLast($column, $columnLocaleSuffix);
                    $row[$columnWithoutLocale][$locale] = $value;
                    unset($row[$column]);
                }
            }
        }
    }

    private function updateOrCreateLocation(array $row)
    {
        /** @var Location $location */
        $location = Location::updateOrCreate([
            'street' => $row['street'],
            'number' => $row['housenumber'],
            'postal_code' => $row['zipcode'],
            'city' => $row['city'],
        ], [
                'country' => $row['country'] ?? 'Belgium',
                'name' => $this->getPossibleTranslatedField($row['name']),
                'description' => $this->getPossibleTranslatedField($row['description']),
                'country_code' => $this->countryCode ?? Str::upper(Str::substr($row['country'] ?? 'Belgium', 0, 2)),
                'is_visible' => true,
            ]);

        $location->save();

        return $location;
    }

    private function updateOrCreateOrganisation(array $row, Location $location)
    {
        $hasWarranty = false;

        if ($row['warranty']) {
            if (Str::contains(Str::lower($row['warranty']), [
                'yes',
                'ja',
                'garantie',
                'maanden',
                1,
            ])) {
                $hasWarranty = true;
            }
        }

        if ($location->organisation) {
            return $location->organisation;
        }

        /** @var Organisation $organisation */
        $organisation = Organisation::create([
            'name' => $this->getPossibleTranslatedField($row['name']),
            'description' => $this->getPossibleTranslatedField($row['description']),
            'product_description' => $this->getPossibleTranslatedField($row['product_description']),
            'warranty_description' => $hasWarranty ? $this->getPossibleTranslatedField($row['warranty_description']) : null,
            'country_code' => $this->countryCode ?? Str::upper(Str::substr($row['country'], 0, 2)),
            'has_warranty' => $hasWarranty,
            'responsible_group' => $this->responsible ?? $row['responsible_group'],
            'is_visible' => true,
            'is_rc_active' => $row['active_in_repairconnects'],
        ]);

        $type = $this->findOrganisationType($row);
        $organisation->organisationType()->associate($type);

        $deviceTypes = $this->findDeviceTypes($row);
        if (!empty($deviceTypes)) {
            $organisation->deviceTypes()->saveMany($deviceTypes);
        } elseif (empty($organisation->getTranslations('product_description'))) {
            $organisation->setTranslation('product_description', $this->locale, $row['product_categories']);
        }
        $organisation->save();

        $contactDetails = $this->getContactDetails($row);
        $organisation->contactDetails()->saveMany($contactDetails);

        $organisation->location()->save($location);

        return $organisation;
    }

    private function findOrganisationType(array $row)
    {
        $code = $row['type_of_organisation'];

        return OrganisationType::where('code', $code)->first();
    }

    private function findDeviceTypes(array $row)
    {
        $deviceTypes = [];

        $deviceTypesStrings = explode(',', $row['product_categories']);
        $deviceTypesStrings = array_map('trim', $deviceTypesStrings);

        foreach ($deviceTypesStrings as $deviceTypesString) {
            if ($deviceTypesString) {
                $deviceType = DeviceType::where('code', $deviceTypesString)->first();

                if ($deviceType) {
                    $deviceTypes[$deviceType->id] = $deviceType;
                }
            }
        }

        return $deviceTypes;
    }

    public function getContactDetails(array $row)
    {
        $contacts = [];

        $this->newContact($contacts, $row['website'], ContactDetail::TYPE_WEBSITE);
        $this->newContact($contacts, $row['e_mail'], ContactDetail::TYPE_EMAIL);
        $this->newContact($contacts, $row['phone_number'], ContactDetail::TYPE_PHONE);
        $this->newContact($contacts, $row['mobile_number'], ContactDetail::TYPE_MOBILE);
        $this->newContact($contacts, $row['facebook'], ContactDetail::TYPE_FACEBOOK);
        $this->newContact($contacts, $row['instagram'], ContactDetail::TYPE_INSTAGRAM);
        $this->newContact($contacts, $row['linkedin'], ContactDetail::TYPE_LINKEDIN);
        $this->newContact($contacts, $row['google'], ContactDetail::TYPE_GOOGLE);

        return $contacts;
    }

    private function newContact(&$contacts, $contact, string $type)
    {
        if ($type === ContactDetail::TYPE_PHONE || $type === ContactDetail::TYPE_MOBILE) {
            $contact = str_replace([
                '=',
                '+',
            ], '', $contact);
        }

        if ($contact) {
            $contacts[] = new ContactDetail([
                'name' => Str::limit($contact, 252),
                'value' => $contact,
                'type' => $type,
            ]);
        }
    }

    private function isInvalidRow(array $row)
    {
        if (isset($row['name']) && is_array($row['name'])){
            foreach (config('app.supported_locales') as $locale){
                if ($row['name'][$locale] ?? null){
                    return false;
                }
            }
        }
        if (($row['name_nl'] ?? null) || ($row['name_en'] ?? null) || ($row['name_fr'] ?? null) || ($row['name_de'] ?? null)) {
            return false;
        }

        return true;
    }

    private function getPossibleTranslatedField(mixed $value)
    {
        if (is_array($value)){
            return $value;
        }

        return [$this->locale => $value];
    }
}
