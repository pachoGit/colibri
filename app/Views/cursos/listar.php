<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/cursos",
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
         "titulo"  => "CURSOS",                                                                                                                   
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
		    <button data-toggle="modal" data-target="#tipo" class="btn btn-secondary mb-1 ml-5">Tipos de cursos</button>
		    <button data-toggle="modal" data-target="#categoria" class="btn btn-info mb-1 ml-5">Categorias de cursos</button>
		    <button data-toggle="modal" data-target="#naturaleza" class="btn btn-dark mb-1 ml-5">Naturalezas de cursos</button>		    

		    <table class="table table-bordered table-striped">
			<thead>
			    <tr>
				<th>ID</th>
				<th>Curso</th>
				<th>Tipo</th>
				<th>Categoria</th>
				<th>Naturaleza</th>
				<th colspan="2">Operaciones</th>
			    </tr>
			</thead>

			<?php
			if ($data["Estado"] == 200) {
			    foreach($data["Detalles"] as $curso) { ?>
			    <tbody>
				<tr class="odd gradeX">
				    <td><?php echo $curso['idCurso']; ?></td>
				    <td><?php echo $curso['curso']; ?></td>
				    <td><?php echo $curso['tipo']; ?></td>
				    <td><?php echo $curso['categoria']; ?></td>
				    <td><?php echo $curso['naturaleza']; ?></td>
				    <td><a href="editar/<?= $curso['idCurso']?>" class="btn
						 btn-warning">Editar</a></td>
				    <td><a onclick="return alerta();" href="eliminar/<?= $curso['idCurso']?>"
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

<!-- Nuevo tipo de curso -->
<div class="modal fade" id="tipo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Registrar nuevo tipo de curso</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
	    </div>
	    <div class="modal-body">
		<form class="form-signin" method="post" action="<?= base_url().'/index.php/tipoCurso/crear';?>">
		    <div class="form-group">
			<label for="tipo">Ingrese el nuevo tipo</label>
			<input class="form-control" name="tipo" id="tipo" required>
		    </div>
		    <button class="btn btn-lg btn-primary btn-block" type="submit">Registrar</button>
		</form>
	    </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
		<!--<button type="button" class="btn btn-primary">Save changes</button>-->
	    </div>
	</div>
    </div>
</div>  



<!-- Nueva categoria de curso -->
<div class="modal fade" id="categoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Registrar nueva categoria de curso</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
	    </div>
	    <div class="modal-body">
		<form class="form-signin" method="post" action="<?= base_url().'/index.php/categoriaCurso/crear';?>">
		    <div class="form-group">
			<label for="categoria">Ingrese la nueva categoria</label>
			<input class="form-control" name="categoria" id="categoria" required>
		    </div>
		    <button class="btn btn-lg btn-primary btn-block" type="submit">Registrar</button>
		</form>
	    </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
	    </div>
	</div>
    </div>
</div>  




<!-- Nuevo naturaleza de curso -->
<div class="modal fade" id="naturaleza" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Registrar nueva naturaleza de curso</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
	    </div>
	    <div class="modal-body">
		<form class="form-signin" method="post" action="<?= base_url().'/index.php/naturalezaCurso/crear';?>">
		    <div class="form-group">
			<label for="naturaleza">Ingrese la nueva naturaleza</label>
			<input class="form-control" name="naturaleza" id="naturaleza" required>
		    </div>
		    <button class="btn btn-lg btn-primary btn-block" type="submit">Registrar</button>
		</form>
	    </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
	    </div>
	</div>
    </div>
</div>  



</div>
</div>
<script type="text/javascript">
  function alerta()
  {
      var r = confirm("Eliminar este curso?");
      if (r)
	  return true;
      else
	  return false;
  }
</script>
<?php echo view("comun/pie"); ?>



