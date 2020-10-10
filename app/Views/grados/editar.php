<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesi�n');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
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
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/grados/listar';</script>";
    return;
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
            $_SESSION["auth"], "Cliente:".$_SESSION["id_cliente"]
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
                    

		    <form method="post"  class="needs-validation" novalidate>
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
			<a href="<?= base_url().'/index.php/grados/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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

