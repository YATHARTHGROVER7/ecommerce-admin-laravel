<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsContacts extends Model
{
    use HasFactory;
    protected $primaryKey= 'contact_id';
	protected $table= 'cs_contacts';
}