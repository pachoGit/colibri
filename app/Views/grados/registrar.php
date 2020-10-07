<?php  
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Soporte para las fotos

    // $ruta = "/public/alumnos/".$_FILES["rutaFoto"]["name"];
    // $ruta2 = "C:\\xampp\\htdocs\\colibri\\public\\alumnos/".$_FILES["rutaFoto"]["name"];
    // move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);

    //var_dump($_POST);
    //var_dump($_FILES);die;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/grados/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
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
    // Redireccion
    $mensaje = $data["Detalles"];
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/grados/listar';</script>";
    
}

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
         "titulo"  => "ALUMNOS",
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
			<h3>Registrar nuevo grado</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" action="<?php base_url().'/index.php/grados/create'?>" enctype="multipart/form-data" class="needs-validation" novalidate>
			    
				<div class="form-group col-md-6">
				    <label for="nombres">Nombre del Grado</label>
				    <input type="text" name="grado" class="form-control" id="nombres" required>
				    <div class="valid-feedback">
					Esto est&aacute; bien
				    </div>
				    <div class="invalid-feedback">
					Ingrese el nombre del grado
				    </div>
				</div>
			
			    <button type="submit" class="btn btn-primary">Registrar</button>
			    <a href="<?php echo base_url().'/index.php/grados/listar'?>" class="btn btn-danger">Cancelar</a>
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

