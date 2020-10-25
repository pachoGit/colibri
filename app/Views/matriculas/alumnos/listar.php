<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/alumnoPorCurso",
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

//var_dump($data);die;
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
		    <h5>Contenido</h5>
		</div>
		<div class="widget-content" >
                    
		    <a href="registrar" class="btn btn-success mb-1">Registrar</a>
		    <table class="table table-bordered table-striped">
			<thead>
			    <tr>
				<th>ID</th>
				<th>Nombres</th>
				<th>Apellidos</th>
				<th>Periodo</th>                         
				<th>Fecha</th>
				<th colspan="3">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    $repetidos = []; foreach($data["Detalles"] as $alumno) {
				$existe = false;
				foreach ($repetidos as $clave => $valor)
				{
				    if ($clave == $alumno["idAlumno"] and $valor == $alumno["ciclo"])
				    {
					$existe = true;
					break;
				    }

				}
				if ($existe == false)
				{
				    $repetidos[$alumno["idAlumno"]] = $alumno["ciclo"];
			?>
			<tbody>
			    <tr class="odd gradeX">
				<td><?php echo $alumno['idAlumnoPorCurso']; ?></td>
				<td><?php echo $alumno['nombres']; ?></td>
				<td><?php echo $alumno['apellidos']; ?></td>
				<td><?php echo $alumno['ciclo']; ?></td>
				<td><?php echo $alumno['fechaCreacion']; ?></td>
				<td><a href="ver/<?= $alumno['idAlumnoPorCurso']?>" class="btn
					     btn-secondary">Ver</a></td>
				<td><a href="editar/<?= $alumno['idAlumnoPorCurso']?>" class="btn
					     btn-warning">Editar</a></td>
				<td><a onclick="return alerta();" href="eliminar/<?= $alumno['idAlumnoPorCurso']?>"
				       class="btn btn-danger">Eliminar</a></td>
			    </tr>
			</tbody>
			<?php
			}
			}
			}
			?>	    

		    </table>
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

<script type="text/javascript">
  function alerta()
  {
      var r = confirm("Desea eliminar esta matricula?");
      if (r)
	  return true;
      else
	  return false;
  }
</script>

<?php echo view("comun/pie"); ?>
