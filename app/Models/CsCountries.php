<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsCountries extends Model
{
    use HasFactory;
    protected $primaryKey= 'country_id';
	protected $table= 'cs_countries';
}