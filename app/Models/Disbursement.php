<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Disbursement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_order',
        'provider_id',
        'provider_id_marketplace',
        'purchase_order',
        'amount_total_disbursement',
        'amount_application_fee',
        'amount_application_net_fee',
        'amount_rsn_fee_net',
        'amount_alitaware_fee_net',
        'invoice_number',
        'provider_notified',
        'amount_rsn_fee',
        'amount_alitaware_fee'
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'purchase_order', 'purchase_order');
    }
}
