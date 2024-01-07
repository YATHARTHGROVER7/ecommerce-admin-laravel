<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CsNewsBlogsCategories extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table= 'cs_newsblogs_categories';
	protected $primaryKey= 'category_id';

    public function parent()
    {
        return $this->belongsTo('App\Models\CsNewsBlogsCategories', 'category_parent');
    }

    public function children()
    {
        return $this->hasMany('App\Models\CsNewsBlogsCategories', 'category_parent');
    }
    
}