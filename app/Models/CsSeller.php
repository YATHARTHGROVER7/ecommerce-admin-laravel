<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsSeller extends Model
{
    use HasFactory;
    protected $primaryKey = 'seller_id'; 
	protected $table = 'cs_seller';
}