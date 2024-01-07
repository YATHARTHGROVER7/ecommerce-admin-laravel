<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsTaxRates extends Model
{
    use HasFactory;
    protected $table='cs_tax_rates';
    protected $primaryKey='tax_id';
}