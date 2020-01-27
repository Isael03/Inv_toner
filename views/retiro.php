<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Inventario de tóner de Informática" />
    <meta name="author" content="" />

    <title>Retirar</title>

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="../vendor/datatable/css/datatables.min.css" />

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="../vendor/font-awesome/fontawesome.min.css" rel="stylesheet" />

    <!-- Toastr -->
    <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">

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
                            <a class="btn nav-link menu-btn" href="./login.php"><span class="fas fa-sign-out-alt"></span></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid bg-light">
                <section>
                    <h1 class="pt-3">Retirar</h1>
                    <form class="mx-5 mt-5 mb-5" id="formRetiroCons">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="bodega">Ubicación</label>
                                <select class="custom-select" id="bodega" name="bodega">
                                    <option value="" selected>Seleccione...</option>
                                    <option value="1">Bodega 1</option>
                                    <option value="2">Bodega 2</option>
                                    <option value="3">Informática</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="marca">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca" />
                            </div>
                            <div class="form-group col-md-4">
                                <!-- <label for="tipo">Tipo</label>
                                <select class="custom-select" id="tipo" name="tipo" required>
                                    <option value="" selected>Seleccione...</option>
                                    <option value="Fusor">Fusor</option>
                                    <option value="Tinta">Tinta</option>
                                    <option value="Tambor">Tambor</option>
                                    <option value="Toner">Tóner</option>
                                    <option value="Tambor de residuo">Tambor de residuo</option>
                                    <option value="Tambor de arrastre">Tambor de arrastre</option>
                                    <option value="Correa de arrastre">Correa de arrastre</option>
                                </select> -->
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" />
                            </div>
                            <button type="submit" class="btn btn-primary mx-auto px-5" id="btnBuscarRetiro">
                                Buscar
                            </button>
                        </div>

                    </form>
                    <div class="card">
                        <div class="card-header bg-white">
                            En existencia
                        </div>
                        <div class="card-body">
                            <div class="text-right mb-2">
                                <button class='btn btn-secondary' id='btnWithdraw' title="Retirar">
                                    <span class='fas fa-box-open fa-lg'></span>
                                </button>

                            </div>

                            <table id="tableRetiro" class=" table table-striped table-bordered display responsive nowrap " style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo</th>
                                        <th>Codigo de barra</th>
                                        <th>Ubicación</th>
                                        <!--   <th>Retirar</th> -->
                                    </tr>
                                </thead>
                                <tbody id="search-withdraw">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo</th>
                                        <th>Codigo de barra</th>
                                        <th>Ubicación</th>
                                        <!--  <th>Retirar</th> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </section>
            </div>
            <!-- /#page-content-wrapper -->
        </div>
    </div>
    <!-- /#wrapper -->
    <?php

    include "../resources/components/modalWithdraw.php";

    ?>

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery-v3.4.1.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Fontawesome -->
    <script src="../vendor/font-awesome/font-awesome-all.js"></script>

    <!-- Datatable -->
    <script src="../vendor/datatable/js/datatables.min.js"></script>
    <script src="../resources/scripts/datatableES.js"></script>
    <!-- Toastr -->
    <script src="../vendor/toastr/toastr.min.js"></script>

    <!-- Own -->
    <script src="../resources/scripts/sidebar-views.js"></script>
    <script src="../resources/scripts/main.js"></script>
    <script src="../resources/scripts/withdraw.js"></script>
    <script>

    </script>
</body>

</html>