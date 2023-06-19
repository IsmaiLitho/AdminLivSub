@extends('layouts.auth')

@section('content')
    <div class="d-flex justify-content-center py-2">
        <div class="d-flex align-items-center w-auto">
            <div class="p-2" style="background-color: #e10098;">
                <img src="{{ asset('assets/img/logo_liverpool.svg') }}" width="150" height="60">
            </div>
            <div class="p-2" style="background-color: #552166;">
                <img src="{{ asset('assets/img/suburbia_2023.svg') }}" width="150" height="60"><br>
            </div>
            <!--<span class="d-none d-lg-block">Club de Asistencia</span>--->
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Administrador para campañas de Protección Celular y Protección Integral Familiar</h5>
                <!--<p class="text-center small"></p>-->
            </div>

            <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="col-12">
                    <label for="yourUsername" class="form-label">Correo Electrónico</label>
                    <div class="input-group has-validation">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label for="yourPassword" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Iniciar Sesión</button>
                </div>
                
            </form>
        </div>
    </div>
@endsection
