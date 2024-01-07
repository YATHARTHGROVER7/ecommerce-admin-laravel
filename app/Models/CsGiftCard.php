<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class CsGiftCard extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $primaryKey= 'gift_card_id';
	protected $table= 'cs_gift_card';
	
    public static function boot()
    {
        parent::boot();
        static::created(function ($tag) {
            $tag->gift_card_slug = $tag->generateSlug($tag->gift_card_name);
            $tag->save();
        });
    }
    public function generateSlug($name)
    {
        if (static::whereGiftCardSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereGiftCardName($name)->latest('gift_card_id')->skip(1)->value('gift_card_slug');
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