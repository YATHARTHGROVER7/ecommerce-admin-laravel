<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class CsUserAddress extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $primaryKey= 'ua_id';

    public function shipstate()
    {
        return $this->belongsTo('App\Models\CsState', 'ua_ship_state');
    }

    public function shipcity()
    {
        return $this->belongsTo('App\Models\CsCities', 'ua_ship_city');
    }

    public function billstate()
    {
        return $this->belongsTo('App\Models\CsState', 'ua_bill_state');
    }

    public function billcity()
    {
        return $this->belongsTo('App\Models\CsCities', 'ua_bill_city');
    }
	
	public function countryBelongsTo()
    {
        return $this->belongsTo('App\Models\CsCountries', 'ua_country_id');
    }

    

   

    
}
