<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class campaniaSbbQa extends Model
{
    use HasFactory;
    protected $connection = 'pif_sbb_qa';
    protected $table = 'campanias';
    protected $fillable = [
        'bannerPromoActivo',
        'bannerMovil',
        'bannerWeb',
        'cuponActivo',
        'desde',
        'hasta',
        'footerActivo',
        'footerLeyenda',
        'footerArchivo',
        'eventLabel',
        'promotionName',
    ];
}
