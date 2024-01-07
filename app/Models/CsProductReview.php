<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CsProductReview extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	protected $primaryKey= 'pr_id';
	protected $table = 'cs_product_review';

	 public function products()
    {
        return $this->belongsTo('App\Models\CsProduct', 'pr_product_id');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\CsUsers', 'pr_user_id');
    }
}



