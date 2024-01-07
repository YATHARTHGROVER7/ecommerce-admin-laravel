<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes; //add this line

class CsGiftProduct extends Model
{
    use HasFactory;
    // use SoftDeletes; //add this line
    protected $primaryKey = 'product_id';
    protected $table = 'cs_gift_products';
	
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

    public function giftgallery()
    {
        return $this->hasMany('App\Models\CsGiftProductGallery','gallery_product_id');
    } 

}