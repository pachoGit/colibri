<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/profesores",
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
         "titulo"  => "PROFESORES",                                                                                                                   
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
				<th>DNI</th>
				<th>Edad</th>
				<th>Sexo</th>
				<th>Correo</th>
				<th>Foto</th>
				<th>Direcci&oacute;n</th>
				<th colspan="3">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    foreach($data["Detalles"] as $profesor) { ?>
			    <tbody>
				<tr class="odd gradeX">
				    <td><?php echo $profesor['idProfesor']; ?></td>
				    <td><?php echo $profesor['nombres']; ?></td>
				     <td><?php echo $profesor['apellidos']; ?></td>
				    <td><?php echo $profesor['dni']; ?></td>
				    <td><?php echo $profesor['edad']; ?></td>
				    <td><?php echo ($profesor['sexo'] == "M" ? "Masculino" : "Femenino"); ?></td>
				    <td><?php echo $profesor['correo']; ?></td>
				    <td><img src='<?php echo base_url().$profesor["rutaFoto"]; ?>' witdh="75" height="75"></td>
				    <td><?php echo $profesor['direccion']; ?></td>				    
				    <td><a href="ver/<?= $profesor['idProfesor']?>" class="btn
						 btn-secondary">Ver</a></td>
				    <td><a href="editar/<?= $profesor['idProfesor']?>" class="btn
						 btn-warning">Editar</a></td>
				    <td><a onclick="return alerta();" href="eliminar/<?= $profesor['idProfesor']?>"
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
<script type="text/javascript">
  function alerta()
  {
      var r = confirm("Eliminar a este profesor?");
      if (r)
	  return true;
      else
	  return false;
  }
</script>
<?php echo view("comun/pie"); ?>

