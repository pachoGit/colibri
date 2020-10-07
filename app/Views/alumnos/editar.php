<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //var_dump($_FILES["rutaFoto"]);die;
    if (!empty($_FILES["rutaFoto"]["name"]))
    {
        $ruta = "/public/alumnos/".$_FILES["rutaFoto"]["name"];
        $ruta2 = $_SESSION["ruta"]."alumnos/".$_FILES["rutaFoto"]["name"];
        move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);
    }
    else
    {
        $usuario = new App\Models\ModeloAlumnos();
        $usuario = $usuario->traerPorId($_POST["idAlumno"], 1); // SESSION
        $ruta = $usuario[0]["rutaFoto"];
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/alumnos/update/".$_POST["idAlumno"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "nombres=".$_POST["nombres"].
        "&apellidos=".$_POST["apellidos"].
        "&dni=".$_POST["dni"].
        "&sexo=".$_POST["sexo"].
        "&nombrePadre=".$_POST["nombrePadre"].
        "&nombreMadre=".$_POST["nombreMadre"].
        "&edad=".$_POST["edad"].
        "&rutaFoto=".$ruta.
        "&direccion=".$_POST["direccion"].
        "&correo=".$_POST["correo"].
        "&comentario=".$_POST["comentario"],
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
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/alumnos/listar';</script>";
    return;
}

else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/alumnos/show/".$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"],
                                    ),
                                   ));
    $response = curl_exec($curl);

    curl_close($curl);


    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, $_SESSION["tam"]);
    $data = json_decode($data, true);

    $casa = new App\Controllers\Casa();
    $nmodulos = $casa->traerModulos();

    $datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
              "titulo"  => "ALUMNOS",                                                                                                                   
              "nombre"  => $_SESSION["nombres"],                                                                                                       
              "modulos" => $nmodulos];

    $casa->cargarCabeza($datos);

    $data = $data["Detalles"];
    $data = $data[0];
    //var_dump($data);die;
}

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
		    <h3>Editar alumno</h3>
		</div>
		<div class="widget-content" >
                    

		    <form method="post" /* action="<?php base_url().'/index.php/profesores/update'?>" */ enctype="multipart/form-data" class="needs-validation" novalidate>
			<input type="hidden" name="idAlumno" value="<?= $data['idAlumno']; ?>">
			<div class="form-row">
			    <div class="form-group col-md-6">
				<label for="nombres">Nombres</label>
				<input type="text" name="nombres" value="<?= $data["nombres"]; ?>" class="form-control" id="nombres" required>
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese su nombre
				</div>
			    </div>
			    <div class="form-group col-md-6">
				<label for="apellidos">Apellidos</label>
				<input type="text" name="apellidos" value="<?= $data["apellidos"]; ?>" class="form-control" id="apellidos" required>
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
				<input type="email" name="correo" value="<?= $data["correo"]; ?>" class="form-control" id="correo" required>
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese un correo electr&oacute;nico valido
				</div>
			    </div>
			    <div class="form-group col-md-6">
				<label for="dni">DNI</label>
				<input type="text" class="form-control" name="dni" value="<?= $data["dni"]; ?>" id="dni" required mixlength="8" maxlength="8">
				<div class="invalid-feedback">
				  Ingrese solo 8 n&uacute;meros
				</div>
			    </div>
			</div>
			<div class="form-row">
			    <div class="form-group col-md-6">
				<label for="nombreMadre">Nombre de la madre</label>
				<input type="text" name="nombreMadre" value="<?= $data["nombreMadre"]; ?>" class="form-control" id="nombreMadre">
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese su nombre
				</div>
			    </div>
			    <div class="form-group col-md-6">
				<label for="nombrePadre">Nombre del padre</label>
				<input type="text" name="nombrePadre" value="<?= $data["nombrePadre"]; ?>" class="form-control" id="nombrePadre">
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese su apellido
				</div>
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputAddress">Direcci&oacute;n</label>
			    <input type="text" class="form-control" name="direccion" value="<?= $data["direccion"]; ?>" id="inputAddress" placeholder="1234 Main St" required>
			    <div class="valid-feedback">
			      Esto est&aacute; bien
			    </div>
			    <div class="invalid-feedback">
			      Ingrese algo aqu&iacute;
			    </div>
			</div>

			<div class="form-row">

			    <div class="form-group col-md-3">
				<label for="edad">Edad</label>
				<input type="number" class="form-control" name="edad" id="edad" value="<?= $data["edad"]; ?>" required>
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese un n&uacute;mero natural
				</div>
			    </div>
			</div>

			<div class="form-check form-check-inline form-group">
			    <input class="form-check-input" type="radio" <?php if ($data["sexo"] == "M") {echo "checked";} ?> name="sexo" id="masculino" value="M" required>
			    <label class="form-check-label" for="masculino">
				Masculino
			    </label>
			</div>
			<div class="form-check form-check-inline form-group">
			    <input class="form-check-input" type="radio" name="sexo" <?php if ($data["sexo"] == "F") {echo "checked";} ?> id="femenino" value="F" required>
			    <label class="form-check-label" for="femenino">
				Femenino
			    </label>
			</div>

			<div class="form-group">
			    <label for="rutaFoto">Escoja un foto</label>
			    <input type="file" name="rutaFoto" value="<?= $data['rutaFoto']; ?>"  class="form-control-file" id="rutaFoto">
			    <!--<img src="<?= base_url().$data["rutaFoto"]; ?>" class="rounded mx-auto d-block" witdh="200" height="200">-->
			</div>

			<div class="form-group">
			    <label for="comentario">Comentario</label>
			    <textarea type="text" class="form-control" name="comentario" value="<?= $data["comentario"]; ?>" id="comentario"> <?= $data["comentario"]; ?> </textarea>
			</div>
			<button type="submit" class="btn btn-primary">Aceptar</button>
			<a href="<?= base_url().'/index.php/alumnos/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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

<?php echo view("comun/pie"); ?>

