<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/cursos/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>
        "curso=".$_POST["curso"].
        "&id_tipo=".$_POST["id_tipo"].
        "&id_categoria=".$_POST["id_categoria"].
        "&id_naturaleza=".$_POST["id_naturaleza"],
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
    if ($data["Estado"] != 200)
    {
        var_dump($data);die;
	}
    // Redireccion

}

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
         "titulo"  => "CURSOS",
         "nombre"  => $_SESSION["nombres"],                                                                                                       
         "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

$m_cursos = new App\Models\ModeloCursos();

// SESSION
$tipos = $m_cursos->traerTiposCurso(1);
$categorias = $m_cursos->traerCategoriasCurso(1);
$naturalezas = $m_cursos->traerNaturalezasCurso(1);

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
			<h3>Registrar curso</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" action="<?php base_url().'/index.php/cursos/create'?>" class="needs-validation" novalidate>
			    <div class="form-group">
				<label for="curso">Nombre del curso</label>
				<input type="text" class="form-control" name="curso" id="curso" required>
				<div class="valid-feedback">
				    Esto est&aacute; bien
				</div>
				<div class="invalid-feedback">
				    Ingrese algo aqu&iacute;
				</div>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-4">
				    <label for="id_tipo">Seleccione el tipo del curso</label>
				    <select id="id_tipo" name="id_tipo" class="form-control" required>
					<?php foreach ($tipos as $tipo): ?>
					    <option value="<?= $tipo["idTipoCurso"]?>"> <?= $tipo["tipo"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>
				<div class="form-group col-md-4">
				    <label for="id_categoria">Seleccione la categoria del curso</label>
				    <select id="id_categoria" name="id_categoria" class="form-control" required>
					<?php foreach ($categorias as $categoria): ?>
					    <option value="<?= $categoria["idCategoriaCurso"]?>"> <?= $categoria["categoria"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>
				<div class="form-group col-md-4">
				    <label for="id_naturaleza">Seleccione la naturaleza del curso</label>
				    <select id="id_naturaleza" name="id_naturaleza" class="form-control" required>
					<?php foreach ($naturalezas as $naturaleza): ?>
					    <option value="<?= $naturaleza["idNaturaleza"]?>"> <?= $naturaleza["naturaleza"]; ?></option>
					    <?php endforeach; ?> 
				    </select>
				</div>

			    </div>
			    
			    <button type="submit" class="btn btn-primary mt-3">Registrar</button>
			    <a href="<?= base_url().'/index.php/cursos/listar'; ?>" class="btn btn-danger mt-3"> Cancelar </a>

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

