<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsPartner extends Model
{
    use HasFactory;
    protected $primaryKey= 'partner_id';
	protected $table= 'cs_partner';
}