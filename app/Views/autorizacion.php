<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/registros/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>
        "nombres=".$_POST["nombres"].
        "&apellidos=".$_POST["apellidos"].
        "&email=".$_POST["email"],
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = substr($response, 0, -266);
    $data = json_decode($data, true);

    if ($data["Estado"] != 200)
    {
        var_dump($data);die;
	}

    $cred = ["data" => $data["credenciales"]];
    
    echo view("credenciales", $cred);
}

?>

<!doctype html>
<html lang="en">
    <head>
	<meta http-equiv="Content-type" content="text/html"  charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Jekyll v4.1.1">
	<title>Coliibri Software</title>

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
	<link href="<?php echo base_url().'/public/ayudas/floating-labels.css';?>" rel="stylesheet">
    </head>
<body>

  <form  method="post" class="form-signin">
    <div class="text-center mb-4">
      <img class="mb-4" src="<?php echo base_url().'/public/media/colibri.jpg';?>" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Autorizaci&oacute;n para Colibri</h1>
      <p> Por favor ingrese su nombre, apellido y un correo para recibir sus credenciales de autorizaci&oacute;n
    </div>

    <div class="form-label-group">
      <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres" required autofocus>
      <label for="nombres">Ingrese su nombre</label>
    </div>

    <div class="form-label-group">
      <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos" required autofocus>
      <label for="apellidos">Ingrese su apellido</label>
    </div>

    <div class="form-label-group">
      <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Correo electr&oacute;nico" required autofocus>
      <label for="inputEmail">Correo electr&oacute;nico</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Aceptar</button>
        <p class="mt-5 mb-3 text-muted text-center">Por favor si encuentras errores o bugs no le digan al profesor :) </p>
    <p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2020</p>
  </form>

</body>
</html>
