<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsTransactionsGiftBox extends Model
{
    use HasFactory;
    protected $primaryKey= 'trans_id';
	protected $table= 'cs_transactions_gift_box';

    public function items(){
        return $this->hasMany('App\Models\CsTransactionGiftBoxDetails','td_trans_id');
    }

      
}