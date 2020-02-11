<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Historial</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">



  <!-- Custom styles for this template-->
  <link href="../resources/css/sb-admin.css" rel="stylesheet">

  <!-- toastr -->
  <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">


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
      <li class="nav-item ">
        <a class="nav-link" href="./bodega.php">
          <i class="fas fa-warehouse"></i>
          <span>Bodegas</span>
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
        <ol class="breadcrumb mb-5">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">Historial de entregas</a>
          </li>
        </ol>

        <div class="row">
          <!--   <div class="col-md-4 col-xs-12">  -->
          <!-- Select para seleccionar filtro -->
          <!--  <div class="form-group">
              <label>Filtrar por:</label>
              <select class="custom-select my-1 mr-sm-2 col-md-8 col-xs-4 form-control" id="filter">
                <option value="" selected>Seleccione...</option>
                <option value="mes">Mes</option>
                <option value="rango">Rango de fechas</option>
              </select>
            </div>
          </div> -->


          <div class="col-md-8 col-xs-12">
            <!-- Formulario para el mes-->

            <!--   <form class="form-inline mb-5 collapse" id="formMesHistorial">
              <div class="form-group">
                <label for="mes" class="my-1 mr-2">Mes</label>
                <select class="custom-select my-1 mr-sm-2" id="mes">
                  <option value="" selected>Seleccione el mes...</option>
                  <option value="01">Enero</option>
                  <option value="02">Febrero</option>
                  <option value="03">Marzo</option>
                  <option value="04">Abril</option>
                  <option value="05">Mayo</option>
                  <option value="06">Junio</option>
                  <option value="07">Julio</option>
                  <option value="08">Agosto</option>
                  <option value="09">Septiembre</option>
                  <option value="10">Obtubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
              <button type="button" class="btn btn-primary my-1" id="btnBuscarMes">Buscar</button>
            </form> -->

            <!-- Formulario por rango de fecha -->
            <form class="form-inline mb-5 " id="formRange">
              <div class="form-group">
                <label for="dateFrom" class="my-1">Desde</label>
                <input type="date" id="dateFrom" name="dateFrom" class="form-control mx-3 my-1" aria-describedby="dateFrom">
              </div>
              <div class="form-group">
                <label for="dateTo">Hasta</label>
                <input type="date" id="dateTo" name="dateTo" class="form-control mx-3 my-1" aria-describedby="dateTo">
              </div>
              <div class="form-group mx-3">
                <button type="button" class="btn btn-primary mx-2" id="btnBuscarRango">Buscar</button>
              </div>

            </form>
          </div>

        </div>



        <!-- Tabla -->
        <div class="table-responsive">
          <table class="table table-bordered text-center" id="tableHist" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Entrega</th>
                <th>Recibe</th>
                <th>Departamento</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Impresora</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>


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
    <script src="../resources/js/historial.js"></script>

    <script src="../resources/js/main.js"></script>


</body>

</html>