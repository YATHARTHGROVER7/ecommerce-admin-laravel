<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class CsAddonsCategory extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $primaryKey= 'addons_category_id';
	protected $table= 'cs_addons_category';
}