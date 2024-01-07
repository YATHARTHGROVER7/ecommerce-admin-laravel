<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsCurrencyRates extends Model
{
    use HasFactory;
    protected $table='cs_currency_rates';
    protected $primaryKey='cr_id'; 
}