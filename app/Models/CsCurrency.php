<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsCurrency extends Model
{
    use HasFactory;
    protected $table='cs_currency';
    protected $primaryKey='currency_id';
}