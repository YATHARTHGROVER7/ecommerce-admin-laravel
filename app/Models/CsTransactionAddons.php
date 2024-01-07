<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsTransactionAddons extends Model
{
    use HasFactory;
    protected $table='cs_transaction_addons';
    protected $primaryKey='ta_id';
  
}