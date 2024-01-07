<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsWishlist extends Model
{
	use HasFactory;
	protected $primaryKey= 'wish_id';
	protected $table= 'cs_wishlist';
	
	public function product()
    {
        return $this->belongsTo('App\Models\CsProduct', 'wish_product_id');
    }
}