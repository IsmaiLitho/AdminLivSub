<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\campaniaPifSbb;
use App\Models\campaniaPifSbbQa;
use Carbon\Carbon;
use DataTables;
use Session;

class PIFController extends Controller
{
    protected $fecha, $fecha_registro, $url_aws;

    public function __construct(){
        date_default_timezone_set("America/Mexico_City");
        $this->fecha = date("Y-m-d H:i:s");
        $this->fecha_registro = date("Y-m-d H:i:s",strtotime($this->fecha."-1 hour"));
        $this->url_aws = config('url_config.aws_url');
    }

    public function index($tienda){
        return view('tiendas.'.$tienda.'.pif.index');
    }

    public function getCampanias($tienda,$ambiente){        
        switch ($tienda) {
            case 'Suburbia':
                if ($ambiente == 'QA') {
                    $campanias = campaniaPifSbbQa::all();
                } else {
                    $campanias = campaniaPifSbb::all();
                }
                break;
            default:
                break;
        }

        $data = array();
        foreach ($campanias as $key => $c) {
            $fechaIn = Carbon::parse($c->desde)->locale('es')->isoFormat('DD [de] MMMM');
            $fechaFin = Carbon::parse($c->hasta)->locale('es')->isoFormat('DD [de] MMMM');

            $c->tienda = "Suburbia";
            $c->fecha = 'Del '.$fechaIn.' al '.$fechaFin.' de '.Carbon::parse($this->fecha_registro)->locale('es')->isoFormat('YYYY');
            array_push($data, $c);
        }
        
        return DataTables::of($data)
        ->addColumn('operaciones', function($row){
            return "<a class='btn btn-primary btn-sm' title='Editar campaña' href='".url($row->tienda.'/pif/editar-campania/'.$row->id)."'><i class='ri-edit-2-fill'></i></a>";
        })
        ->rawColumns(['operaciones'])
        ->toJson();
    }

    public function create($tienda){        
        return view('tiendas.'.$tienda.'.pif.create');
    }

    public function store(Request $r){
        $this->validate($r, [
            'fechain' => 'required',
            'fechafin' => 'required',
            'bannerMovil' => 'required|image|mimes:jpg,png',
            'bannerWeb' => 'required|image|mimes:jpg,png',
            //'mesesLiverpool' => 'required',
            //'mesesExternas' => 'required',
        ]);
        
        $user = \Auth()->User();
        $fechain = ($r->check_hora_in == 'on') ? $r->fechain.' '.date("H:i:s",strtotime($r->hora_in)) : $r->fechain.' 00:00:00';
        $fechafin = ($r->check_hora_fin == 'on') ? $r->fechafin.' '.date("H:i:s",strtotime($r->hora_fin)) : $r->fechafin.' 23:59:59';

        $movil = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/img',$r->file('bannerMovil'),'public');
        $bannerMovil = $this->url_aws . $movil;

        $web = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/img',$r->file('bannerWeb'),'public');
        $bannerWeb = $this->url_aws . $web;

        $archivo_tc = '';
        if ($r->hasFile('archivo_tc')) {
            $tc = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/pdf',$r->file('archivo_tc'),'public');
            $archivo_tc = $this->url_aws . $tc;
        }

        switch ($user->tienda) {
            case 'Suburbia':
                $qa = campaniaPifSbbQa::create([
                    'promotionName' => $r->nombre,
                    'desde' => $this->fecha_registro,
                    'hasta' => $fechafin,
                    //'mesesLiverpool' => $r->mesesLiverpool,
                    //'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footerActivo' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'footerArchivo' => ($r->footer == 'on') ? $archivo_tc : null,
                ]);

                $prod = campaniaPifSbb::create([
                    'promotionName' => $r->nombre,
                    'desde' => $fechain,
                    'hasta' => $fechafin,
                    //'mesesLiverpool' => $r->mesesLiverpool,
                    //'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footerActivo' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'footerArchivo' => ($r->footer == 'on') ? $archivo_tc : null,
                ]);
                break;
        }

        Session::flash('m',"Campaña para Protección Integral Familiar ".$user->tienda." registrada correctamente en qa y producción");
        return redirect($user->tienda.'/pif');
    }

    public function edit($tienda,$id){
        $campania = '';

        switch ($tienda) {
            case 'Suburbia':                
                $campania = campaniaPifSbb::find($id);
                break;
        }
        
        return view('tiendas.'.$tienda.'.pif.edit',compact('campania'));
    }

    public function update(Request $r){
        $this->validate($r, [
            'id' => 'required',
            'fechain' => 'required',
            'fechafin' => 'required',
            'bannerMovil' => 'nullable|image|mimes:jpg,png',
            'bannerWeb' => 'nullable|image|mimes:jpg,png',
            //'mesesLiverpool' => 'required',
            //'mesesExternas' => 'required',
        ]);

        $user = \Auth()->User();
        $fechain = ($r->check_hora_in == 'on') ? $r->fechain.' '.date("H:i:s",strtotime($r->hora_in)) : $r->fechain.' 00:00:00';
        $fechafin = ($r->check_hora_fin == 'on') ? $r->fechafin.' '.date("H:i:s",strtotime($r->hora_fin)) : $r->fechafin.' 23:59:59';

        $bannerMovil = '';
        $bannerWeb = '';
        $archivo_tc = '';
        if ($r->hasFile('bannerMovil')) {
            $movil = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/img',$r->file('bannerMovil'),'public');
            $bannerMovil = $this->url_aws . $movil;
        }

        if ($r->hasFile('bannerWeb')) {
            $web = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/img',$r->file('bannerWeb'),'public');
            $bannerWeb = $this->url_aws . $web;
        }

        if ($r->hasFile('archivo_tc')) {
            $tc = \Storage::disk('s3')->put('Pif/'.$user->tienda.'/pdf',$r->file('archivo_tc'),'public');
            $archivo_tc = $this->url_aws . $tc;
        }

        switch ($user->tienda) {
            case 'Suburbia':
                $qa = campaniaPifSbbQa::find($r->id);
                $prod = campaniaPifSbb::find($r->id);
                
                $qa->update([
                    'promotionName' => $r->nombre,
                    'desde' => $this->fecha_registro,
                    'hasta' => $fechafin,
                    //'mesesLiverpool' => $r->mesesLiverpool,
                    //'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => ($bannerMovil == '') ? $qa->bannerMovil : $bannerMovil,
                    'bannerWeb' => ($bannerWeb == '') ? $qa->bannerWeb : $bannerWeb,
                    'footerActivo' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'footerArchivo' => ($r->footer == 'on') ? (($archivo_tc == '') ? $qa->archivo_tc : $bannerMovil) : null,
                ]);

                $prod->update([
                    'promotionName' => $r->nombre,
                    'desde' => $fechain,
                    'hasta' => $fechafin,
                    //'mesesLiverpool' => $r->mesesLiverpool,
                    //'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => ($bannerMovil == '') ? $qa->bannerMovil : $bannerMovil,
                    'bannerWeb' => ($bannerWeb == '') ? $qa->bannerWeb : $bannerWeb,
                    'footerActivo' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'footerArchivo' => ($r->footer == 'on') ? (($archivo_tc == '') ? $qa->archivo_tc : $bannerMovil) : null,
                ]);
                break;
        }

        Session::flash('m',"Campaña para Protección Integral Familiar ".$user->tienda." actualizada correctamente en qa y producción");
        return redirect($user->tienda.'/pif');
    }
}
