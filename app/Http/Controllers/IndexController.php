<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function seleccionarTienda(Request $r){
        $this->validate($r, [
            'tienda' => 'required',
        ]);
        
        $user = \Auth()->User();
        switch ($r->tienda) {
            case 'Liverpool':
                $user->update([
                    'tienda' => 'Liverpool',
                    'logo' => 'logo_liverpool.svg',
                    'color' => '#e10098'
                ]);
                break;            
            case 'Suburbia':
                $user->update([
                    'tienda' => 'Suburbia',
                    'logo' => 'suburbia_2023.svg',
                    'color' => '#552166'
                ]);
                break;
        }

        return redirect('/dashboard/'.$user->tienda);
    }

    public function dashboard() {
        if (\Auth()->User()->tienda == null) {
            return redirect('/home');
        }

        return view('dashboard');
    }
}
