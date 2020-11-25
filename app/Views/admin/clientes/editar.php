<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (!empty($_FILES["foto"]["name"]))
    {
	$ruta = "/public/clientes/".$_FILES["foto"]["name"];
	$ruta2 = "/var/www/html/colibri/public/clientes/".$_FILES["foto"]["name"];
	move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta2);

	$curl = curl_init();
	curl_setopt_array($curl, array(
            CURLOPT_URL => "http://colibri.informaticapp.com/clientes/".$_POST["idCliente"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS =>
            "cliente=".$_POST["cliente"].
			    "&ruc=".$_POST["ruc"].
			    "&nombreEncar=".$_POST["nombreEncar"].
			    "&apellidoEncar=".$_POST["apellidoEncar"].
			    "&fechaContrato=".$_POST["fechaContrato"].
			    "&correoCliente=".$_POST["correoCliente"].
			    "&url=".$_POST["url"].
				"&terminos=".$_POST["terminos"].
				"&foto=".$ruta,
            CURLOPT_HTTPHEADER => array(
		"Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
            ),
        ));

	$response = curl_exec($curl);
	curl_close($curl);
    }
    else
    {
	$curl = curl_init();
	curl_setopt_array($curl, array(
            CURLOPT_URL => "http://colibri.informaticapp.com/clientes/".$_POST["idCliente"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS =>
            "cliente=".$_POST["cliente"].
			    "&ruc=".$_POST["ruc"].
			    "&nombreEncar=".$_POST["nombreEncar"].
			    "&apellidoEncar=".$_POST["apellidoEncar"].
			    "&fechaContrato=".$_POST["fechaContrato"].
			    "&correoCliente=".$_POST["correoCliente"].
			    "&url=".$_POST["url"].
			    "&terminos=".$_POST["terminos"],
            CURLOPT_HTTPHEADER => array(
		"Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
            ),
        ));

	$response = curl_exec($curl);
	curl_close($curl);

    }

    $data = json_decode($response, true);
    $mensaje = $data["Detalles"];

    echo "<script> alert('".$mensaje."');window.location.href='".base_url().'/index.php/admin'."' </script>";

}


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
    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
	<div class="form-group">
	    <label for="cliente">Nombre de la Instituci&oacute;n</label>
	    <input type="text" class="form-control" value="<?= $cliente["cliente"]; ?>" name="cliente" id="cliente" readonly>
	    <input type="hidden" class="form-control" value="<?= $id ?>" name="idCliente" id="idCliente">
	</div>

	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="nombreEncar">Nombres del encargado</label>
		<input type="text"  name="nombreEncar" value="<?= $cliente["nombreEncar"]; ?>" class="form-control" id="nombreEncar" required>
	    </div>
	    <div class="form-group col-md-6">
		<label for="apellidoEncar">Apellidos del encargado</label>
		<input type="text" value="<?= $cliente["apellidoEncar"]; ?>" name="apellidoEncar" class="form-control" id="apellidoEncar" required>
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-4">
		<label for="ruc">RUC</label>
		<input type="text" class="form-control" value="<?= $cliente["ruc"]; ?>" name="ruc" id="ruc" required minlength="11" maxlength="11" readonly>
	    </div>

	    <div class="form-group col-md-4">
		<label for="correoCliente">Correo electr&oacute;nico</label>
		<input type="email"  value="<?= $cliente["correoCliente"]; ?>" class="form-control" name="correoCliente" id="correoCliente" required>
	    </div>

	    <div class="form-group col-md-4">
		<label for="fechaContrato">Fecha de contrato</label>
		<input type="date" class="form-control" value="<?= $cliente["fechaContrato"]; ?>" name="fechaContrato" id="fechaContrato" readonly>
	    </div>
	</div>

	<div class="form-group">
	    <label for="url">URL del cliente</label>
	    <input type="text" value="<?= $cliente["url"]; ?>" class="form-control" name="url" id="url" required>
	</div>

	<div class="form-group">
	    <label for="terminos">Términos  y condiciones</label>
	    <textarea type="text" class="form-control" name="terminos" maxlength="2000" id="terminos"> <?= $cliente["terminos"]; ?> </textarea>
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	    <div class="invalid-feedback">
		Solo hasta 2000 caracteres
	    </div>
	</div>


	<div class="form-group">
	    <label for="foto">Escoja un foto</label>
	    <input type="file" accept="image/*" name="foto" class="form-control-file" id="foto">
	</div>

	<button type="submit" class="btn btn-primary">Aceptar</button>
        <a href="<?= base_url().'/index.php/admin'; ?>" class="btn btn-danger"> Cancelar </a>

    </form>

</main>
