<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Impresoras</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">

  <!-- Custom styles for this template-->
  <link href="../resources/css/sb-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="../resources/css/main.css">
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

    <ul class="nav navbar-nav  form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <li class="nav-item"><a href="./nuevo.php" class="btn nav-link menu-btn text-white mx-2" title="Añadir consumible"><i class="fas fa-plus-square"></i></a></li>
      <li class="nav-item">
        <a class="btn nav-link menu-btn" href="#"><i class="fas fa-sign-out-alt text-white"></i></a>
      </li>
    </ul>


  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item  btn-sidebar">
        <a class="nav-link" href="../">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span>
        </a>
      </li>
      <li class="nav-item btn-sidebar active">
        <a class="nav-link" href="./impresoras.php">
          <i class="fas fa-print"></i>
          <span>Impresoras</span>
        </a>
      </li>
      <li class="nav-item btn-sidebar">
        <a class="nav-link" href="./bodega.php">
          <i class="fas fa-warehouse"></i>
          <span>Bodegas</span>
        </a>
      </li>
      <li class="nav-item btn-sidebar">
        <a class="nav-link" href="./historial.php">
          <i class="fas fa-history"></i>
          <span>Historial de entregas</span>
        </a>
      </li>
      <li class="nav-item btn-sidebar">
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
            <a href="./impresoras.php" class="text-decoration-none text-dark">Impresoras</a>
          </li>
        </ol>

        <div class="row">

          <form class="col-lg-12 mb-3" id="insertPrinter" novalidate>
            <legend class="h5">Añadir impresora</legend>
            <div class="form-row">
              <div class="col-md-6 col-xs-12 col-lg-3">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Marca</span>
                  </div>
                  <input type="text" id="nuevaMarca" class="form-control" placeholder="Marca" aria-label="Marca" aria-describedby="basic-addon1" required>
                </div>
              </div>

              <div class="col-md-6 col-xs-12 col-lg-3">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Modelo</span>
                  </div>
                  <input type="text" class="form-control" id="nuevoModelo" placeholder="Modelo" aria-label="Modelo" aria-describedby="basic-addon1" required>
                </div>
              </div>

              <div class="col-auto">
                <input type="submit" class="btn btn-primary" id="btnNuevaImpresora" value="Ingresar">
              </div>

            </div>
          </form>

          <!-- Botones -->

          <div class="container mb-4">
            <div class="collapse" id="container-btn">
              <div class="btn-group" role="group" aria-label="btn-group-actions">
                <button type="button" class="btn btn-warning" title="Actualizar" id="PrinterUpdate"><span class='fas fa-wrench text-white'></span></button>
                <button type="button" class="btn btn-danger" title="Eliminar" id="PrinterDelete"><span class='fas fa-trash'></span></button>
              </div>
            </div>
          </div>


          <!-- Tabla -->
          <div class="table-responsive col-md-7 col-xs-12" id="contentTable">
            <table class="table table-bordered display" id="tablePrinters" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th></th>
                  <th>Marca</th>
                  <th>Modelo</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>


        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->


    <!-- Modal confirm delete -->

    <div class="modal" tabindex="-1" role="dialog" id="modalDeletePrinter">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar la eliminación de la impresora</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <p class="text-center">¿Está seguro de borrar esta impresora?</p>
            <small class="text-danger">*Todos los consumibles asociados a esta impresora también serán eliminados.</small>
          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <input type="button" class="btn btn-danger" id="btnModalDeletePrinter" value="Borrar">

          </div>
        </div>
      </div>
    </div>

    <!-- modal confirmacion -->
    <div class="modal" tabindex="-1" role="dialog" id="update-impresora">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modificar impresora</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="col-lg-12" id="updatePrinter" novalidate>
            <div class="modal-body">

              <div class="form-row">
                <div class="col-lg-6 col-sm-12">
                  <label for="validationDefault01">Marca</label>
                  <input type="text" class="form-control" id="updateMarcaPrinter" placeholder="Marca" aria-label="Marca" aria-describedby="basic-addon1" required>
                </div>
                <div class="col-lg-6 col-sm-12 ">
                  <label for="validationDefault02">Modelo</label>
                  <input type="text" class="form-control" id="updateModeloPrinter" placeholder="Modelo" aria-label="Modelo" aria-describedby="basic-addon1" required>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <input type="submit" id="btnUpdatePrinter" class="btn btn-primary" value="Modificar">
            </div>
          </form>
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
    <script src="../resources/js/impresora.js"></script>

    <script src="../resources/js/main.js"></script>


</body>

</html>