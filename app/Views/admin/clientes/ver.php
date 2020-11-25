<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "http://colibri.informaticapp.com/clientes/".$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
	"Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

$cliente = $data["Detalles"][0];

?>



<main role="main" class="col-md-9 ml-sm-auto col-lg-10 py-md-4 px-md-4">
    <h2> Visualizar Institución </h2>
    <form  class="needs-validation" novalidate>
	<div class="form-group">
	    <label for="cliente">Nombre de la Instituci&oacute;n</label>
	    <input type="text" class="form-control" value="<?= $cliente["cliente"]; ?>" name="cliente" id="cliente" readonly>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="nombreEncar">Nombres del encargado</label>
		<input type="text"  name="nombreEncar" value="<?= $cliente["nombreEncar"]; ?>" class="form-control" id="nombreEncar" readonly>
	    </div>
	    <div class="form-group col-md-6">
		<label for="apellidoEncar">Apellidos del encargado</label>
		<input type="text" value="<?= $cliente["apellidoEncar"]; ?>" name="apellidoEncar" class="form-control" id="apellidoEncar" readonly>
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-4">
		<label for="ruc">RUC</label>
		<input type="text" class="form-control" value="<?= $cliente["ruc"]; ?>" name="ruc" id="ruc" required minlength="11" maxlength="11" readonly>
	    </div>

	    <div class="form-group col-md-4">
		<label for="correoCliente">Correo electr&oacute;nico</label>
		<input type="email"  value="<?= $cliente["correoCliente"]; ?>" class="form-control" name="correoCliente" id="correoCliente" readonly>
	    </div>

	    <div class="form-group col-md-4">
		<label for="fechaContrato">Fecha de contrato</label>
		<input type="date" class="form-control" value="<?= $cliente["fechaContrato"]; ?>" name="fechaContrato" id="fechaContrato" readonly>
	    </div>
	</div>

	<div class="form-group">
	    <label for="url">URL del cliente</label>
	    <input type="text" value="<?= $cliente["url"]; ?>" class="form-control" name="url" id="url" readonly>
	</div>

	<div class="form-group">
	    <label for="terminos">Términos  y condiciones</label>
	    <textarea type="text" class="form-control" name="terminos" maxlength="2000" id="terminos" readonly> <?= $cliente["terminos"]; ?> </textarea>
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	    <div class="invalid-feedback">
		Solo hasta 2000 caracteres
	    </div>
	</div>


	<div class="form-group">
	    <img src="<?= base_url().$cliente["foto"]; ?>" class="rounded mx-auto d-block" witdh="200" height="200">
	</div>

        <a href="<?= base_url().'/index.php/admin'; ?>" class="btn btn-primary"> Volver </a>
    </form>

</main>
