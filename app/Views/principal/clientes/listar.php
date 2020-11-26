<?php

$_ENV["auth"] = "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ==";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "http://colibri.informaticapp.com/clientes",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
	$_ENV["auth"],
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

$clientes = $data["Detalles"];

?>

<main>

    <section class="jumbotron text-center">
	<div class="container">
	    <h1>Lista de Instituciones</h1>
	    <p class="lead text-muted">Instituciones registrados y usando los servicios de Colibri</p>
	</div>
    </section>

    <div class="album py-5 bg-light">
	<div class="container">
	    <div class="row">
		<?php foreach ($clientes as $cliente) :?>
		    <div class="col-md-4">
			<div class="card mb-4 shadow-sm">
			    <img class="bd-placeholder-img card-img-top" width="100%" height="225" src="<?= base_url().$cliente["foto"]; ?>" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"> <title><?= $cliente["correoCliente"]; ?></title><rect width="100%" height="100%" fill="#55595c"/> </img>
			    <div class="card-body">
				<p class="card-text"><?= $cliente["cliente"]; ?></p>
				<div class="d-flex justify-content-between align-items-center">
				    <div class="btn-group">
					<a href="<?= base_url().'/index.php/principal/reg_alumno/'.$cliente["idCliente"]; ?>" type="button" class="btn btn-sm btn-outline-primary">Contáctenos</a>
					<!-- <a href="<?= base_url().'/index.php/clientes/ver/'.$cliente["idCliente"]; ?>" type="button" class="btn btn-sm btn-outline-primary">Contáctenos</a> -->
					<!-- <a href="" type="button" class="btn btn-sm btn-outline-secondary">Editar</a> -->
					<!-- <a href="<?= base_url().'/index.php/clientes/delete/'.$cliente["idCliente"]; ?>" type="button" class="btn btn-sm btn-outline-danger">Eliminar</a> -->
					<a href="<?= $cliente["url"]; ?>" type="button" class="btn btn-sm btn-outline-primary">Ver Más</a>
				    </div>
				    <small class="text-muted"><?= $cliente["fechaContrato"]; ?></small>
				</div>
			    </div>
			</div>
		    </div>
		<?php endforeach; ?>
	    </div>
	</div>
    </div>

    
</main>
