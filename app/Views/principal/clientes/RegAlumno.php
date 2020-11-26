<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://colibri.informaticapp.com/solicitud",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>
            "nombrePadre=".$_POST["nombrePadre"].
			    "&correoPadre=".$_POST["correoPadre"].
			    "&telefono=".$_POST["telefono"].
			    "&nombreHijo=".$_POST["nombreHijo"].
			    "&nivel=".$_POST["nivel"].
			    "&id_cliente=".$_POST["id_cliente"],
        CURLOPT_HTTPHEADER => array(
	    "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    $mensaje = $data["Detalles"];
    
    if ($data["Estado"] != 200)
	echo "<script> alert('".$mensaje."');window.location.href='".base_url().'/index.php/principal/instituciones'."' </script>";
    else
	echo "<script> alert('Se envio la solicitud correctamente');window.location.href='".base_url().'/index.php/principal/instituciones'."' </script>";

}

?>



<main role="main" class="col-md-9 ml-sm-auto col-lg-10 py-md-4 px-md-4">
    <h2> Solicitud de Información</h2>

    <form method="post"  class="needs-validation" novalidate>

	<div class="form-group">
	    <label for="cliente">Nombre y apellido</label>
	    <input type="text" class="form-control" name="nombrePadre" id="nombrePadre" placeholder="Nombre y Apellido *" required>
	    <input type="hidden" name="id_cliente" value="<?php echo $id; ?>">
	
	    <div class="valid-feedback">
		Esto est&aacute; bien
	    </div>
	    <div class="invalid-feedback">
		Por favor rellene este campo
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-6">
		<label for="nombreEncar">Correo Electrónico</label>
		<input type="email"  name="correoPadre" class="form-control" placeholder ="Correo*"id="nombreEncar" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Por favor rellene este campo
		</div>
	    </div>
	    <div class="form-group col-md-6">
		<label for="apellidoEncar">Celular</label>
		<input type="text"  name="telefono" class="form-control" id="apellidoEncar" maxlength="9" required placeholder="Celular*">
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Por favor rellene este campo
		</div>
	    </div>
	</div>

	<div class="form-row">
	    <div class="form-group col-md-8">
		<label for="nombreHijo">Nombre y Apellido del Hijo</label>
		<input type="text" class="form-control" placeholder="Nombre y Apellido del Hijo*" name="nombreHijo" id="nombreHijo" required >
		<div class="invalid-feedback">
		    Ingrese solo 11 n&uacute;meros
		</div>
	    </div>
	</div>
	<div class="form-row">
	    <div class="form-group col-md-8">
		<label for="correoCliente">Grado y Nivel en el que está interesado</label>
		<input type="text" value="" class="form-control" name="nivel" id="correoCliente" placeholder="Grado y Nivel en el que está interesado*" required>
		<div class="valid-feedback">
		    Esto est&aacute; bien
		</div>
		<div class="invalid-feedback">
		    Ingrese un formato de correo electr&oacute;nico v&aacute;lido
		</div>
	    </div>
	</div>
	<div >

	    <div class="form-group">
		<div class="form-check">
		    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
		    <label class="form-check-label" for="invalidCheck">
			Aceptar <a class="check-div-a" data-toggle="modal" data-target="#ModalInscripcionAcademia" id="termsActive">Términos y
			Condiciones
			</a>

		    </label>
		    <div class="invalid-feedback">
			Tiene que aceptar los Términos y condiciones
		    </div>
		</div>
	    </div>

            <p class="check-div-p">Aceptar 
            </p>
            <!-- </fieldset>  -->
	</div>
	<br>

	<div><button type="submit" class="btn btn-primary">Enviar Datos</button>
            <a href="<?= base_url().'/index.php/principal/instituciones'; ?>" class="btn btn-danger"> Cancelar </a>
	</div>
	
    </form>



    <div class="modal fade" id="ModalInscripcionAcademia" tabindex="-1" role="dialog"
	 aria-labelledby="ModalInscripcionAcademiaLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h5 class="modal-title text-center d-flex font-weight-bold" id="ModalInscripcionAcademiaLabel">CONDICIONES
			ECONÓMICAS Y DEL SERVICIO DE LA ACADEMIA
			PREUNIVERSITARIA</h5>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    <div class="terms px-4 text-justify" id="terms">
			<p class="ft17"><b>1. </b> Se considerar&aacute; tardanza si el alumno ingresa a la plataforma 5 minutos despu&eacute;s de iniciada la clase.</p>
			<p class="ft17"><b>2. </b> Si se realiza el pago del ciclo de forma fraccionada en dos cuotas, este tendr&aacute; un recargo adicional de S/50. Este monto ser&aacute; recargado en la primera cuota y la segunda cuota debe ser cancelada en un lapso de 15 d&iacute;as.</p>
			<p class="ft17"><b>3. </b> Si el padre de familia, apoderado o alumno mayor de edad opta por retirarse habi&eacute;ndose iniciado las clases, la instituci&oacute;n realizar&aacute; un cobro de S/50 por concepto de penalidad y un cobro de S/30 por concepto de materiales; adem&aacute;s, se le retendr&aacute; el monto de d&iacute;as asistidos y no asistidos sin justificaci&oacute;n.</p>
			<p class="ft17"><b>4. </b> Si el alumno solicita traslado de sede o traslado a otro turno o ciclo dentro del local en donde se le presta servicio, se proceder&aacute; a descontar los d&iacute;as asistidos y no asistidos sin justificaci&oacute;n. El monto restante podr&aacute; ser utilizado para las inscripciones en otro ciclo, debi&eacute;ndose abonar la diferencia.</p>
			<p class="ft17"><b>5. </b> Si el padre de familia, apoderado o alumno mayor de edad requiere la devoluci&oacute;n del dinero antes de iniciado el periodo de clases, la instituci&oacute;n realizar&aacute; el cobro de S/50 por concepto de gastos administrativos.</p>
			<p class="ft17"><b>6. </b> Para el tr&aacute;mite de postergaci&oacute;n de matr&iacute;cula, se proceder&aacute; a descontar los d&iacute;as asistidos y no asistidos sin justificaci&oacute;n. El monto restante ser&aacute; reservado para el siguiente ciclo acad&eacute;mico inmediato en el cual el alumno se matricule. En caso decida no retomar la preparaci&oacute;n al ciclo siguiente, el monto reservado le ser&aacute; devuelto, a solicitud del padre de familia, apoderado o alumno mayor de edad.</p>
			<p class="ft17"><b>7. </b> En caso de no es tar al d&iacute;a en el pago de la pensi&oacute;n, se proceder&aacute; a dar de baja el correo institucional del alumno.</p>
			<p class="ft17"><b>8. </b> El dictado de clases se iniciar&aacute; siempre que se alcance el n&uacute;mero m&iacute;nimo de alumnos matriculados establecidopor la instituci&oacute;n.</p>
			<p class="ft17"><b>9. </b> Manifiesto mi conformidad en caso la instituci&oacute;n cambie la fecha de inicio del dictado de clases del ciclo en cuesti&oacute;n.</p>
			<p class="ft17"><b>10.</b> En los casos de los ciclos anuales, semestrales o intensivos, el cobro de la pensi&oacute;n se hace por 30 d&iacute;as; si pasado el vencimiento de dicha pensi&oacute;n el alumno opta por reingresar a la academia, deber&aacute; cancelar la pensi&oacute;n en los 30 d&iacute;as siguientes de vencida la pensi&oacute;n anterior.</p>
			<p class="ft17"><b>11.</b> Si el alumno pide traslado de pago de la pensi&oacute;n mensual a otro alumno, ya sea que se trate de un alumno nuevo o ya matriculado (en el ciclo anual, semestral, intensivo, o en el ciclo de verano o repaso), se descontar&aacute; de dicho monto la cantidad de S/50 por concepto de traslado de titularidad. Cabe precisar que el traslado de pago se realizar&aacute; &uacute;nica y exclusivamente por las pensiones que corresponden a los meses pendientes de clases.</p>
			<p class="ft111"><em>&ldquo;Declaro tener conocimiento de que la Academia Preuniversitaria Trilce incluir&aacute; la informaci&oacute;n personal de mi menor hijo (a), padres y/o apoderados en su base de datos en un tiempo indefinido para fines de la actividad que desarrolla de acuerdo a la ley N&deg; 29733".</em></p>
		    </div>
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
	    </div>
	</div>
    </div>

</main>

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
