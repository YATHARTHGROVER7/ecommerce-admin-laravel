<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsShippingRateWeight extends Model
{
    use HasFactory;
    protected $primaryKey = 'srw_id';
    protected $table = 'cs_shipping_rate_weight';
}