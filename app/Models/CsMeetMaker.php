<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsMeetMaker extends Model
{
    use HasFactory;
    protected $primaryKey = 'maker_id'; 
	protected $table = 'cs_meetmaker';
}