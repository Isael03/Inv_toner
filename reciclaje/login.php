<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Iniciar sesión</title>

  <!-- Font awesome -->
  <link rel="stylesheet" href="../vendor/font-awesome/fontawesome.min.css">
  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="../resources/styles/login.css">


</head>

<body>
  <div class="row m-0 justify-content-center align-items-center vh-100 bg-light">
    <form class="form-signin mx-auto col-md-3 border rounded col-xs-12 shadow-sm bg-white" action="../index.php">
      <fieldset class=" py-4 px-1">
        <h1 class="h3 mb-3 font-weight-normal text-center ">
          Iniciar sesión
        </h1>
        <div class="text-center mb-3 ">
          <span class="fas fa-user-circle fa-2x"></span>
        </div>

        <div class="form-group mb-4">
          <label for="inputUser">Usuario</label>
          <input type="text " id="inputUser" class="form-control" placeholder="Usuario " required autofocus />
        </div>
        <div class="form-group mb-5">
          <label for="inputPassword">Contraseña</label>
          <input type="password" id="inputPassword" class="form-control " placeholder="Contraseña" required />
        </div>
        <button class="btn btn-primary btn-block shadow-sm py-2" type="submit ">
          Ingresar
        </button>
        <div class="text-center mt-2">
          <a href="" class="">¿Olvido su contraseña?</a>
        </div>
      </fieldset>
    </form>
    </divc>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery-v3.4.1.js "></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js "></script>

    <!-- Font awesome -->
    <script src="../vendor/font-awesome/font-awesome-all.js"></script>

    <script src="../resources/scripts/main.js"></script>
</body>

</html>