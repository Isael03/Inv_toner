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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link rel="stylesheet" href="vendor/datatables/datatables.min.css">

  <!--<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css"> -->

  <!-- Custom styles for this template-->
  <link href="resources/css/sb-admin.css" rel="stylesheet">

  <!-- toastr -->
  <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
  <link rel="stylesheet" href="resources/css/main.css">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top shadow-sm">

    <button class="btn btn-link btn-sm text-white order-0 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <a class="navbar-brand ml-2" href="index.php">Inventario</a>

    <!-- Navbar -->
    <ul class="nav navbar-nav d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <li class="nav-item">
        <a class="btn nav-link menu-btn" href="#"><i class="fas fa-sign-out-alt text-white"></i></a>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./views/impresoras.php">
          <i class="fas fa-print"></i>
          <span>Impresoras</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./views/bodega.php">
          <i class="fas fa-warehouse"></i>
          <span>Bodegas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./views/historial.php">
          <i class="fas fa-history"></i>
          <span>Historial de entregas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./views/reporte.php">
          <i class="fas fa-table"></i>
          <span>Reporte de entregas</span>
        </a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-dark">Inventario de consumibles</a>
          </li>
        </ol>
        <div class="text-right mb-1">
          <a class='btn btn-primary text-white px-4 py-1 rounded-pill' id='btnAdd' href="./views/nuevo.php" title="Añadir" role="button">
            <span class="fas fa-plus"></span>
          </a>
        </div>


        <!-- Icon Cards-->
        <div class="row justify-content-center" id="rowCards">
          <!--  <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-boxes"></i>
                </div>
                <div class="text-center font-weight-bold" id="amount-inf">Calculando...</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left font-weight-bold">Informática</span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="text-center font-weight-bold" id="amount-mo">Calculando...</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left font-weight-bold">Manuel Orella</span>
              </a>
            </div>
          </div> -->
        </div>

        <ul class="nav nav-tabs mt-5 mb-3 text-capitalize" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">Todos</a>
          </li>
          <!--  <li class="nav-item">
            <a class="nav-link id-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">Informatica</a>
          </li>
          <li class="nav-item">
            <a class="nav-link id-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Manuel Orella</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  id-tab" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Bodega B</a>
          </li> -->
        </ul>

        <!-- Pestañas -->
        <div class="tab-content  mb-4" id="myTabContent">

          <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <!-- Datatable total -->
            <div class="table-responsive">
              <table class="table table-bordered display nowrap text-center" id="tableALL" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Impresora</th>
                    <!-- <th class="no-exportar">Acciones</th> -->
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="tab-1" role="tabpanel" aria-labelledby="tab-1">
            <div class="table-responsive">
              <table class="table table-bordered display nowrap text-center" id="tableINF" width="100%" cellspacing="0">
                <thead>
                  <tr>
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

          </div>

          <!-- Bodega A -->
          <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tab-2">

            <div class="table-responsive">
              <table class="table table-bordered display nowrap text-center" id="tableMO" width="100%" cellspacing="0">
                <thead>
                  <tr>
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
          </div>

          <!-- Bodega B -->
          <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="bodegaB-tab">
            <div class="table-responsive">
              <table class="table table-bordered display nowrap text-center" id="tableBODB" width="100%" cellspacing="0">
                <thead>
                  <tr>
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
          </div>
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


  <?php

  include "./components/modalDelete.php";
  include "./components/modalWithdraw.php";
  include "./components/modalUpdate.php";
  include "./components/modalTransfer.php";
  ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Toastr -->
  <script src="vendor/toastr/toastr.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/datatables.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="resources/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->

  <script src="resources/js/datatableES.js"></script>
  <script src="resources/js/main.js"></script>
  <script src="resources/js/index-datatable.js"></script>

</body>

</html>