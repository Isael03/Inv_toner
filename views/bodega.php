<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bodega</title>

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
                        <a href="#" class="text-decoration-none text-dark">Bodega</a>
                    </li>
                </ol>

                <div class="row">

                    <form class="col-lg-12 mb-5 collapse show" id="insertPrinter" novalidate>
                        <legend class="h5">Añadir bodega</legend>
                        <div class="form-row">
                            <div class="col-md-6 col-xs-12 col-lg-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" id="nuevaBodega" class="form-control" placeholder="Nombre de la bodega" aria-label="bodega" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" id="btnNuevabodega">Ingresar</button>
                            </div>

                        </div>
                    </form>

                    <form class="col-lg-12 mb-5 collapse" id="updatePrinter" novalidate>
                        <legend class="h5">Cambiar nombre</legend>
                        <div class="form-row">
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" id="updateNameStorage" placeholder="Marca" aria-label="Marca" aria-describedby="basic-addon1" required>
                                </div>
                            </div>

                            <div class="col-auto">
                                <button type="button" id="btnUpdateStorage" class="btn btn-primary">Modificar</button>
                            </div>

                        </div>
                    </form>


                    <!-- Tabla -->
                    <div class="table-responsive col-md-6 col-xs-12" id="contentTable">
                        <table class="table table-bordered text-center" id="tableStorage" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cant. de elementos</th>
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
                        <div class="copyright text-center my-auto">
                        </div>
                    </div>
                </footer>

            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Modal confirm delete -->
        <div class="modal" tabindex="-1" role="dialog" id="modalUpdateStorage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar nombre</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mx-auto mt-2 text-center w-50">
                            <label for="cantDelete" class="col-form-label text-left">Nuevo nombre:</label>
                            <input type="text" class="form-control w-100 mx-auto" id="newName" placeholder="Nombre" autofocus>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnModalUpdateStorage">Modificar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal confirm delete -->
        <div class="modal" tabindex="-1" role="dialog" id="modalDeleteStorage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar la eliminación de la bodega</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-center">¿Está seguro de borrar esta bodega?</p>
                        <small class="">*Todos los elementos en su interior tambien se eliminarán</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="btnModalDeleteStorage">Borrar</button>
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
        <script src="../resources/js/bodega.js"></script>

        <script src="../resources/js/main.js"></script>


</body>

</html>