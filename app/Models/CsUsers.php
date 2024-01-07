<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsUsers extends Model
{
    use HasFactory;
    protected $table='cs_users';
    protected $primaryKey='user_id';

}