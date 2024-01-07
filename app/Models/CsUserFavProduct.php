<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class CsUserFavProduct extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $primaryKey= 'ufp_id';
	protected $table= 'cs_user_fav_product';
	
	public function product(){
        return $this->belongsTo('App\Models\CsProduct','ufp_product_id');
    }
 
}
