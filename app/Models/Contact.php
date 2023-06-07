<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @mixin IdeHelperContact
 */
class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'organisation_id',
        'tenant_id',
    ];

    public function store($data, Tenant $tenant)
    {
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'organisation_id' => $data['repair_organisation'] ?? null,
            'tenant_id' => $tenant->id,
        ];

        $this->fill($data);
        $this->save();

        return $this;
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
