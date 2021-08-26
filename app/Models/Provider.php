<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{

    protected $fillable = [
        'provider_id',
        'provider_email',
        'identification_type',
        'identification_number',
        'provider_address',
        'first_name',
        'last_name',
        'zip_code',
        'street_name',
        'street_number',
        'country_code',
        'state_code',
        'erp_id',
        'locality_code',
        'marketplace_id',
        'access_token',
        'token_type',
        'expires_in',
        'scope',
        'refresh_token',
        'public_key',
    ];

    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class);
    }
}
