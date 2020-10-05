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

    </head>
<body>

    <div class="alert alert-success" role="alert">
	<h4 class="alert-heading">Registro exitoso!</h4>
	<p>Copie sus credenciales de acceso y no los pierda</p>
	<p> Cliente      : <?= $data["cliente_id"]; ?></p>
	<p> Llave secreta: <?= $data["llave_secreta"]; ?></p>	
	<hr>
	<p class="mb-0">Con estas credenciales usted podr&aacute; utilizar nuestro sistema <a href="<?= base_url().'/index.php/home/iniciar'; ?>" class="alert-link">Colibri </a> </p>
    </div>

</body>
</html>
