<?php

//session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/secciones/update/".$_POST["idSeccion"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "seccion=".$_POST["seccion"].
        "&id_grado=".$_POST["id_grado"],
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"],
            "Content-Type: application/x-www-form-urlencoded",
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);
    
    $mensaje = $data["Detalles"];
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/secciones/listar';</script>";
    return;
    
}
else
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/secciones/show/".$id,
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
              "titulo"  => "SECCIONES",                                                                                                                   
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
			<h3>Editar secciones</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" /*action="<?php base_url().'/index.php/seciones/create'?>"*/ enctype="multipart/form-data" class="needs-validation" novalidate>
			<input type="hidden" name="idSeccion" value="<?= $id; ?>">                                                                                                                                                             
			    <div class="form-row">
			    <div class="form-group col-md-4">
				    <label for="id_grado">Seleccione Grado</label>
				    <select id="id_grado" name="id_grado" class="form-control" required>
					<?php foreach ($grados as $grado): ?>
					    <option value="<?= $grado["idGrado"]?>" <?php if ($grado["grado"] == $mi_grado) { echo "selected"; }?> > <?= $grado["grado"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>
				<div class="form-group col-md-6">
				    <label for="Seccion">Seccion</label>
				    <input type="text" name="seccion" value="<?= $data["seccion"]; ?>" class="form-control" id="seccion" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese su nombre
				    </div>
				</div>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-6">
				    <label for="fechaCreacion">Fecha CreaciÃ³n</label>
				    <input type="date" name="fechaCreacion" class="form-control" id="fechaCreacion" value="<?= $data["fechaCreacion"]; ?>" disabled>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese un correo electr&oacute;nico valido
				    </div>
				</div>
			    </div>
			    <button type="submit" class="btn btn-primary">Aceptar</button>
                            <a href="<?= base_url().'/index.php/secciones/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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
