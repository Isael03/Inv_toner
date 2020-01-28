<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Inventario de tóner de Informática" />
    <meta name="author" content="" />

    <title>Historial de retiros</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="../vendor/font-awesome/fontawesome.min.css" rel="stylesheet" />

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="../vendor/datatable/css/datatables.min.css" />


    <!-- Custom styles -->
    <link href="../resources/styles/simple-sidebar.css" rel="stylesheet" />
    <link href="../resources/styles/main.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- /#sidebar-wrapper -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center pb-3 bg-dark  font-weight-bold"><a href="../index.php" class="text-decoration-none text-white">
                    Inventario</a></div>
            <div class="list-group list-group-flush ">
                <a href="../index.php" class="list-group-item list-group-item-action bg-dark text-white">Inventario</a>
                <a href="./nuevo.php" class="list-group-item list-group-item-action bg-dark text-white">Añadir
                </a>
                <a href="./retiro.php" class="list-group-item list-group-item-action bg-dark text-white">Retirar
                </a>
                <a href="./historial.php" class="list-group-item list-group-item-action bg-dark text-white">Historial de
                    retiros</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg  border-bottom shadow-sm">
                <button class="btn" id="menu-toggle">
                    <span class="fas fa-angle-left fa-lg" id="icon-sidebar"></span>
                </button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fas fa-bars text-white"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="btn nav-link menu-btn" href="./login.php"><span class="fas fa-sign-out-alt "></span></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid bg-light">
                <section>
                    <h1 class="pt-3 mb-5">Historial de Retiros</h1>

                    <table id="tableHistorial" class=" table table-striped table-bordered display responsive nowrap " style="width:100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Retira</th>
                                <th>Entrega</th>
                                <th>Departamento</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tipo</th>
                                <th>Código</th>
                                <th>Bodega</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--Content table  -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Fecha</th>
                                <th>Retira</th>
                                <th>Entrega</th>
                                <th>Departamento</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tipo</th>
                                <th>Código</th>
                                <th>Bodega</th>
                            </tr>
                        </tfoot>
                    </table>

                </section>
            </div>
            <!-- /#page-content-wrapper -->
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery-v3.4.1.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Fontawesome -->
    <script src="../vendor/font-awesome/font-awesome-all.js"></script>

    <!-- Own -->
    <script src="../resources/scripts/sidebar-views.js"></script>


    <!-- Datatable -->
    <script src="../vendor/datatable/js/datatables.min.js"></script>
    <script src="../resources/scripts/datatableES.js"></script>

    <script src="../resources/scripts/main.js"></script>
    <script src="../resources/scripts/historial.js"></script>

</body>

</html>