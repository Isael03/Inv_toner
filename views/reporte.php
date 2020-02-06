<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Reportes</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">



  <!-- Custom styles for this template-->
  <link href="../resources/css/sb-admin.css" rel="stylesheet">

  <!-- toastr -->
  <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">
  <link rel="stylesheet" href="../resources/css/main.css">


</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top shadow-sm">

    <button class="btn btn-link btn-sm text-white order-0 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <a class="navbar-brand ml-2" href="../index.php">Inventario</a>

    <!-- Navbar -->
    <ul class="nav navbar-nav d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <li class="nav-item">
        <a class="btn nav-link menu-btn" href="views/login.php"><i class="fas fa-sign-out-alt text-white"></i></a>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="../">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./impresoras.php">
          <i class="fas fa-print"></i>
          <span>Impresoras</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./historial.php">
          <i class="fas fa-history"></i>
          <span>Historial de entregas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./reporte.php">
          <i class="fas fa-table"></i>
          <span>Reporte de entregas</span>
        </a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb mb-3">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">Reportes de entregas</a>
          </li>
        </ol>

        <!-- Contenido -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Dirección</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
          </li> -->
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

            <!-- formulario desde-hasta -->
            <p>Buscar:</p>
            <form>
              <div class="form-row align-items-center">
                <div class="col-auto">
                  <label class="sr-only" for="inlineFormInput">Desde</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Desde</div>
                    </div>
                    <input type="date" class="form-control" id="inicio-gral" placeholder="Username">
                  </div>
                </div>
                <div class="col-auto">
                  <label class="sr-only" for="inlineFormInputGroup">Hasta</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Hasta</div>
                    </div>
                    <input type="date" class="form-control" id="termino-gral" placeholder="Username">
                  </div>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-2" id="btn-searchGeneral">Buscar</button>
                </div>
              </div>
            </form>

            <div id="linkToPdf" class="text-center mt-3"></div>

            <section class="mt-3 container-sm">
              <h1 class="text-center mb-5 container-sm" id="title_report">
                Entregas
              </h1>
              <div class="row">

                <div class="col-md-8 col-xs-12 mx-auto">
                  <div class="table-responsive-sm text-center">
                    <table class="table table-sm table-hover table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Departamento</th>
                          <th scope="col">Cant. de elementos entregados</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-depart">

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row mt-5">
                <div class="col-md-8 col-xs-12 mx-auto">
                  <div class="table-responsive-sm text-center">
                    <table class="table table-sm table-hover table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Marca</th>
                          <th scope="col">Modelo</th>
                          <th scope="col">Tipo</th>
                          <th scope="col">Cantidad</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-model">

                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </section>
          </div>

          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <p>Buscar:</p>
            <form>
              <div class="form-row align-items-center">
                <div class="col-auto">
                  <label class="sr-only" for="inlineFormInput">Desde</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Desde</div>
                    </div>
                    <input type="date" class="form-control" id="inicio_dep">
                  </div>
                </div>
                <div class="col-auto">
                  <label class="sr-only" for="inlineFormInputGroup">Hasta</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Hasta</div>
                    </div>
                    <input type="date" class="form-control" id="termino_dep">
                  </div>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-2" id="btn-searchDep">Generar</button>
                </div>
              </div>
            </form>

            <div class="row mt-4">
              <div class="col-md-8 col-xs-12 mx-auto">
                <div class="table-responsive-sm text-center">
                  <table class="table table-sm table-hover table-striped">
                    <thead>
                      <tr>
                        <th scope="col">Dirección</th>
                        <th scope="col">Acción</th>
                      </tr>
                    </thead>
                    <tbody id="tbody-dep">

                    </tbody>
                  </table>
                </div>

              </div>
            </div>

          </div>
          <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div> -->
        </div>



      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->





  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Toastr -->
  <script src="../vendor/toastr/toastr.min.js"></script>

  <!-- Page level plugin JavaScript-->

  <script src="../vendor/datatables/jquery.dataTables.js"></script>
  <script src="../vendor/datatables/datatables.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../resources/js/sb-admin.min.js"></script>

  <!-- OWN-->
  <script src="../resources/js/datatableES.js"></script>
  <script src="../resources/js/reporte.js"></script>
  <script src="../resources/js/main.js"></script>


</body>

</html>