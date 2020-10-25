<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/sedes/update/".$_POST["idSede"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "sede=".$_POST["sede"].
        "&direccion=".$_POST["direccion"],
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"],
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    $mensaje = $data["Detalles"];
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/sedes/listar';</script>";
    return;
}

else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/sedes/show/".$id,
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

    $data = json_decode($response, true);

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
		    <h3>Editar Sede</h3>
		</div>
		<div class="widget-content" >
                    

		    <form method="post"  class="needs-validation" novalidate>
			<input type="hidden" name="idSede" value="<?= $data['idSede']; ?>">
			<div class="form-row">
			    <div class="form-group col-md-6">
				<label for="sede">Nombre del Grado</label>
				<input type="text" name="sede" value="<?= $data["sede"]; ?>" class="form-control" id="sede" required>
				<div class="valid-feedback">
				  Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				  Ingrese la sede
				</div>
			    </div>
			    
			</div>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="direccion">Direccion de la Sede</label>
                <input type="text" name="direccion" value="<?= $data["direccion"]; ?>" class="form-control" id="direccion" required>
                <div class="valid-feedback">
                  Esto est&aacute; bien
                </div>
                <div class="invalid-feedback">
                  Ingrese la direccion de la sede
                </div>
                </div>
                
            </div>

			<button type="submit" class="btn btn-primary">Aceptar</button>
			<a href="<?= base_url().'/index.php/sedes/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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

