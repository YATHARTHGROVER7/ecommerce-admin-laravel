<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsProductAddons extends Model
{
    use HasFactory;
    protected $primaryKey= 'addons_id';
	protected $table= 'cs_product_addons';
}