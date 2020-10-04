<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //var_dump($_FILES["rutaFoto"]);die;
    // if (!empty($_FILES["rutaFoto"]["name"]))
    // {
    //     $ruta = "/public/alumnos/".$_FILES["rutaFoto"]["name"];
    //     $ruta2 = "/var/www/html/colibri/public/alumnos/".$_FILES["rutaFoto"]["name"];
    //     move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);
    // }
    // else
    // {
    //     $usuario = new App\Models\ModeloAlumnos();
    //     $usuario = $usuario->traerPorId($_POST["idAlumno"], 1); // SESSION
    //     $ruta = $usuario[0]["rutaFoto"];
    // }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/grados/update/".$_POST["idGrado"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "grado=".$_POST["grado"],
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"],
                                    ),
                                   ));

    $response = curl_exec($curl);

    curl_close($curl);


    // Puede que tengamos caracteres ocultos la final de la respuesta
    $data = substr($response, 0, $_SESSION["tam"]);
    $data = json_decode($data, true);
    if ($data["Estado"] != 200)
    {
        var_dump($data);die;
	}
    return redirect()->to(base_url()."/index.php/grados/listar");
}

else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/grados/show/".$id,
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
		    <h3>Editar grado</h3>
		</div>
		<div class="widget-content" >
                    

		    <form method="post" /* action="<?php base_url().'/index.php/grados/update'?>" */ enctype="multipart/form-data" class="needs-validation" novalidate>
			<input type="hidden" name="idGrado" value="<?= $data['idGrado']; ?>">
			<div class="form-row">
			    <div class="form-group col-md-6">
				<label for="nombres">Nombre del Grado</label>
				<input type="text" name="grado" value="<?= $data["grado"]; ?>" class="form-control" id="nombres" required>
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese su nombre
				</div>
			    </div>
			    
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

