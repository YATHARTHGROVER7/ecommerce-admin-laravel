<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; //add this line

class CsProductVariation extends Model
{
    use HasFactory;
    // use SoftDeletes; //add this line
    protected $primaryKey = 'pv_id';
    protected $table = 'cs_product_variations';
}