<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost/colibri/index.php/perfiles/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>
        "perfil=".$_POST["perfil"],
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

    // Obtenemos el perfil recien guardado
    $m_perfiles = new App\Models\ModeloPerfiles();
    $perfiles = $m_perfiles->where("perfil", $_POST["perfil"])->findAll();
    $perfil = $perfiles[0];
    
    // Insertamos el la tabla permisos
    foreach ($_POST["permisos"] as $id_modulo)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost/colibri/index.php/permisos/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>
            "id_perfil=".$perfil["idPerfil"].
            "&id_modulo=".$id_modulo,
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
    }    
    // Redireccion

    
}
//if (session_start() == false)
//{
    session_start();
//}


$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
         "titulo"  => "PERFILES",
         "nombre"  => $_SESSION["nombres"],                                                                                                       
         "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

// Traemos todos los modulos y submodulos
$m_modulos = new App\Models\ModeloModulos();
$todos_modulos = $m_modulos->traerModulos(1); // SESSION

$modulos = []; // Solo los modulos padres

// Filtramos los modulos padres
foreach ($todos_modulos as $tmodulo)
{
    if (is_null($tmodulo["id_moduloPadre"]))
        array_push($modulos, $tmodulo);
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
			<h3>Registrar nuevo perfil de usuario</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" action="<?php base_url().'/index.php/perfiles/create'?>" enctype="multipart/form-data" class="needs-validation" novalidate>
			    <div class="form-group">
				<label for="perfil">Nombre del perfil</label>
				<input type="text" class="form-control" name="perfil" id="perfil" required>
				<div class="valid-feedback">
				    Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				    Ingrese algo aqu&iacute;
				</div>
			    </div>
			    <label for="permisos"> Elija los permisos del nuevo perfil </label>
			    <?php foreach($modulos as $modulo): ?>
			    <div class="form-check">
			      <input class="form-check-input" type="checkbox" name="permisos[]" value="<?= $modulo["idModulo"];?>" id="permisos">
			      <label class="form-check-label" for="permisos">
				 <?= $modulo["modulo"]; ?>
			      </label>
			    </div>
			    <?php endforeach; ?>
			    
			    <button type="submit" class="btn btn-primary mt-3">Registrar</button>
			    <a href="<?= base_url().'/index.php/perfiles/listar'; ?>" class="btn btn-danger mt-3"> Cancelar </a>

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

