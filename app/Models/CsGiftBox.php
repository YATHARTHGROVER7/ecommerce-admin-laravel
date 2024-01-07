<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class CsGiftBox extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $primaryKey= 'gift_box_id';
	protected $table= 'cs_gift_box';
	
    public static function boot()
    {
        parent::boot();
        static::created(function ($tag) {
            $tag->gift_box_slug = $tag->generateSlug($tag->gift_box_name);
            $tag->save();
        });
    }
    public function generateSlug($name)
    {
        if (static::whereGiftBoxSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereGiftBoxName($name)->latest('gift_box_id')->skip(1)->value('gift_box_slug');
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

    
}