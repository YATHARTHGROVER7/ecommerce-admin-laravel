<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CsReviewLikeUnlike extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	protected $primaryKey= 'rlu_id';
	protected $table = 'cs_review_like_unlike';
}



