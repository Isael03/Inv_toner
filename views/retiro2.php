<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Añadir</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">  -->
  <!--  <link rel="stylesheet" href="../vendor/bootstrap.min.css"> -->

  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">

  <!-- Custom styles for this template-->
  <link href="../resources/css/sb-admin.css" rel="stylesheet">

  <link href="../resources/css/main.css" rel="stylesheet" />

  <!-- toastr -->
  <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top shadow-sm">

    <a class="navbar-brand mr-1" href="index.html">Inventario</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

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
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Retiro</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.html">
          <i class="fas fa-history"></i>
          <span>Historial</span>
        </a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid pb-3">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mb-5">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">En construccion</a>
          </li>
        </ol>

        <!-- Form-->
        <form class="mx-5 mt-5 mb-5" id="formMesHistorial">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="fecha">Fecha</label>
              <input type="date" class="form-control" id="fecha" name="fecha" />
            </div>
          </div>
          <button type="submit" class="btn btn-primary mx-auto px-5" id="btnBuscarRetiro">
            Buscar
          </button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered" id="tableMO" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Impresora</th>
                <!--                   <th>Bodega</th> -->
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Impresora</th>
                <!--      <th>Bodega</th> -->
              </tr>
            </tfoot>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <!-- <span>Copyright © Your Website 2019</span> -->
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

  <!-- Page level plugin JavaScript-->
  <script src="../vendor/datatables/jquery.dataTables.js"></script>
  <!--   <script src="vendor/datatables/dataTables.bootstrap4.js"></script> -->
  <script src="../vendor/datatables/datatables.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../resources/js/sb-admin.min.js"></script>

  <!-- Toastr -->
  <script src="../vendor/toastr/toastr.min.js"></script>


  <script src="../resources/js/datatableES.js"></script>

  <!-- Own -->
  <script src="../resources/js/main.js"></script>
  <script src="../resources/js/new.js"></script>

</body>

</html>