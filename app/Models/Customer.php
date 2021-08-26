<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_email',
        'identification_type',
        'identification_number',
        'customer_address',
        'first_name',
        'last_name',
        'zip_code',
        'street_name',
        'street_number',
        'country_code',
        'state_code',
        'erp_id',
        'locality_code',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
