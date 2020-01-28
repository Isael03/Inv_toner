<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Inicio</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">

  <!-- <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css"> -->

  <!-- Custom styles for this template-->
  <link href="../resources/css/sb-admin.css" rel="stylesheet">

  <!-- toastr -->
  <link rel="../stylesheet" href="vendor/toastr/toastr.min.css">

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
        <a class="nav-link" href="./historial.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Historial</span>
        </a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb mb-5">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">Historial</a>
          </li>
        </ol>

        <div class="table-responsive">
          <table class="table table-bordered" id="tableHist" width="100%" cellspacing="0">
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
            <tfoot>
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
            </tfoot>
            <tbody>
            </tbody>
          </table>


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


    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>



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