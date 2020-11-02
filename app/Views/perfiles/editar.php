<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

// Permisos del perfil

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/permisos/verDePerfil/".$id,
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

$permisos = json_decode($response, true);
$permisos = $permisos["Detalles"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Si no selecciono ningun permiso...
    //TODO: esto deberia ser validado en la vista
    if (!isset($_POST["permisosH"], $_POST["permisosP"]))
    {
        echo "<script>alert('Seleccione algun modulo');window.location.href = '".base_url()."/index.php/perfiles/editar';</script>";
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/perfiles/update/".$_POST["idPerfil"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS =>
        "perfil=".$_POST["perfil"].
        "&id_cliente=".$_SESSION["id_cliente"],
        CURLOPT_HTTPHEADER => array(
	    $_SESSION["auth"],
            "Content-Type: application/x-www-form-urlencoded",
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    if ($data["Estado"] != 200)
    {
        // Redireccion
        $mensaje = $data["Detalles"];
        echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/perfiles/listar';</script>";
    }

    // Verificar si ya esta registrado los modulos padres

    $crear = false;
    foreach ($_POST["permisosP"] as $id_modulo)
    {
	foreach ($permisos as $permAcutal)
	{
	    if ($id_modulo == $permActual["id_modulo"])
	    {
		$crear = false;
		break;
	    }
	    else
	    {
		$crear = true;
	    }
	}
	if ($crear == true)
	{
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
		CURLOPT_URL => base_url()."/index.php/permisos/create",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>
		    "id_perfil=".$_POST["idPerfil"].
				    "&id_modulo=".$id_modulo.
				    "&id_cliente=".$_SESSION["id_cliente"],
		CURLOPT_HTTPHEADER => array(
		    $_SESSION["auth"],
		    "Content-Type: application/x-www-form-urlencoded",
		),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);
	}
    }

    // Verficar si esta registrado los modulos hijos

    $crear = false;
    foreach ($_POST["permisosH"] as $id_modulo)
    {
	foreach ($permisos as $permAcutal)
	{
	    if ($id_modulo == $permActual["id_modulo"])
	    {
		$crear = false;
		break;
	    }
	    else
	    {
		$crear = true;
	    }
	}
	if ($crear == true)
	{
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
		CURLOPT_URL => base_url()."/index.php/permisos/create",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>
		    "id_perfil=".$_POST["idPerfil"].
				    "&id_modulo=".$id_modulo.
				    "&id_cliente=".$_SESSION["id_cliente"],
		CURLOPT_HTTPHEADER => array(
		    $_SESSION["auth"],
		    "Content-Type: application/x-www-form-urlencoded",
		),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);
	}
    }

    // Redireccion
    echo "<script>alert('Se actualizó correctamente el perfil');window.location.href = '".base_url()."/index.php/perfiles/listar';</script>";
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/perfiles/show/".$id,
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
$perfil = $data["Detalles"][0];



$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
          "titulo"  => "PERFILES",
          "nombre"  => $_SESSION["nombres"],                                                                                                       
          "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

// Traemos todos los modulos y submodulos
$m_modulos = new App\Models\ModeloModulos();
$todos_modulos = $m_modulos->traerModulos($_SESSION["id_cliente"]); // SESSION

/**** Obtenemos los modulos ****/

$padres = []; // Solo los modulos padres

// Filtramos los modulos padres
foreach ($todos_modulos as $tmodulo)
{
    if (is_null($tmodulo["id_moduloPadre"]))
        array_push($padres, $tmodulo);
}

$hijos = []; // Solo los modulos hijos

// Filtramos los modulos hijos de los padres
foreach ($padres as $smodulo)
{
    $h = $m_modulos->traerHijos($smodulo["idModulo"], $_SESSION["id_cliente"]);
    array_push($hijos, $h);
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
			<h3>Editar perfil de usuario</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" class="needs-validation" novalidate>
			    <input type="hidden" name="idPerfil" value="<?= $perfil["idPerfil"]; ?>">
			    <div class="form-group">
				<label for="perfil">Nombre del perfil</label>
				<input type="text" class="form-control" value="<?= $perfil["perfil"]; ?>" name="perfil" id="perfil" required>
				<div class="valid-feedback">
				    Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				    Ingrese algo aqu&iacute;
				</div>
			    </div>

			    <label> Elija los permisos del nuevo perfil </label>
			    <div class="container row">
				<?php foreach ($padres as $padre): ?>
				    <div class="col">
					<div class="form-check form-check-inline">
					    <input class="form-check-input ml-5" <?php foreach ($permisos as $permiso) { if ($permiso["id_modulo"] == $padre["idModulo"]) {echo "checked"; }}?> type="checkbox" name="permisosP[]" value="<?= $padre["idModulo"];?>" id="permisosP">
					    <label class="form-check-label" for="permisosP">
						<?= $padre["modulo"]; ?>
					    </label>
					</div>
				    </div>
				<?php endforeach; ?>
			    </div>

			    <div class="container row">
				<?php  foreach  ($hijos as $nhijos): ?>
				    <div class="col">
					<?php  foreach  ($nhijos as $hijo): ?>
					    <div class="form-check">
						<input class="form-check-input" <?php foreach ($permisos as $permiso) { if ($permiso["id_modulo"] == $hijo["idModulo"]) {echo "checked"; }}?> type="checkbox" name="permisosH[]" value="<?= $hijo["idModulo"];?>" id="permisosH">
						<label class="form-check-label" for="permisosH">
						    <?= $hijo["modulo"]; ?>
						</label>
					    </div>
					<?php endforeach;?>
				    </div>
				<?php endforeach;?>
			    </div>
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

