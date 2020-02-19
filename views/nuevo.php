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

    <ul class="nav navbar-nav  form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <li class="nav-item"><a href="./nuevo.php" class="btn nav-link menu-btn text-white  mx-2" title="Añadir consumible"><i class="fas fa-plus-square"></i></a></li>
      <li class="nav-item">
        <a class="btn nav-link menu-btn" href="#"><i class="fas fa-sign-out-alt text-white"></i></a>
      </li>
    </ul>


  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active btn-sidebar">
        <a class="nav-link" href="../">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span>
        </a>
      </li>
      <li class="nav-item btn-sidebar">
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

      <div class="container-fluid pb-3">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mb-5">
          <li class="breadcrumb-item">
            <a href="./nuevo.php" class="text-decoration-none text-dark">Añadir consumibles</a>
          </li>
        </ol>

        <!-- tabs -->
        <ul class="nav nav-tabs" id="myTabAdd" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#existingConsumable" role="tab" aria-controls="existingConsumable" aria-selected="false">Existente</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#newConsumable" role="tab" aria-controls="newConsumable" aria-selected="true">Nuevo</a>
          </li>

        </ul>
        <div class="tab-content" id="myTabContentCon">

          <!-- tab nuevo -->
          <div class="tab-pane fade" id="newConsumable" role="tabpanel" aria-labelledby="newConsumable-tab">

            <div class="alert alert-warning text-center mt-3 " role="alert">
              Recuerda: Antes de añadir un nuevo consumible, asegúrese que las bodegas e impresoras esten añadidas en el sistema.
            </div>
            <!-- Form-->
            <div class="container">
              <div class="row">
                <div class="col d-flex justify-content-center">
                  <form class="mt-5 needs-validation" id="formNuevo" novalidate>
                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="inputCantidad" class=" col-form-label">Cantidad</label>
                        <div>
                          <input type="number" name="cantidad" class="form-control  mb-2 " id="inputCantidad" required min="1" value="1" />
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="inputMarca" class="col-form-label">Marca</label>
                        <div>
                          <select class="custom-select mr-sm-2" id="inputMarca" name="marca" required>
                            <option value="" selected>Seleccione...</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="modelo_con" class="col-form-label">Modelo</label>
                        <div>
                          <input list="inputModelo" name="modelo" class="form-control" autocomplete="off" id="modelo_con" required />
                          <!--  <datalist id="inputModelo"> </datalist> -->
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="selectTipo" class="col-form-label">Tipo</label>
                        <div>
                          <select class="custom-select mr-sm-2" id="selectTipo" name="tipo" required>
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
                    </div>

                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="modelo_imp" class="col-form-label">Impresora <a href="./impresoras.php"><span class="fas fa-plus-circle" title="Añadir impresora"></span></a></label>
                        <div>
                          <select class="custom-select mr-sm-2" id="modelo_imp" name="impresora" required>
                            <option value="" selected>Seleccione...</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="selectUbicacion" class="col-form-label">Ubicación<a href="./bodega.php" class="ml-2"><span class="fas fa-plus-circle" title="Añadir bodega"></span></a></label>
                        <div>
                          <select class="custom-select mr-sm-2" id="selectUbicacion" name="ubicacion" required>
                            <option value="" selected>Seleccione...</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="inputCantidad" class="col-form-label">Rango mínimo</label>
                        <div>
                          <input type="number" name="cantidad" class="form-control" id="rangoMinimo" required min="1" value="1" />
                        </div>
                      </div>

                      <div class="form-group col-lg-6 col-sm-12">
                        <label for="inputCantidad" class="col-form-label">Rango máximo</label>
                        <div>
                          <input type="number" name="cantidad" class="form-control" id="rangoMaximo" required min="1" value="1" />
                        </div>
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
              </div>
            </div>
          </div>

          <!-- tab existente -->
          <div class="tab-pane fade show active" id="existingConsumable" role="tabpanel" aria-labelledby="existingConsumable-tab">

            <div class="row mt-4">
              <form class="col-lg-12 mb-5 collapse show" id="insertPrinter" novalidate>
                <div class="form-row">
                  <div class="col-md-5 col-xs-12 col-lg-3">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Cantidad</span>
                      </div>
                      <input type="number" id="addMore" class="form-control" value="1" min="1" aria-label="addMore" aria-describedby="basic-addon1" required>
                    </div>
                  </div>
                  <div class="col-md-5 col-xs-12 col-lg-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Ubicacion</span>
                      </div>
                      <select class="custom-select " id="selectStorage" name="ubicacion" required>
                        <option value="" selected>Seleccione...</option>

                      </select>
                    </div>
                  </div>
                  <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="btnAddMore">Agregar</button>
                  </div>
                </div>
              </form>

              <!-- Tabla -->
              <div class="table-responsive col-md-12 col-xs-12">
                <table class="table table-bordered text-center display nowrap" id="tableListConsumable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Tipo</th>
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

        <!--end tabs  -->
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

  <?php
  include_once "../components/modalUpdate.php"
  ?>

  <!-- Modal eliminar -->
  <div class="modal" tabindex="-1" role="dialog" id='modalDeleteConsumable'>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Confirmar eliminación
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center" id="body-history">
          <p> ¿Esta seguro de que desea borrarlo?</p>
          <small class="text-danger">*Todos los elementos asociados se eliminarán</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger" id="btnDeleteAll">Borrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal -->

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