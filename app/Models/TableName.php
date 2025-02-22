<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableName extends Model
{
    use HasFactory;
    protected $table='table_name';
    protected $primaryKey='pin_id';
	
	public function pinstate()
    {
        return $this->belongsTo('App\Models\CsState', 'pin_state_id');
    }
	public function pincity()
    {
        return $this->belongsTo('App\Models\CsCities', 'pin_city_id');
    }
}