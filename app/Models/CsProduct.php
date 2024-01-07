<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes; //add this line

class CsProduct extends Model
{
    use HasFactory;
    // use SoftDeletes; //add this line
    protected $primaryKey = 'product_id';

    public static function boot()
    {
        parent::boot();
        static::created(function ($brand) {
            $brand->product_slug = $brand->generateSlug($brand->product_name);
            $brand->save();
        });
    }
    public function generateSlug($name)
    {
        if (static::whereProductSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereProductName($name)->latest('product_id')->skip(1)->value('product_slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
               
            }
             return "{$slug}-1";
        }else{
            return $slug;
        }
        
    }   
	
	public function gallery()
    {
        return $this->hasMany('App\Models\CsProductGallery','gallery_product_id')->orderBy('gallery_id','ASC');
    } 

    public function highlight()
    {
        return $this->hasMany('App\Models\CsProductHighlight','highlight_product_id');
    }

    public function productAttributeDetails()
    {
        return $this->hasMany('App\Models\CsProductAttributeDetail','attribute_details_proid');
    }
    public function productVariations()
    {
        return $this->hasMany('App\Models\CsProductVariation','pv_product_id');
    }

    public function defaultVariation()
    {
        return $this->hasMany('App\Models\CsProductVariation','pv_product_id')->where('pv_default',1);
    }
	
	public function addons()
    {
        return $this->hasMany('App\Models\CsProductAddons','addons_product_id');
    }
	public function productTabs()
    {
        return $this->hasMany('App\Models\CsProductTabs','tab_product_id');
    }
	public function brand()
    {
        return $this->belongsTo('App\Models\CsProductBrand','product_brand_id');
    } 
	
	public function taxBelongsTo()
    {
        return $this->belongsTo('App\Models\CsTaxRates','product_tax_id');
    } 
}