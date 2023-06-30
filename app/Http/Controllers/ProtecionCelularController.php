<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\campaniaPCSbb;
use App\Models\campaniaPCSbbQa;
use Carbon\Carbon;
use DataTables;
use Session;

class ProtecionCelularController extends Controller
{
    protected $fecha, $fecha_registro, $url_aws;

    public function __construct(){
        date_default_timezone_set("America/Mexico_City");
        $this->fecha = date("Y-m-d H:i:s");
        $this->fecha_registro = date("Y-m-d H:i:s",strtotime($this->fecha."-1 hour"));
        $this->url_aws = config('url_config.aws_url');
    }

    public function index($tienda){
        return view('tiendas.'.$tienda.'.pc.index');
    }

    public function getCampanias($tienda,$ambiente){        
        switch ($tienda) {
            case 'Suburbia':
                if ($ambiente == 'QA') {
                    $campanias = campaniaPCSbbQa::all();
                } else {
                    $campanias = campaniaPCSbb::all();
                }
                break;
            /*case 'Liverpool':
                $campanias = campaniaPCSbbQa::all();;
                break;*/
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
            return "<a class='btn btn-primary btn-sm' title='Editar campaña' href='".url($row->tienda.'/pc/editar-campania/'.$row->id)."'><i class='ri-edit-2-fill'></i></a>";
        })
        ->rawColumns(['operaciones'])
        ->toJson();
    }

    public function create($tienda){        
        return view('tiendas.'.$tienda.'.pc.create');
    }

    public function store(Request $r){
        $this->validate($r, [
            'fechain' => 'required',
            'fechafin' => 'required',
            'bannerMovil' => 'required|image|mimes:jpg,png',
            'bannerWeb' => 'required|image|mimes:jpg,png',
            'mesesLiverpool' => 'required',
            'mesesExternas' => 'required',
        ]);
        //return $r;

        $user = \Auth()->User();

        $movil = \Storage::disk('s3')->put('PC/'.$user->tienda.'/img',$r->file('bannerMovil'),'public');
        $bannerMovil = $this->url_aws . $movil;

        $web = \Storage::disk('s3')->put('PC/'.$user->tienda.'/img',$r->file('bannerWeb'),'public');
        $bannerWeb = $this->url_aws . $web;
        
        $archivo_tc = '';
        if ($r->hasFile('archivo_tc')) {
            $tc = \Storage::disk('s3')->put('PC/'.$user->tienda.'/pdf',$r->file('archivo_tc'),'public');
            $archivo_tc = $this->url_aws . $tc;
        }

        switch ($user->tienda) {
            case 'Suburbia':
                $qa = campaniaPCSbbQa::create([
                    'nombre' => $r->nombre,
                    'fechaRegistro' => $this->fecha_registro,
                    'desde' => $this->fecha_registro,
                    'hasta' => $r->fechafin,
                    'horaActivaDesde' => ($r->check_hora_in == 'on') ? 1 : 0,
                    'horaDesde' => ($r->check_hora_in == 'on') ? $r->hora_in : null,
                    'horaActivaHasta' => ($r->check_hora_fin == 'on') ? 1 : 0,
                    'horaHasta' => ($r->check_hora_fin == 'on') ? $r->hora_fin : null,
                    'mesesLiverpool' => $r->mesesLiverpool,
                    'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footer' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'archivoTerminosCondiciones' => ($r->footer == 'on') ? $archivo_tc : null,
                ]);

                $prod = campaniaPCSbb::create([
                    'nombre' => $r->nombre,
                    'fechaRegistro' => $this->fecha_registro,
                    'desde' => $r->fechain,
                    'hasta' => $r->fechafin,
                    'horaActivaDesde' => ($r->check_hora_in == 'on') ? 1 : 0,
                    'horaDesde' => ($r->check_hora_in == 'on') ? $r->hora_in : null,
                    'horaActivaHasta' => ($r->check_hora_fin == 'on') ? 1 : 0,
                    'horaHasta' => ($r->check_hora_fin == 'on') ? $r->hora_fin : null,
                    'mesesLiverpool' => $r->mesesLiverpool,
                    'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footer' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'archivoTerminosCondiciones' => ($r->footer == 'on') ? $archivo_tc : null,
                ]);
                break;
        }

        Session::flash('m',"Campaña para Protección Celular ".$user->tienda." registrada correctamente en qa y producción");
        return redirect($user->tienda.'/pc');
    }

    public function edit($tienda,$id){
        $campania = '';

        switch ($tienda) {
            case 'Suburbia':                
                $campania = campaniaPCSbb::find($id);
                break;
        }
        //return $campania;
        return view('tiendas.'.$tienda.'.pc.edit',compact('campania'));
    }

    public function update(Request $r){
        $this->validate($r, [
            'id' => 'required',
            'fechain' => 'required',
            'fechafin' => 'required',
            'bannerMovil' => 'nullable|image|mimes:jpg,png',
            'bannerWeb' => 'nullable|image|mimes:jpg,png',
            'mesesLiverpool' => 'required',
            'mesesExternas' => 'required',
        ]);

        $user = \Auth()->User();

        $bannerMovil = '';
        $bannerWeb = '';
        $archivo_tc = '';
        if ($r->hasFile('bannerMovil')) {
            $movil = \Storage::disk('s3')->put('PC/'.$user->tienda.'/img',$r->file('bannerMovil'),'public');
            $bannerMovil = $this->url_aws . $movil;
        }

        if ($r->hasFile('bannerWeb')) {
            $web = \Storage::disk('s3')->put('PC/'.$user->tienda.'/img',$r->file('bannerWeb'),'public');
            $bannerWeb = $this->url_aws . $web;
        }

        if ($r->hasFile('archivo_tc')) {
            $tc = \Storage::disk('s3')->put('PC/'.$user->tienda.'/pdf',$r->file('archivo_tc'),'public');
            $archivo_tc = $this->url_aws . $tc;
        }

        switch ($user->tienda) {
            case 'Suburbia':
                $qa = campaniaPCSbbQa::find($r->id);
                $prod = campaniaPCSbb::find($r->id);
                
                $qa->update([
                    'nombre' => $r->nombre,
                    'desde' => $this->fecha_registro,
                    'hasta' => $r->fechafin,
                    'horaActivaDesde' => ($r->check_hora_in == 'on') ? 1 : 0,
                    'horaDesde' => ($r->check_hora_in == 'on') ? $r->hora_in : null,
                    'horaActivaHasta' => ($r->check_hora_fin == 'on') ? 1 : 0,
                    'horaHasta' => ($r->check_hora_fin == 'on') ? $r->hora_fin : null,
                    'mesesLiverpool' => $r->mesesLiverpool,
                    'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footer' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'archivoTerminosCondiciones' => ($r->footer == 'on') ? (($archivo_tc == '') ? $qa->archivo_tc : $bannerMovil) : null,
                ]);

                $prod->update([
                    'nombre' => $r->nombre,
                    'desde' => $r->fechain,
                    'hasta' => $r->fechafin,
                    'horaActivaDesde' => ($r->check_hora_in == 'on') ? 1 : 0,
                    'horaDesde' => ($r->check_hora_in == 'on') ? $r->hora_in : null,
                    'horaActivaHasta' => ($r->check_hora_fin == 'on') ? 1 : 0,
                    'horaHasta' => ($r->check_hora_fin == 'on') ? $r->hora_fin : null,
                    'mesesLiverpool' => $r->mesesLiverpool,
                    'mesesExternas' => $r->mesesExternas,
                    'bannerMovil' => $bannerMovil,
                    'bannerWeb' => $bannerWeb,
                    'footer' => ($r->footer == 'on') ? 1 : 0,
                    'footerLeyenda' => ($r->footer == 'on') ? $r->footerLeyenda : null,
                    'archivoTerminosCondiciones' => ($r->footer == 'on') ? (($archivo_tc == '') ? $qa->archivo_tc : $bannerMovil) : null,
                ]);
                break;
        }

        Session::flash('m',"Campaña actualizada correctamente en qa y producción");
        return redirect($user->tienda.'/pc');
    }
    
    
}
