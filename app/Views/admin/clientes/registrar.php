<script>
 function traerCorreo(correo, callback)
 {
     var respuesta;
     var xhttp = new XMLHttpRequest();

     xhttp.onreadystatechange = function()
     {
	 if (xhttp.readyState == 4 && xhttp.status == 200)
	 {
	     <?php if ($_SERVER["SERVER_NAME"] == "localhost") {?>
	     var tam = -266; // Copiar aqui lo que tienes en $_SESSION["tam"]
	     respuesta = this.responseText.slice(0, tam);
	     <?php } else { ?>
	     respuesta = this.responseText;
	     <?php } ?>
	     if (callback)
		 callback(respuesta);
	 }
     }
     xhttp.open("POST", "../clientes/traerCorreo", true);
     xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     xhttp.send("correo=" + correo);
 }
 
 // Funcion para copiar la direccion de correo electronico del
 // formulario del cliente hacia el usuario
 function ponerCorreoInst(entrada)
 {
     traerCorreo(entrada.value, function(respuesta) {
	 var correo = JSON.parse(respuesta);
	 if (correo.length == 0)
	     document.getElementById("correo").value = entrada.value;
     });
 }

 function ponerCorreoUs(entrada)
 {
     traerCorreo(entrada.value, function(respuesta) {
	 var correo = JSON.parse(respuesta);
	 if (correo.length != 0)
	 {
	     alert("Este correo ya existe, por favor ingrese otro");
	     entrada.value = "";
	 }
     });
 }

 function ponerNombre(entrada)
 {
     document.getElementById("nombres").value = entrada.value;
 }
 
 function ponerApellido(entrada)
 {
     document.getElementById("apellidos").value = entrada.value;
 }
 
</script>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 py-md-4 px-md-4">
    <h2> Registrar cliente </h2>
    <form  method="post" action="<?= base_url().'/index.php/clientes/crear'; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>

	<div class="form-group">
	    <label for="cliente">Nombre de la Instituci&oacute;n</label>
	    <input type="text" class="form-control" name="cliente" id="cliente" placeholder="IE. Tarapoto N&#176; 123" required>
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	    <div class="invalid-feedback">
		Por favor rellene este campo
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="nombreEncar">Nombres del encargado</label>
		<input type="text" onchange="ponerNombre(this)" name="nombreEncar" class="form-control" id="nombreEncar" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Por favor rellene este campo
		</div>
	    </div>
	    <div class="form-group col-md-6">
		<label for="apellidoEncar">Apellidos del encargado</label>
		<input type="text" onchange="ponerApellido(this)" name="apellidoEncar" class="form-control" id="apellidoEncar" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Por favor rellene este campo
		</div>
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-4">
		<label for="ruc">RUC</label>
		<input type="text" class="form-control" placeholder="11223344556" name="ruc" id="ruc" required minlength="11" maxlength="11">
		<div class="invalid-feedback">
		    Ingrese solo 11 n&uacute;meros
		</div>
	    </div>

	    <div class="form-group col-md-4">
		<label for="correoCliente">Correo electr&oacute;nico</label>
		<input type="email" onchange="ponerCorreoInst(this)" value="" class="form-control" name="correoCliente" id="correoCliente" placeholder="ejemplo@compania.com" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese un formato de correo electr&oacute;nico v&aacute;lido
		</div>
	    </div>

	    <div class="form-group col-md-4">
		<label for="fechaContrato">Fecha de contrato</label>
		<input type="date" class="form-control" value="<?= date("Y-m-d"); ?>" name="fechaContrato" id="fechaContrato" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Seleccione una fecha v&aacute;lida
		</div>
	    </div>
	</div>

	<div class="form-group">
	    <label for="url">URL del cliente</label>
	    <input type="text" class="form-control" name="url" id="url">
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	</div>

	<div class="form-group">
	    <label for="foto">Seleccione la insignia de la instituci&oacute;n</label>
	    <input type="file" accept="image/*" class="form-control-file" name="foto" id="foto">
	</div>
	
	<hr class="border border-dark">

	<!-- Aqui comienza el formulario para la creacion de un usuario del nuevo cliente -->
	<h3> Creaci&oacute;n del usuario administrador</h3>
	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="nombres">Nombres</label>
		<input type="text" name="nombres" value="" class="form-control" id="nombres" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese su nombre
		</div>
	    </div>
	    <div class="form-group col-md-6">
		<label for="apellidos">Apellidos</label>
		<input type="text" name="apellidos" value="" class="form-control" id="apellidos" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese su apellido
		</div>
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="correo">Correo electr&oacute;nico</label>
		<input type="email" onchange="ponerCorreoUs(this)" value="" name="correo" class="form-control" id="correo" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese un correo electr&oacute;nico valido
		</div>
	    </div>
	    <div class="form-group col-md-6">
		<label for="contra">Contrase&ntilde;a </label>
		<input type="password" class="form-control" name="contra" id="contra" required minlength="3" required>
		<div class="invalid-feedback">
		    Rellene este campo
		</div>
	    </div>
	</div>

	<div class="form-group">
	    <label for="inputAddress">Direcci&oacute;n</label>
	    <input type="text" class="form-control" name="direccion" id="inputAddress" placeholder="1234 Main St" required>
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	    <div class="invalid-feedback">
		Ingrese algo aqu&iacute;
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-4">
		<label for="dni">DNI</label>
		<input type="text" class="form-control" name="dni" id="dni" required minlength="8" maxlength="8">
		<div class="invalid-feedback">
		    Ingrese solo 8 n&uacute;meros
		</div>
	    </div>

	    <div class="form-group col-md-4">
		<label for="edad">Edad</label>
		<input type="number" class="form-control" name="edad" id="edad" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese un n&uacute;mero natural
		</div>
	    </div>

	</div>

	<div class="form-check form-check-inline form-group">
	    <input class="form-check-input" type="radio" name="sexo" id="masculino" value="M" required>
	    <label class="form-check-label" for="masculino">
		Masculino
	    </label>
	</div>
	<div class="form-check form-check-inline form-group">
	    <input class="form-check-input" type="radio" name="sexo" id="femenino" value="F" required>
	    <label class="form-check-label" for="femenino">
		Femenino
	    </label>
	</div>

	<div class="form-group">
	    <label for="rutaFoto">Escoja un foto</label>
	    <input type="file" accept="image/*" name="rutaFoto" class="form-control-file" id="rutaFoto">
	</div>

	<div class="form-group">
	    <label for="comentario">Comentario</label>
	    <textarea type="text" class="form-control" name="comentario" id="comentario" placeholder="Ingrese alg&uacute;n comentario"></textarea>
	</div>
	
	<button type="submit" class="btn btn-primary">Registrar</button>
        <a href="<?= base_url().'/index.php/admin'; ?>" class="btn btn-danger"> Cancelar </a>
    </form>

</main>

<script>
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
