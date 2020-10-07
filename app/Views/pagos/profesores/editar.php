<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/pagos/update/".$_POST["idPago"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "monto=".$_POST["monto"].
        "&fechaPago=".$_POST["fechaPago"].
        "&id_motivo=".$_POST["id_motivo"],
        CURLOPT_HTTPHEADER => array(
	    $_SESSION["auth"],
            "Content-Type: application/x-www-form-urlencoded",
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, $_SESSION["tam"]);
    $data = json_decode($data, true);

    $mensaje = $data["Detalles"];
    // Redireccion despues de insertar
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/pagos/listar_profesores';</script>";
    return;
}
else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/pagos/show_profesor/".$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"],
            "Content-Type: application/x-www-form-urlencoded",
                                    ),
                                   ));

    $response = curl_exec($curl);

    curl_close($curl);

    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, $_SESSION["tam"]);
    $data = json_decode($data, true);
    if ($data["Estado"] != 200)
    {
        $mensaje = $data["Detalles"];
        echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/pagos/listar_profesores';</script>";
    }
    $data = $data["Detalles"][0];
}

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
          "titulo"  => "PAGOS - PROFESOR",
          "nombre"  => $_SESSION["nombres"],                                                                                                       
          "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

?>

<!--main-container-part-->
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div id="content">
	<br><br>
	<!--Action boxes-->
	<div class="container-fluid">
	    <!--End-Action boxes-->    

	    <!--Chart-box-->    
	    <div class="row-fluid">
		<div class="widget-box">
		    <div class="widget-title bg_lg">
			<h3>Registrar nuevo pago</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post"  class="needs-validation" novalidate>
                            <input type="hidden" name="idPago" value="<?= $data["idPago"]; ?>"> </input>
			    <div class="form-group">
				<label for="id_profesor">Seleccione a un profesor</label>
				<select id="id_profesor" name="id_profesor" class="form-control" disabled>
					<option value="<?= $data["id_profesor"]; ?>"> <?= $data["nombres"]." ".$data["apellidos"]; ?></option>
				</select>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-6">
				    <label for="id_motivo">Seleccione el motivo del pago</label>
				    <select id="id_motivo" name="id_motivo" class="form-control" required>
					<?php foreach ($motivos as $motivo): ?>
					    <option <?php if ($motivo["idMotivo"] == $data["id_motivo"]) { echo "selected"; }?> value="<?= $motivo["idMotivo"]?>"> <?= $motivo["motivo"]; ?></option>
					<?php endforeach; ?> 
				    </select>
				</div>

				<div class="form-group col-md-6">
				    <label for="fechaPago">Fecha del pago</label>
				    <input type="date" class="form-control" value="<?= $data["fechaPago"];?>" name="fechaPago" id="fechaPago" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese una fecha v&aacute;lida
				    </div>
				</div>
			    </div>

			    <div class="form-group">
				<label for="monto">Ingrese el monto</label>
				<input type="number" step="0.01" class="form-control" value="<?= $data["monto"];?>" name="monto" id="monto"  required>
				<div class="valid-feedback">
				    Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				    Ingrese un monto;
				</div>
			    </div>

			    <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="<?= base_url().'/index.php/pagos/listar_profesores'; ?>" class="btn btn-danger"> Cancelar </a>
			</form>
			
		    </div>

		</div>
	    </div>
	</div>
	<!--End-Chart-box--> 
	<hr/>
    </div>
</main>


</div>
</div>

<script>
 // Example starter JavaScript for disabling form submissions if there are invalid fields
 (function() {
     'use strict';
     window.addEventListener('load', function() {
	 // Fetch all the forms we want to apply custom Bootstrap validation styles to
	 var forms = document.getElementsByClassName('needs-validation');
	 // Loop over them and prevent submission
	 var validation = Array.prototype.filter.call(forms, function(form) {
	     form.addEventListener('submit', function(event) {
		 if (form.checkValidity() === false) {
		     event.preventDefault();
		     event.stopPropagation();
		 }
		 form.classList.add('was-validated');
	     }, false);
	 });
     }, false);
 })();
</script>

<?php echo view("comun/pie"); ?>
