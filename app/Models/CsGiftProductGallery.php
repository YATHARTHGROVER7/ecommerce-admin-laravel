<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsGiftProductGallery extends Model
{
    use HasFactory;
    protected $primaryKey = 'gallery_id';
    protected $table = 'cs_gift_product_gallery';
}
