<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsStaff extends Model
{
    use HasFactory;
    protected $primaryKey= 'staff_id';
    protected $table= 'cs_staffs';

	public function belongstoRole(){
       return $this->belongsTo(CsRole::class, 'staff_role');
    }
    
}
