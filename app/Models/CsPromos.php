<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsPromos extends Model
{
    use HasFactory;
    protected $table='cs_promos';
    protected $primaryKey='promo_id';
}