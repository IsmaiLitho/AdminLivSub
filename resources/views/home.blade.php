<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Administrador PIF</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <!--<link href="assets/img/favicon.png" rel="icon">-->
  <!--<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">--->

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">

  <script src="{{ asset('assets/js/jquery-3.6.0.min.js')}}"></script>

</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Bienvenido al admnistrador multitienda</h5>
                    <p class="text-center small">Para continuar, por favor seleccione la tienda a la que desea ingresar</p>
                  </div>

                  <form class="row g-3 needs-validation" method="POST" action="{{ route('seleccionar-tienda') }}">
                    @csrf
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input mt-4 @error('password') is-invalid @enderror" type="radio" name="tienda" id="liv" value="Liverpool">
                        <div id="sec_liverpool" class="p-2 d-flex flex-column align-items-center justify-content-center" style="background-color: #e10098;">
                          <img src="{{ asset('assets/img/logo_liverpool.svg') }}" alt="Liverpool">
                        </div>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input mt-4 @error('password') is-invalid @enderror" type="radio" name="tienda" id="sbb" value="Suburbia">
                        <div id="sec_suburbia" class="p-2 d-flex flex-column align-items-center justify-content-center" style="background-color: #552166;">                          
                          <img src="{{ asset('assets/img/suburbia_2023.svg') }}" alt="Suburbia">                        
                        </div>
                      </div>
                    </div>
                    @error('tienda')
                      <p class="text text-danger">{{ $message }}</p>
                    @enderror

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Ingresar</button>
                    </div>
                    
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js')}}"></script>

  <script>
    $('input[name=tienda]').on('change', function() {
        tienda = $(this).val();
        console.log(tienda);
        if (tienda == 'Liverpool') {
            $('#sec_liverpool').addClass('border border-5 border-primary mb-2 rounded');            
            $('#sec_suburbia').removeClass('border border-5 border-primary mb-2 rounded');
        } else {
            $('#sec_liverpool').removeClass('border border-5 border-primary mb-2 rounded');            
            $('#sec_suburbia').addClass('border border-5 border-primary mb-2 rounded');
        }
    });
  </script>

</body>

</html>