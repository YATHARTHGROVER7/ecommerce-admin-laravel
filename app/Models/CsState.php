<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsState extends Model
{
    use HasFactory;
    protected $primaryKey = 'state_id'; 
    protected $table = 'cs_states';
}