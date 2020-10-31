<?php

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $existeCursos = false;
    foreach ($_POST as $clave => $valor)
    {
	if ($clave == "cursos")
	{
	    $existeCursos = true;
	    break;
	}
    }
    if ($existeCursos == false)
    {
	echo "<script>alert('ERROR: Agregue al menos un curso');window.location.href = '".base_url()."/index.php/curSecPorProfesor/registrar';</script>";
    }

    $data = "";
    //var_dump($_POST);die;
    $cont = 0;
    foreach ($_POST["cursos"] as $curso)
    {
	$curl = curl_init();

	curl_setopt_array($curl, array(
            CURLOPT_URL => base_url()."/index.php/curSecPorProfesor/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>
            "id_profesor=".$_POST["id_profesor"].
            "&id_curso=".$curso.
            "&id_seccion=".$_POST["secciones"][$cont++].
            "&id_ciclo=".$_POST["id_ciclo"].
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
	    $mensaje = $data["Detalles"];
	    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/curSecPorProfesor/registrar';</script>";
	}
    }
    $mensaje = $data["Detalles"];
    // Redireccion despues de insertar
    echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/curSecPorProfesor/listar';</script>";
}

$casa = new App\Controllers\Casa();
$nmodulos = $casa->traerModulos();

$datos = ["perfil"  => $_SESSION["perfil"],                                                                                                         
          "titulo"  => "Matricula - Profesores",
          "nombre"  => $_SESSION["nombres"],                                                                                                       
          "modulos" => $nmodulos];

$casa->cargarCabeza($datos);

?>

<script type="text/javascript">

 /******** Funciones para el manejo dinamico de grado y seccion *********/
 
 // Funciones asincronas de JavaScript
 function traerSecciones(idgrado, funcion)
 {
     // Utilizamos AJAX para conseguir las secciones de un grado
     // en tiempo real
     var respuesta;
     var xhttp = new XMLHttpRequest();
     
     xhttp.onreadystatechange = function()
     {
	 if (xhttp.readyState == 4 && xhttp.status == 200)
	 {
	     respuesta = this.responseText;
	     if (funcion)
		 funcion(respuesta);
	 }

     };
     // Aqui enviamos los datos al servidor
     xhttp.open("POST", "../alumnoPorCurso/seccionesDeGrado", true);
     xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     xhttp.send("idgrado=" + idgrado);
 }

 // Esta funcion obtiene el id del grado que escoja el usuario
 function formarSecciones()
 {
     var idgrado;
     var secciones;

     // Obtengo el id del grado que escogio el usuario ('id_grado' es el id del html)
     idgrado = document.forms[0].id_grado.value;
     traerSecciones(idgrado, function(respuesta) {
	 var secciones = JSON.parse(respuesta);
	 var option = ""; // Esto es el html para cuerpo de select
	 // Los for de JavaScript son raros :D
	 for (var seccion in secciones)
	 {
	     option += "<option value='" + secciones[seccion].idSeccion + "'>" + secciones[seccion].seccion + " </option>";
	     // Insertamos en el tag de select
	     document.getElementById("id_seccion").innerHTML = option;
	 }
     });     
 }

 /************************************************************************/

 /************ Funciones para formar los cursos ***********************/

 function traerCurso(idcurso, funcion)
 {
     var respuesta;
     var xhttp = new XMLHttpRequest();

     xhttp.onreadystatechange = function()
     {
	 if (xhttp.readyState == 4 && xhttp.status == 200)
	 {
	     respuesta = this.responseText;
	     if (funcion)
		 funcion(respuesta);
	 }
     };
     // Aqui enviamos los datos al servidor
     xhttp.open("POST", "../alumnoPorCurso/traerCurso", true);
     xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     xhttp.send("idcurso=" + idcurso);

 }

 // Para agregar un curso y que se pueda registrar a travez de cURL creo checkbox
 // de los cursos que selecciona el usuario, de esta manera los puedo capturar
 // con $_POST, es algo precario pero... funciona! :D
 function agregarCurso()
 {
     var tbody = "";
     var cuerpo = document.getElementById("tabla").getElementsByTagName("tbody")[0];
     var nuevaFila = cuerpo.insertRow(cuerpo.rows.length);
     
     var idcurso = document.forms[0].id_curso.value;

     traerCurso(idcurso, function(respuesta) {
	 var curso = JSON.parse(respuesta);
	 var id_grado = document.forms[0].id_grado.value;
	 var id_seccion = document.forms[0].id_seccion.value;	 


	 var existeCurso = document.getElementById("c" + curso.idCurso);
	 var existeGrado = document.getElementById("g" + id_grado);
	 var existeSeccion = document.getElementById("s" + id_seccion);
	 if (existeCurso != null && existeGrado != null && existeSeccion != null)
	 {
	     alert("Esta informacion ya esta en la tabla");
	     return;
	 }

	 var grado;
	 var seccion;
	 // Nombres
	 grado = document.getElementById("id_grado");
	 grado = grado.options[grado.selectedIndex].text;
	 seccion = document.getElementById("id_seccion");
	 seccion = seccion.options[seccion.selectedIndex].text;


	 tbody += "<tr>";
	 tbody += "<td>" +  curso.curso + "</td>";
	 tbody += "<td>" +  grado + "</td>";
	 tbody += "<td>" +  seccion + "</td>";	 
	 tbody += "<td> <input class='btn btn-outline-danger' onclick='eliminar(" + curso.idCurso + ", this)' type='button' value='Quitar'> </td>";
	 tbody += "</tr>";
	 nuevaFila.innerHTML = tbody;

	 // Insertar un checkbox con los id's de los cursos
	 var check = document.createElement("input");
	 check.type = "checkbox";
	 check.id = "c" + curso.idCurso;
	 check.value = curso.idCurso;
	 check.checked = "checked";
	 check.name = "cursos[]";
	 check.style = "opacity:0; position:absolute; left:9999px;"
	 document.getElementById("cursos").appendChild(check); // El div

	 // Insertar un checkbox con los id's de los Grados como hijos del curso recien creado
	 var check = document.createElement("input");
	 check.type = "checkbox";
	 check.id = "g" + id_grado;
	 check.value = id_grado;
	 check.checked = "checked";
	 check.name = "grados[]";
	 check.style = "opacity:0; position:absolute; left:9999px;"
	 document.getElementById("c" + curso.idCurso).appendChild(check); // El div
	 
	 // Insertar un checkbox con los id's de los Secciones como hijos del curso recien creado
	 var check = document.createElement("input");
	 check.type = "checkbox";
	 check.id = "s" + id_seccion;
	 check.value = id_seccion;
	 check.checked = "checked";
	 check.name = "secciones[]";
	 check.style = "opacity:0; position:absolute; left:9999px;"
	 document.getElementById("c" + curso.idCurso).appendChild(check); // El div

     });
 }

 // El id del curso y el input de donde se hace click
 function eliminar(idcurso, input)
 {
     // Eliminamos el tr (fila) de la tabla
     var fila = input.parentNode.parentNode; // LLegamos al tr
     fila.parentNode.removeChild(fila); // Del tbody eliminar el mismo tr

     // Eliminamos el id del curso para no agregarlo
     var curso = document.getElementById("c" + idcurso);
     curso.remove();
 }

 
 /************************************************************************/
</script>

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
			<h3>Registrar matricula</h3>
		    </div>
		    <div class="widget-content" >
			
			<form  method="post" class="needs-validation" novalidate>
			    <div class="form-group">
				<label for="id_profesor">Seleccione a un profesor</label>
				<select id="id_profesor" name="id_profesor" class="form-control" required>
				    <?php foreach ($profesores as $profesor):?>
					<option value="<?= $profesor["idProfesor"]?>"> <?= $profesor["nombres"]." ".$profesor["apellidos"]; ?></option>
				    <?php endforeach; ?> 
				</select>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-4">
				    <label for="id_grado">Seleccione el grado</label>
				    <select onchange="formarSecciones()" id="id_grado" name="id_grado" class="form-control" required>
					<option value=""></option>					
					<?php foreach ($grados as $grado): ?>
					    <option value="<?= $grado["idGrado"]?>"> <?= $grado["grado"]; ?></option>
					<?php endforeach; ?> 
				    </select>
				</div>
				<p id="hola"> </p>
				<div class="form-group col-md-4">
				    <label for="id_seccion">Seleccione la secci&oacute;n</label>
				    <select id="id_seccion" name="id_seccion" class="form-control" required>
				    </select>
				</div>

				<div class="form-group col-md-4">
				    <label for="id_ciclo">Seleccione el peri&oacute;do</label>
				    <select id="id_ciclo" name="id_ciclo" class="form-control" required>
					<?php foreach ($ciclos as $ciclo): ?>
					    <option value="<?= $ciclo["idCiclo"]?>"> <?= $ciclo["ciclo"]; ?></option>
					<?php endforeach; ?> 
				    </select>
				</div>
			    </div>

			    <div class="form-row">
				<div class="form-group col-md-9">
				    <label for="monto">Seleccione los cursos que ense&ntilde;ar&aacute; el profesor</label>
				    <select id="id_curso" name="id_curso" class="form-control" required>
					<?php foreach ($cursos as $curso): ?>
					    <option value="<?= $curso["idCurso"]?>"> <?= $curso["curso"]; ?></option>
					<?php endforeach; ?> 
				    </select>
				</div>
				<div class="form-group col-md-3">
				    <label for="boton"> A&ntilde;adir </label>
				    <input class="form-control btn btn-outline-primary" onclick="agregarCurso()" id="boton" type="button" value="Agregar curso">
				</div>
			    </div>

			    <table class="table table-bordered table-striped" id="tabla">
				<thead>
				    <tr>
					<th>Cursos</th>
					<th>Grado</th>
					<th>Secci&oacute;n</th>
					<th class="text-center">Acci&oacute;n</th>
				    </tr>
				</thead>
				<tbody>
				    
				</tbody>
			    </table>

			    <div class="form-group" id="cursos">

			    </div>

			    <button  type="submit" class="btn btn-primary">Registrar</button>
                            <a href="<?= base_url().'/index.php/curSecPorProfesor/listar'; ?>" class="btn btn-danger"> Cancelar </a>
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
