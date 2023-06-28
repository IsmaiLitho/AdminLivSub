<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class campaniaPCSbbQa extends Model
{
    use HasFactory;
    protected $connection = 'pc_sbb_qa';
    protected $table = 'campanias';
    protected $fillable = [
        'nombre',
        'fechaRegistro',
        'desde',
        'horaActivaDesde',
        'horaDesde',
        'hasta',
        'horaActivaHasta',
        'horaHasta',
        'mesesLiverpool',
        'mesesExternas',
        'bannerMovil',
        'bannerWeb',
        'footer',
        'footerLeyenda',
        'archivoTerminosCondiciones',
    ];

    public $timestamps = false;
}
