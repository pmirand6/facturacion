<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingParameter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mercado_pago_rsn',
        'mercado_pago_alitaware',
        'commission_percent',
        'iva_percent',
        'commission_percent_rsn',
        'commission_percent_alitaware',
        'cost_until_20kg',
        'cost_higher_20kg',
        'cost_per_km_until_20kg',
        'cost_per_km_higher_20kg',
        'url_api_rsn',
        'url_api_alitaware',
        'user',
        'password',
        'client_id',
        'secret_key',
        'company',
        'email_admin_rsn',
    ];
}
