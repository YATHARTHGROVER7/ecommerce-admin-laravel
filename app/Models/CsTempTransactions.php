<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsTempTransactions extends Model
{
    use HasFactory;
	protected $primaryKey = 'temp_trans_id'; 
	protected $table = 'cs_temp_transactions';
}