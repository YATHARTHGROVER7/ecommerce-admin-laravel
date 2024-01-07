<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsShippingAgency extends Model
{
    use HasFactory;
    protected $primaryKey = 'agency_id';
    protected $table = 'cs_shipping_agency';
}