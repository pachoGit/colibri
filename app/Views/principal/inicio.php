<main>
    <div style="background-image: url('<?= base_url().'/public/media/colibri.png'; ?>'); background-size: 500px; background-repeat: no-repeat; background-position: relative;" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
	<div class="col-md-5 p-lg-5 mx-auto my-5">
	    <h1 class="display-4 font-weight-normal text-primary">Colibri</h1>
	    <p class="lead font-weight-normal">Colibri es una plataforma de administraci&oacute;n educativa.</p>
	    <a class="btn btn-outline-primary" href="#">Ver m&aacute;s</a>
	</div>
    </div>


    <div class="container" id="caracteristicas">
	<h2 class="text-primary text-center my-3"> Caracter&iacute;sticas </h2>
	<div class="card-deck mb-3 text-center">
	    <div class="card mb-4 shadow-sm shadow-lg">
		<div class="card-header">
		    <h4 class="my-0 font-weight-normal">Sencillo</h4>
		</div>
		<div class="card-body">
		    <h1 class="card-title pricing-card-title"></small></h1>
		    <p class="text-justify">La plataforma de administraci&oacute;n es f&aacute;cil e intuitivo de usar. </p>
		    <button type="button" class="btn btn-lg btn-block btn-outline-primary">Ver m&aacute;s</button>
		</div>
	    </div>
	    <div class="card mb-4 shadow-sm shadow-lg">
		<div class="card-header">
		    <h4 class="my-0 font-weight-normal">Adaptable</h4>
		</div>
		<div class="card-body">
		    <h1 class="card-title pricing-card-title"></h1>
		    <p class="text-justify">A diferencia de otras soluciones en las que el colegio se tiene que adaptar al software. Nuestro sistema de administraci&oacute;n escolar, se adapta a las necesidades espec&iacute;ficas del colegio.. </p>
		    <button type="button" class="btn btn-lg btn-block btn-outline-primary">Ver m&aacute;s</button>
		</div>
	    </div>
	    <div class="card mb-4 shadow-sm shadow-lg">
		<div class="card-header">
		    <h4 class="my-0 font-weight-normal">Documentado</h4>
		</div>
		<div class="card-body">
		    <h1 class="card-title pricing-card-title"></h1>
		    <p class="text-justify">Usted puede realizar una nueva plataforma usando nuestra API, documentada y fac&iacute;l de usar, expanda su sistema admnistrativo como usted prefiera.</p>
		    <button type="button" class="btn btn-lg btn-block btn-outline-primary">Ver m&aacute;s</button>
		</div>
	    </div>
	</div>


<!-- Modulos -->

    <div class="container" id="modulos">
	<h2 class="text-primary text-center my-3"> M&oacute;dulos </h2>	

	<div class="row my-4">
	  <div class="col-sm-6">
	    <div class="card shadow ">
	      <div class="card-body">
	        <h4 class="card-title">Seguridad</h4>
	        <p class="text-justify"> Gestione usuarios y perfiles, con sus respectivos permisos de acceso. </p>
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-6">
	    <div class="card shadow">
	      <div class="card-body">
	        <h4 class="card-title">Mantenimiento</h4>
	        <p class="text-justify"> Gestione a sus alumnos, profesores, cursos, grados y secciones de manera facil e intuitiva. </p>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="row">  
	   <div class="col-sm-6">
	    <div class="card shadow">
	      <div class="card-body">
	        <h4 class="card-title">Pagos</h4>
	        <p class="text-justify"> Gestione los pagos de sus alumnos y a lo profesores. </p>
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-6">
	    <div class="card shadow">
	      <div class="card-body">
	        <h4 class="card-title">Matriculas</h4>
	       <p> Gestione las matr&iacute;las de sus alumnos y profesores. </p>
	    </div>
	  </div>
	</div>

	</div>

<!-- Contactenos  -->
    <div class="container my-5" id="contactenos">
	<h2 class="text-primary text-center"> Cont&aacute;ctenos </h2>
	<div class="row py-1">
	    <!-- Columna de la informacion -->
	    <div class="col">
		<ul class="list-group list-group-flush">
		    <li class="list-group-item">Jr Per&uacute; 235</li>
		    <li class="list-group-item">colibri@gmail.com</li>
		    <li class="list-group-item">992342432</li>
		</ul>
	    </div>

	    <!-- Columna del formulario -->
	    <div class="col">
		<!--<h4 class="mb-3">Billing address</h4> -->
		<form class="needs-validation" novalidate>
		    <div class="row">
			<div class="col-md-6 mb-3">
			    <label for="firstName">Nombres</label>
			    <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
			    <div class="invalid-feedback">
				Por favor rellene este campo
			    </div>
			</div>
			<div class="col-md-6 mb-3">
			    <label for="lastName">Apellidos</label>
			    <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
			    <div class="invalid-feedback">
				Por favor rellene este campo
			    </div>
			</div>
		    </div>


		    <div class="row">
			<div class="col-md-6 mb-3">
			    <label for="telefono">Tel&eacute;fono</label>
			    <input type="text" class="form-control" id="telefono" placeholder="" value="" required>
			    <div class="invalid-feedback">
				Por favor rellene este campo
			    </div>
			</div>
			<div class="col-md-6 mb-3">
			    <label for="correo">Correo</label>
			    <input type="text" class="form-control" id="correo" placeholder="" value="" required>
			    <div class="invalid-feedback">
				Por favor ingrese un correo v&acute;lido
			    </div>
			</div>
		    </div>


		    <div class="mb-3">
			<label for="entidad">Entidad educativa </label>
			<input type="entidad" class="form-control" id="entidad">
			<div class="invalid-feedback">
			    Por favor rellene este campo
			</div>
		    </div>

		    <div class="mb-3">
			<label for="mensaje">Mensaje </label>
			<textarea type="text" name="mensaje" class="form-control" id="mensaje" > </textarea>
			<div class="invalid-feedback">
			    Rellene este campo
			</div>
		    </div>

		    <hr class="mb-4">
		    <button class="btn btn-primary btn-lg btn-block" type="submit">Enviar</button>
		</form>
	    </div>
	</div>
    </div>
    
</main>
