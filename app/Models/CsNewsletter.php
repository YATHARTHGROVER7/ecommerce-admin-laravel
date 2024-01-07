<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsNewsletter extends Model
{
    use HasFactory;
    protected $table='cs_newsletter';
    protected $primaryKey='newsletter_id';
}