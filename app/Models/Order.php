<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'items', 'subtotal', 'shipping_cost',
        'total_amount', 'payment_status', 'order_status',
        'payment_gateway_ref', 'tracking_number', 'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'items'            => 'array',
        'subtotal'         => 'decimal:2',
        'shipping_cost'    => 'decimal:2',
        'total_amount'     => 'decimal:2',
    ];

    public function getTotalFormattedAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' MAD';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->order_status) {
            'new'        => 'badge-new',
            'processing' => 'badge-processing',
            'shipped'    => 'badge-shipped',
            'delivered'  => 'badge-delivered',
            'cancelled'  => 'badge-cancelled',
            default      => 'badge-new',
        };
    }
}
