<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsProductAttributeDetail extends Model
{
    use HasFactory;
	protected $table = 'cs_product_attribute_details';
    protected $primaryKey = 'attribute_details_id';

    public function attributes()
    {
        return $this->belongsTo('App\Models\CsProductAttribute', 'attribute_details_attid');
    }
	
	public function belongsToAttrImage()
    {
        return $this->belongsTo('App\Models\CsProductAttribute', 'attribute_details_attid')->where('attribute_type',2);
    }
}
