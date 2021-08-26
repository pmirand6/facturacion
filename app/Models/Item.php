<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'item_code',
        'item_name',
        'item_quantity',
        'item_unit_price',
        'item_subtotal',
        'delivery_type',
        'delivery_cost',
        'delivery_cost_iva',
        'delivery_cost_net',
        'marketplace_fee_net',
        'marketplace_fee',
        'payment_status',
        'provider_id',
        'payment_id',
        'purchase_order',
        'disbursements_id'
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);

    }

    public function disbursement()
    {
        return $this->belongsTo(Disbursement::class);
    }
}
