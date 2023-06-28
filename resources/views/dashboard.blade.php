@extends('layouts.app')

@section('content')

	<div class="pagetitle">
        <h1>Dashboard {{ Auth::user()->tienda }}</h1>
    </div>

    <section class="section">
        <div class="row">
            @if(Auth::user()->tienda == 'Suburbia')
		        <div class="col-xxl-4 col-md-6">
            		<a href="{{ url(Auth::user()->tienda.'/pc') }}">
		                <div class="card info-card">
		                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
		                        <h5 class="card-title">Protección Celular</h5>
		                        <img src="{{ asset('assets/img/proteccion-celular-sbb.svg') }}" width="200" height="200">
		                    </div>
		                </div>
            		</a>
		        </div>
		        <div class="col-xxl-4 col-md-6">
            		<a href="">
		                <div class="card info-card">
		                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
		                        <h5 class="card-title">Protección Integral Familiar</h5>
		                        <img src="{{ asset('assets/img/logoPIFSbb.svg') }}" width="200" height="200">
		                    </div>
		                </div>
            		</a>
		        </div>
            @endif
        </div>
    </section>

@endsection