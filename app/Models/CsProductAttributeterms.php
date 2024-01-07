<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsProductAttributeterms extends Model
{
    use HasFactory;
    protected $primaryKey = 'terms_id';
    protected $table = 'cs_product_attributeterms';
	
	public function belongsToTermsImage()
    {
        return $this->belongsTo('App\Models\CsProductTermsImage', 'pti_terms_id');
    }
}