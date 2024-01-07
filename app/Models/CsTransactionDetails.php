<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsTransactionDetails extends Model
{
    use HasFactory;
    protected $primaryKey= 'td_id';
	
	public function product()
    {
        return $this->belongsTo('App\Models\CsProduct', 'td_item_id');
    }
	
	public function taxrate()
    {
        return $this->belongsTo('App\Models\CsTaxRates', 'td_tax_id');
    }
	
	public function transaction()
    {
        return $this->belongsTo('App\Models\CsTransactions', 'td_trans_id');
    }
    public function seller()
    {
        return $this->belongsTo('App\Models\CsSeller', 'td_seller_id');
    }
}