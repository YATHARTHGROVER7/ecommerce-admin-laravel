<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsProductBrand extends Model
{
    use HasFactory;
    protected $table='cs_product_brand';
    protected $primaryKey='brand_id';
}