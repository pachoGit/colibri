<?php

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Soporte para las fotos

    $ruta = "/public/usuarios/".$_FILES["rutaFoto"]["name"];
    $ruta2 = $_SESSION["ruta"]."usuarios/".$_FILES["rutaFoto"]["name"];
    move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/usuarios/create",
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
        "&dni=".$_POST["dni"].
        "&sexo=".$_POST["sexo"].
        "&edad=".$_POST["edad"].
        "&rutaFoto=".$ruta.
        "&direccion=".$_POST["direccion"].
        "&correo=".$_POST["correo"].
        "&contra=".$_POST["contra"].
        "&id_perfil=".$_POST["id_perfil"].
        "&id_cliente=".$_SESSION["id_cliente"].        
        "&comentario=".$_POST["comentario"],
        CURLOPT_HTTPHEADER => array(
	    $_SESSION["auth"],
            "Content-Type: application/x-www-form-urlencoded",
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);
    if ($_SERVER["SERVER_NAME"] == "localhost")
    {
        // Puede que tengamos caracteres ocultos la final de la respuesta
        $data = substr($response, 0, $_SESSION["tam"]);
        $data = json_decode($data, true);
    }
    else
    {
        $data = json_decode($response, true);
    }
    $mensaje = $data["Detalles"];
    
    // Redireccion
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/usuarios/listar';</script>";
    
}

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
          "titulo"  => "USUARIOS",
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
			<h3>Registrar nuevo usuario</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" /*action="<?php base_url().'/index.php/usuarios/create'?>"*/ enctype="multipart/form-data" class="needs-validation" novalidate>
			    <div class="form-row">
				<div class="form-group col-md-6">
				    <label for="nombres">Nombres</label>
				    <input type="text" name="nombres" class="form-control" id="nombres" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese su nombre
				    </div>
				</div>
				<div class="form-group col-md-6">
				    <label for="apellidos">Apellidos</label>
				    <input type="text" name="apellidos" class="form-control" id="apellidos" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese su apellido
				    </div>
				</div>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-6">
				    <label for="correo">Correo electr&oacute;nico</label>
				    <input type="email" name="correo" class="form-control" id="correo" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese un correo electr&oacute;nico valido
				    </div>
				</div>
				<div class="form-group col-md-6">
				    <label for="contra">Contrase&ntilde;a </label>
				    <input type="password" class="form-control" name="contra" id="contra" required minlength="3" required>
				    <div class="invalid-feedback">
					Rellene este campo
				    </div>
				</div>
			    </div>

			    <div class="form-group">
				<label for="inputAddress">Direcci&oacute;n</label>
				<input type="text" class="form-control" name="direccion" id="inputAddress" placeholder="1234 Main St" required>
				<div class="valid-feedback">
				    Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				    Ingrese algo aqu&iacute;
				</div>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-4">
				    <label for="dni">DNI</label>
				    <input type="text" class="form-control" name="dni" id="dni" required mixlength="8" maxlength="8">
				    <div class="invalid-feedback">
					Ingrese solo 8 n&uacute;meros
				    </div>
				</div>

				<div class="form-group col-md-4">
				    <label for="edad">Edad</label>
				    <input type="number" class="form-control" name="edad" id="edad" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese un n&uacute;mero natural
				    </div>
				</div>

				<div class="form-group col-md-4">
				    <label for="id_perfil">Seleccione un perfil</label>
				    <select id="id_perfil" name="id_perfil" class="form-control" required>
					<?php foreach ($perfiles as $perfil): ?>
					    <option value="<?= $perfil["idPerfil"]?>"> <?= $perfil["perfil"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>
			    </div>

			    <div class="form-check form-check-inline form-group">
				<input class="form-check-input" type="radio" name="sexo" id="masculino" value="M" required>
				<label class="form-check-label" for="masculino">
				    Masculino
				</label>
			    </div>
			    <div class="form-check form-check-inline form-group">
				<input class="form-check-input" type="radio" name="sexo" id="femenino" value="F" required>
				<label class="form-check-label" for="femenino">
				    Femenino
				</label>
			    </div>

			    <div class="form-group">
				<label for="rutaFoto">Escoja un foto</label>
				<input type="file" name="rutaFoto" class="form-control-file" id="rutaFoto">
			    </div>

			    <div class="form-group">
				<label for="comentario">Comentario</label>
				<textarea type="text" class="form-control" name="comentario" id="comentario" placeholder="Ingrese alg&uacute;n comentario"></textarea>
			    </div>
			    <button type="submit" class="btn btn-primary">Registrar</button>
                            <a href="<?= base_url().'/index.php/usuarios/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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

