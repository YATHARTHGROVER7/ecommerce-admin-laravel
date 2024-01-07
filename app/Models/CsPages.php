<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsPages extends Model
{
    use HasFactory;
    protected $table='cs_pages';
    protected $primaryKey='page_id';
}