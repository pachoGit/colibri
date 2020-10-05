<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Iniciar Sesión · Colibri</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url().'/public/bootstrap-4.5.2/css/bootstrap.min.css';?>" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="<?= base_url().'/public/ayudas/signin.css'; ?>" rel="stylesheet">
  </head>
  <body class="text-center">
      <form action="<?= base_url().'/index.php/home/login' ?>" method="post" class="form-signin">
	  <img class="mb-4" src="<?= base_url().'/public/media/colibri.jpg'; ?>" alt="" width="72" height="72">
	  <h1 class="h3 mb-3 font-weight-normal">Iniciar Sesión</h1>
	  <label for="correo" class="sr-only">Correo Electrónico</label>
	  <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo electrónico" required autofocus>
	  <label for="contra" class="sr-only">Contraseña</label>
	  <input type="password" id="contra" name="contra" class="form-control" placeholder="Contraseña" required>
	  <div class="checkbox mb-3">
	      <label>
		  <input type="checkbox" value="recordar"> Recordar
	      </label>
	  </div>
	  <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
	  <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
      </form>
  </body>
</html>
