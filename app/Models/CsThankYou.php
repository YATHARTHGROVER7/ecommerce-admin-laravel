<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsThankYou extends Model
{
    use HasFactory;
    protected $primaryKey= 'thankyou_id';
	protected $table= 'cs_thankyou';
}