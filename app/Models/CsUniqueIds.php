<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsUniqueIds extends Model
{
    use HasFactory;
    protected $table='cs_unique_ids';
    protected $primaryKey='ui_id';
}