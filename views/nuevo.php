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

      <div class="container-fluid pb-3">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mb-5">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">Añadir consumibles</a>
          </li>
        </ol>

        <!-- Form-->
        <form class="mx-5 mt-5 needs-validation" id="formNuevo" novalidate>
          <div class="form-group row">
            <label for="inputCantidad" class="col-sm-2 col-form-label">Cantidad</label>
            <div class="col-sm-10">
              <input type="number" name="cantidad" class="form-control col-md-6 mb-2 col-lg-4" id="inputCantidad" required />
            </div>
          </div>
          <div class="form-group row">
            <label for="inputMarca" class="col-sm-2 col-form-label">Marca</label>
            <div class="col-sm-10">
              <select class="custom-select my-1 mr-sm-2 mb-2 col-md-6 mb-2 col-lg-4" id="inputMarca" name="marca" required>
                <option value="" selected>Seleccione...</option>

              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="modelo_con" class="col-sm-2 col-form-label">Modelo</label>
            <div class="col-sm-10">
              <!-- <input type="text" name="modelo" class="form-control mb-2 col-md-6 mb-2 col-lg-4" id="inputModelo" required /> -->
              <input list="inputModelo" name="modelo" class="form-control mb-2 col-md-6 mb-2 col-lg-4" autocomplete="off" id="modelo_con" required>
              <datalist id="inputModelo">
              </datalist>

            </div>
          </div>
          <div class="form-group row">
            <label for="selectTipo" class="col-sm-2 col-form-label">Tipo</label>
            <div class="col-sm-10">
              <select class="custom-select my-1 mr-sm-2 mb-2 col-md-6 mb-2 col-lg-4" id="selectTipo" name="tipo" required>
                <option value="" selected>Seleccione...</option>
                <option value="Fusor">Fusor</option>
                <option value="Tinta">Tinta</option>
                <option value="Tambor">Tambor</option>
                <option value="Toner">Tóner</option>
                <option value="Tambor de residuo">Tambor de residuo</option>
                <option value="Tambor de arrastre">Tambor de arrastre</option>
                <option value="Correa de arrastre">Correa de arrastre</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="modelo_imp" class="col-sm-2 col-form-label">Impresora</label>
            <div class="col-sm-10">
              <select class="custom-select my-1 mr-sm-2 mb-2 col-md-6 mb-2 col-lg-4" id="modelo_imp" name="impresora" required>
                <option value="" selected>Seleccione...</option>
                <!--<option value="1">Manuel Orella</option>
                <option value="2">Informática</option> -->
              </select>
              <!--  <input list="inputModeloImpresora" name="impresora" class="form-control mb-2 col-md-6 mb-2 col-lg-4" id="modelo_imp" autocomplete="off" required>
              <datalist id="inputModeloImpresora">
              </datalist> -->
            </div>
          </div>
          <div class="form-group row">
            <label for="selectUbicacion" class="col-sm-2 col-form-label">Ubicación</label>
            <div class="col-sm-10">
              <select class="custom-select my-1 mr-sm-2 mb-2 col-md-6 mb-2 col-lg-4" id="selectUbicacion" name="ubicacion" required>
                <option value="" selected>Seleccione...</option>
                <option value="1">Manuel Orella</option>
                <option value="2">Informática</option>
              </select>
            </div>
          </div>
          <div class="mt-4">
            <a href="../index.php" class="mr-3">Volver</a>
            <button type="submit" class="btn btn-primary px-4" id="btnNuevoToner">
              Añadir
            </button>
          </div>

        </form>

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