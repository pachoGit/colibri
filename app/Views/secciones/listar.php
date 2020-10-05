<?php
//session_start();

namespace App\Controllers;

session_start();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => base_url()."/index.php/secciones",
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

/*
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://colibri.informaticapp.com/index.php/usuarios",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
  ),
));
*/

$response = curl_exec($curl);

curl_close($curl);


// Puede que tengamos caracteres ocultos la final de la respuesta
$data = substr($response, 0, $_SESSION["tam"]);
//$data = substr($response, 0, -269);
$data = json_decode($data, true);
//var_dump($response);
//die;
//if (session_start() == false)
//{
   // session_start();
//}


$casa = new Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
         "titulo"  => "SECCIONES",                                                                                                                   
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
				<th>Secci&oacute;n</th>
				<th>Grado</th>
				<th>Fecha Creación</th>
				<th>Cliente</th>
				<th colspan="2">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    foreach($data["Detalles"] as $seccion) { ?>
			    <tbody>
				<tr class="odd gradeX">
				    <td><?php echo $seccion['idSeccion']; ?></td>
				    <td><?php echo $seccion['seccion']; ?></td>
				    <td><?php echo $seccion['grado']; ?></td>
				    <td><?php echo $seccion['fechaCreacion']; ?></td>
				    <td><?php echo $seccion['id_cliente']; ?></td>
				    <td><a href="editar/<?= $seccion['idSeccion']?>" class="btn
						 btn-warning">Editar</a></td>
				    <td><a href="eliminar/<?= $seccion['idSeccion']?>"
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
