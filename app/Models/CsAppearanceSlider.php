<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsAppearanceSlider extends Model
{
    use HasFactory;
    protected $table='cs_appearance_slider';
    protected $primaryKey='slider_id';
	
	public function category()
    {
        return $this->belongsTo('App\Models\CsCategory', 'slider_category');
    }
	
	public function tag()
    {
        return $this->belongsTo('App\Models\CsProductTags', 'slider_tags');
    }
}