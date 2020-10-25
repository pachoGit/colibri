<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/perfiles",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
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
         "titulo"  => "PERFILES",                                                                                                                   
         "nombre"  => $_SESSION["nombres"],                                                                                                       
         "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

$m_permisos = new App\Models\ModeloPermisos();

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
				    <th>Perfil</th>
				    <th>Permisos</th>
				    <th colspan="2">Operaciones</th>
				</tr>
			    </thead>

			    <?php
			    if ($data["Estado"] == 200) {
				foreach($data["Detalles"] as $perfil) { ?>
				<tbody>
				    <tr class="odd gradeX">
					<td><?php echo $perfil['idPerfil'] ?></td>
					<td><?php echo $perfil['perfil'] ?></td>
					<td>
					    <?php
					    $permisos = $m_permisos->traerDePerfil($perfil["idPerfil"], $_SESSION["id_cliente"]);
					    foreach ($permisos as $permiso) {
						echo $permiso["modulo"]."<br>";
					    }
					    ?>
					</td>
					<td><a href="editar/<?= $perfil['idPerfil']?>" class="btn
						     btn-warning">Editar</a></td>
					<td><a onclick="return alerta();" href="eliminar/<?= $perfil['idPerfil']?>"
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
    <script>
	function alerta()
     {
	 var r = confirm("Desea eliminar este perfil?");
	 if (r)
	     return true;
	 else
	     return false;
     }
</script>

<?php echo view("comun/pie"); ?>


