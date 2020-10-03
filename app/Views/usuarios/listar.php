<?php

namespace App\Controllers;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://localhost/colibri/index.php/usuarios",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ==",
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
$data = substr($response, 0, -266);
$data = json_decode($data, true);

/*
if (session_start() == false)
{
    session_start();
}
*/

$casa = new Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
         "titulo"  => "USUARIOS",                                                                                                                   
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
				<th>DNI</th>
				<th>Edad</th>
				<th>Sexo</th>
				<th>Correo</th>
				<th>Foto</th>
				<th colspan="3">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    foreach($data["Detalles"] as $usuario) { ?>
			    <tbody>
				<tr class="odd gradeX">
				    <td><?php echo $usuario['idUsuario']; ?></td>
				    <td><?php echo $usuario['nombres']; ?></td>
				    <td><?php echo $usuario['dni']; ?></td>
				    <td><?php echo $usuario['edad']; ?></td>
				    <td><?php echo ($usuario['sexo'] == "M" ? "Masculino" : "Femenino"); ?></td>
				    <td><?php echo $usuario['correo']; ?></td>
				    <td><img src='<?php echo base_url().$usuario["rutaFoto"]; ?>' witdh="75" height="75"></td>				    
				    <td><a href="ver/<?= $usuario['idUsuario']?>" class="btn
						 btn-secondary">Ver</a></td>
				    <td><a href="editar/<?= $usuario['idUsuario']?>" class="btn
						 btn-warning">Editar</a></td>
				    <td><a href="eliminar/<?= $usuario['idUsuario']?>"
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



