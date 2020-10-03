<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Soporte para las fotos

    if (!empty($_FILES["rutaFoto"]["name"]))
    {
        $ruta = "/public/usuarios/".$_FILES["rutaFoto"]["name"];
        $ruta2 = "/var/www/html/colibri/public/usuarios/".$_FILES["rutaFoto"]["name"];
        move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);
    }
    else
    {
        $usuario = new App\Models\ModeloUsuarios();
        $usuario = $usuario->traerPorId($_POST["idUsuario"], 1); // SESSION
        $ruta = $usuario[0]["rutaFoto"];
    }

    //var_dump($_POST);
    //var_dump($_FILES);die;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost/colibri/index.php/usuarios/update/".$_POST["idUsuario"],
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
        "&edad=".$_POST["edad"].
        "&rutaFoto=".$ruta.
        "&direccion=".$_POST["direccion"].
        "&correo=".$_POST["correo"].
        "&contra=".$_POST["contra"].
        "&id_perfil=".$_POST["id_perfil"].        
        "&comentario=".$_POST["comentario"],
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ==",
            "Content-Type: application/x-www-form-urlencoded",
                                    ),
                                   ));

    $response = curl_exec($curl);

    curl_close($curl);

    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, -266);
    $data = json_decode($data, true);
    if ($data["Estado"] != 200)
    {
        var_dump($data);die;
	}
    // Redireccion
    var_dump($data);
    return redirect()->to(base_url()."/index.php/usuarios/listar");;
    
}


else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost/colibri/index.php/usuarios/show/".$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ==",
                                    ),
                                   ));
    $response = curl_exec($curl);
    curl_close($curl);

    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, -266);
    $data = json_decode($data, true);

    //if (session_start() == false)
    //{
//    session_start();
    //}


    $casa = new App\Controllers\Casa();
    $nmodulos = $casa->traerModulos();

    $datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
              "titulo"  => "ALUMNOS",                                                                                                                   
              "nombre"  => $_SESSION["nombres"],                                                                                                       
              "modulos" => $nmodulos];

    $casa->cargarCabeza($datos);

    $data = $data["Detalles"];
    $data = $data[0];

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
			<h3>Editar usuario</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" /*action="<?php base_url().'/index.php/usuarios/create'?>"*/ enctype="multipart/form-data" class="needs-validation" novalidate>
			<input type="hidden" name="idUsuario" value="<?= $id; ?>">                                                                                                                                                             
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
				    <input type="text" name="apellidos" class="form-control" value="<?= $data["apellidos"]; ?>" id="apellidos" required>
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
				    <input type="email" name="correo" class="form-control" id="correo" value="<?= $data["correo"]; ?>" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese un correo electr&oacute;nico valido
				    </div>
				</div>

				<div class="form-group col-md-6">
				    <label for="contra">Contrase&ntilde;a </label>
				    <input type="password" class="form-control" name="contra" id="contra" value="<?= $data["contra"]; ?>" minlength="3" required>
				    <div class="invalid-feedback">
					Rellene este campo
				    </div>
				</div>
			    </div>

			    <div class="form-group">
				<label for="inputAddress">Direcci&oacute;n</label>
				<input type="text" class="form-control" name="direccion" id="inputAddress" value="<?= $data["direccion"]; ?>" placeholder="1234 Main St" required>
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
				    <input type="text" class="form-control" name="dni" id="dni" required value="<?= $data["dni"]; ?>"  mixlength="8" maxlength="8">
				    <div class="invalid-feedback">
					Ingrese solo 8 n&uacute;meros
				    </div>
				</div>

				<div class="form-group col-md-4">
				    <label for="edad">Edad</label>
				    <input type="number" class="form-control" value="<?= $data["edad"]; ?>" name="edad" id="edad" required>
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
					    <option value="<?= $perfil["idPerfil"]?>" <?php if ($perfil["perfil"] == $mi_perfil) { echo "selected"; }?> > <?= $perfil["perfil"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>
				
			    </div>
			    <div class="form-check form-check-inline form-group">
				<input class="form-check-input" type="radio" name="sexo" id="masculino" <?php if ($data["sexo"] == "M") {echo "checked";} ?> value="M" required>
				<label class="form-check-label" for="masculino">
				    Masculino
				</label>
			    </div>
			    <div class="form-check form-check-inline form-group">
				<input class="form-check-input" type="radio" name="sexo" id="femenino" <?php if ($data["sexo"] == "F") {echo "checked";} ?> value="F" required>
				<label class="form-check-label" for="femenino">
				    Femenino
				</label>
			    </div>

			    <div class="form-group">
                             <label for="rutaFoto">Escoja un foto</label>
                             <input type="file" name="rutaFoto" value="<?= $data["rutaFoto"]; ?>" class="form-control-file" id="rutaFoto">
			    </div>

			    <div class="form-group">
				<label for="comentario">Comentario</label>
				<textarea type="text" class="form-control" name="comentario" value="<?= $data["comentario"]; ?>" id="comentario"> <?= $data["comentario"]; ?> </textarea>
			    </div>
			    <button type="submit" class="btn btn-primary">Aceptar</button>
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

<?php echo view("comun/pie"); ?>

