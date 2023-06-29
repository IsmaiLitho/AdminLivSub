@extends('layouts.app')

@section('content')

	<div class="pagetitle">
        <h1>Protección Integral Familiar Suburbia</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registrar nueva campaña</h5>
                        <form method="POST" action="{{ url('Suburbia/pif/guardar-campania') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12" id="sec_nombre">
                                    <label class="form-label">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <div class="row g-3" id="sec_fechain">
                                        <div class="col-md-4" id="sec_col_fechain">
                                            <label class="form-label">Fecha de publicación:</label>
                                            <input type="date" name="fechain" class="form-control @error('fechain') is-invalid @enderror" id="fechain">
                                            @error('fechain')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-2" id="sec_check_hora_in">
                                            <label class="form-check-label">Establecer hora de publición:</label><br>
                                            <input type="checkbox" name="check_hora_in" id="check_hora_in" class="form-check-input mt-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row g-3" id="sec_fechafin">
                                        <div class="col-md-4" id="sec_col_fechafin">
                                            <label class="form-label">Fecha de finalización:</label>
                                            <input type="date" name="fechafin" class="form-control @error('fechafin') is-invalid @enderror" id="fechafin">
                                            @error('fechafin')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-2" id="sec_check_hora_fin">
                                            <label class="form-check-label">Establecer hora de finalización:</label><br>
                                            <input type="checkbox" name="check_hora_fin" id="check_hora_fin" class="form-check-input mt-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title">Banners</h5>
                            <div class="row g-3">                                
                                <div class="col-md-6" id="sec_col_cat">
                                    <label class="form-label">Banner para Web:</label>
                                    <input type="file" name="bannerMovil" id="bannerMovil" class="form-control @error('bannerMovil') is-invalid @enderror">
                                    @error('bannerMovil')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6" id="sec_col_producto">
                                    <label class="form-label">Banner para Movil:</label>
                                    <input type="file" name="bannerWeb" id="bannerWeb" class="form-control @error('bannerWeb') is-invalid @enderror">
                                    @error('bannerWeb')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!---<h5 class="card-title">Promoción de tarjetas</h5>
                            <div class="row g-3">                                
                                <div class="col-md-6" id="sec_col_cat">
                                    <label class="form-label">Tarjetas Suburbia:</label>
                                    <select name="mesesLiverpool" class="form-control @error('mesesLiverpool') is-invalid @enderror" id="categorias">
                                        <option class="text-center" value="">---------------------- Seleccione una opción ----------------------</option>
                                        <option value="9">9 MSI</option>
                                        <option value="13">13 MSI</option>
                                    </select>
                                    @error('mesesLiverpool')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6" id="sec_col_producto">
                                    <label class="form-label">Tarjetas Externas:</label>
                                    <select name="mesesExternas" class="form-control @error('mesesLiverpool') is-invalid @enderror" id="productos">
                                        <option class="text-center" value="">---------------------- Seleccione una opción ----------------------</option>
                                        <option value="6">6 MSI</option>
                                        <option value="9">9 MSI</option>
                                    </select>
                                    @error('mesesLiverpool')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>--->
                            <h5 class="card-title">Términos y condiciones</h5>
                            <div class="row g-3">
                                <div class="col-md-12">                                    
                                    <div class="row g-3" id="sec_tc">
                                        <div class="col-md-2" id="sec_check_tc">
                                            <label class="form-label">Activar términos y condiciones:</label>
                                            <input type="checkbox" name="footer" id="footer_check" class="form-check-input mt-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 row g-3">
                                <div class="col-sm-4">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success" id="btn_guardar"><i class="bi bi-sd-card-fill"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')

    <script>
        let data = { 
            fechain:'',
            fechafin:'',
            check:false,
        };
        verifyTerminos(data);
    </script>
    <script src="{{ asset('assets/js/moment.js')}}"></script>
    <script src="{{ asset('assets/js/funciones_pc.js')}}"></script>

@endsection