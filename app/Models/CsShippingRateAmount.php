<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsShippingRateAmount extends Model
{
    use HasFactory;
    protected $primaryKey = 'sra_id';
    protected $table = 'cs_shipping_rate_amount';
}