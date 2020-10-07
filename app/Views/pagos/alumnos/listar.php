<?php

session_start();

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/pagos/index_alumnos",
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
         "titulo"  => "PAGOS - ALUMNOS",                                                                                                                   
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
                    
		    <a href="registrar_alumno" class="btn btn-success mb-1">Registrar</a>
		    <table class="table table-bordered table-striped">
			<thead>
			    <tr>
				<th>ID</th>
				<th>Nombres</th>
				<th>Apellidos</th>
				<th>Persona</th>                         
				<th>DNI</th>
				<th>Monto</th>
				<th>Fecha de pago</th>
				<th>Motivo</th>
				<th colspan="3">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    foreach($data["Detalles"] as $pago) { ?>
			    <tbody>
				<tr class="odd gradeX">
				    <td><?php echo $pago['idPago']; ?></td>
				    <td><?php echo $pago['nombres']; ?></td>
				    <td><?php echo $pago['apellidos']; ?></td>
				    <td><?php if (is_null($pago['id_alumno'])) { echo "Profesor"; }else { echo "Alumno";}  ?></td>                    
				    <td><?php echo $pago['dni']; ?></td>
				    <td><?php echo $pago['monto']; ?></td>
				    <td><?php echo $pago['fechaPago']; ?></td>
				    <td><?php echo $pago['motivo']; ?></td>
				    <td><a href="ver_alumno/<?= $pago['idPago']?>" class="btn
						 btn-secondary">Ver</a></td>
				    <td><a href="editar_alumno/<?= $pago['idPago']?>" class="btn
						 btn-warning">Editar</a></td>
				    <td><a href="eliminar_alumno/<?= $pago['idPago']?>"
					   class="btn btn-danger">Eliminar</a></td>
				</tr>
			    </tbody>
			<?php
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
<?php echo view("comun/pie"); ?>
