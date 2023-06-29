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
                        <h5 class="card-title">Campañas registradas</h5>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="inputAmbiente" class="form-label">Ambiente:</label>
                                    <div id="loader" style="display:inline-block;"></div>
                                    <select class="form-control" name="ambiente" id="ambiente">
                                        <option class="text-center" value="">---------------------- Seleccione una opción ----------------------</option>
                                        <option value="QA">QA</option>
                                        <option value="Produccion">Producción</option>
                                    </select>
                                    <input type="text" name="tienda" id="tienda" value="{{ Auth::user()->tienda }}" hidden readonly>
                                </div>                        
                            </div>
                        </div>
                        <a href="{{ url(Auth::user()->tienda.'/pif/nueva-campania') }}" class="mt-4 mb-4 btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva campaña</a>
                        @if(Session::has('m'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                {{Session::get('m')}}
                            </div>
                        @endif
                        <div class="campanias-pif-table" hidden>
                            <table class="table display" id="campanias-pif" style="width:100%">
                                <thead style="background-color: {{ Auth::user()->color }}; color: #fff;">
                                    <tr>                                    
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Fecha de la promoción</th>
                                        <th>Terminos y condiciones</th>
                                        <th>Operaciones</th>
                                    </tr>
                                </thead>                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    
    <script src="{{ asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script>
        let ambiente = '';
        let tienda = '{{ Auth::user()->tienda }}';
        let url = '';

        $('#ambiente').on('change', function() {
            //$('#campanias-pif').find('tbody').remove();
            
            ambiente = $(this).val();
            url = `{{ url('/${tienda}/${ambiente}/pif/getCampanias') }}`;
            //console.log(url);

            $('.campanias-pif-table').removeAttr('hidden',true);

            $('#campanias-pif thead tr').addClass('filters').appendTo('#campanias-pif thead');

            var table = $('#campanias-pif').DataTable({
                ajax: url,
                processing: true,
                serverSide: true,
                //responsive:true,
                //autoWhith:false,

                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Resultados obtenidos _MAX_ de registros totales)",
                    "search": "Buscar:",
                    "loadingRecords": "Cargando...",
                    "paginate": {
                        "first":"First",
                        "last":"Last",
                        "next":"Siguiente",
                        "previous":"Anterior"
                    },
                },

                columns: [
                    {data: 'id', name:'id' },
                    {data: 'promotionName', name:'promotionName' },                   
                    {data: 'fecha', name:'fecha' },
                    {
                        data: 'footerActivo', 
                        name:'footerActivo',
                        render: function(data, type) {
                            if (type === 'display') {
                                if (data == 0) {
                                    return `<span class="badge text-bg-danger" style="background-color:#dc3545">Términos y condiciones desactivados</span>`;
                                }
                                if (data == 1) {
                                    return `<span class="badge text-bg-success" style="background-color:#198754">Términos y condiciones activados</span>`;
                                }                        
                            }
                             
                            return data;
                        }
                    },
                    {data: 'operaciones', name: 'operaciones'}
                ],            
            });
        });
        

    </script>
    
@endsection