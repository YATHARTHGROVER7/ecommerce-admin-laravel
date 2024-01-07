<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsProductTermsImage extends Model
{
    use HasFactory;
    protected $primaryKey= 'pti_id';
	protected $table= 'cs_product_terms_image';
}