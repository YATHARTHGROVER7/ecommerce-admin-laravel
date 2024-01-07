<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsEmail extends Model
{
    use HasFactory;
    protected $primaryKey= 'email_id';
	protected $table= 'cs_email';
}