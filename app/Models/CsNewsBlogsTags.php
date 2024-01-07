<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CsNewsBlogsTags extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'cs_newsblogs_tags';
	protected $primaryKey= 'tag_id';
}