<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}



$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/alumnoPorCurso/show/".$id,
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

if ($data["Estado"] != 200)
{
    $mensaje = $data["Detalles"];
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/alumnoPorCurso/listar';</script>";
}

$data = $data["Detalles"];
$m_grados = new App\Models\ModeloGrados();

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
          "titulo"  => "Matricula - Alumnos",
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
			<h3>Visualizar matricula</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" class="needs-validation" novalidate>
			    <div class="form-group">
				<label for="id_alumno">Alumno</label>
				<select id="id_alumno" name="id_alumno" class="form-control" disabled>
				    <option value="<?= $data[0]["idAlumno"]?>"> <?= $data[0]["nombres"]." ".$data[0]["apellidos"]; ?></option>
				</select>
			    </div>

			    <label for="tabla"> Lista de cursos del alumno - Peri&oacute;do <label class="font-weight-bold"> <?= $data[0]["ciclo"];?> </label> </label>
			    <table class="table table-bordered table-striped" id="tabla">
				<thead>
				    <tr>
					<th>Curso</th>
					<th>Grado</th>					
					<th>Secci&oacute;n</th>					
				    </tr>
				</thead>
				<?php foreach ($data as $info):/* var_dump($info);die;*/?>
				<tbody>
				    <tr class="odd gradeX">
					<td> <?= $info["curso"]; ?></td>
					<td> <?php $grado = $m_grados->traerPorId($info["id_grado"], $_SESSION["id_cliente"]); echo $grado[0]["grado"]; ?></td>
					<td> <?= $info["seccion"]; ?></td>			
				    </tr>
				</tbody>
				<?php endforeach; ?>
			    </table>
			    
                            <a href="<?= base_url().'/index.php/alumnoPorCurso/listar'; ?>" class="btn btn-primary"> Volver </a>
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
