<div class="container-fluid">
    <div class="row">
	<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
	    <div class="sidebar-sticky pt-3 mt-4">
		<div class="accordion" id="accordionExample">

		    <div class="card">
			<div class="card-header" id="modulo-seguridad">
			    <h2 class="mb-0">
				<button class="btn card-link btn-block text-left" type="button" data-toggle="collapse" data-target="#seguridad" aria-expanded="true" aria-controls="seguridad">
				    Seguridad
				</button>
			    </h2>
			</div>
			<div id="seguridad" class="collapse" aria-labelledby="modulo-seguridad" data-parent="#accordionExample">
			    <div class="card-body">
				<ul class="nav flex-column">
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/usuarios';1?>">
					    Usuarios
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Perfiles
					</a>
				    </li>
				</ul>
			    </div>
			</div>
		    </div>


		    <div class="card">
			<div class="card-header" id="modulo-mantenimiento">
			    <h2 class="mb-0">
				<button class="btn card-link btn-block text-left" type="button" data-toggle="collapse" data-target="#mantenimiento" aria-expanded="true" aria-controls="mantenimiento">
				    Mantenimiento
				</button>
			    </h2>
			</div>
			<div id="mantenimiento" class="collapse" aria-labelledby="modulo-mantenimiento" data-parent="#accordionExample">
			    <div class="card-body">
				<ul class="nav flex-column">
				    <li class="nav-item">
					<a class="nav-link" href="usuarios.php">
					    Alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Profesores
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="usuarios.php">
					    Grados
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Secciones
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="usuarios.php">
					    Cursos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Sedes
					</a>
				    </li>
				</ul>
			    </div>
			</div>
		    </div>



		    <div class="card">
			<div class="card-header" id="modulo-pagos">
			    <h2 class="mb-0">
				<button class="btn card-link btn-block text-left" type="button" data-toggle="collapse" data-target="#pagos" aria-expanded="true" aria-controls="pagos">
				    Pagos
				</button>
			    </h2>
			</div>
			<div id="pagos" class="collapse" aria-labelledby="modulo-pagos" data-parent="#accordionExample">
			    <div class="card-body">
				<ul class="nav flex-column">
				    <li class="nav-item">
					<a class="nav-link" href="usuarios.php">
					    Pagos de alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Pagos a profesores
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Motivo de pagos
					</a>
				    </li>
				</ul>
			    </div>
			</div>
		    </div>



		    <div class="card">
			<div class="card-header" id="modulo-matricula">
			    <h2 class="mb-0">
				<button class="btn card-link btn-block text-left" type="button" data-toggle="collapse" data-target="#matricula" aria-expanded="true" aria-controls="matricula">
				    Matricula
				</button>
			    </h2>
			</div>
			<div id="matricula" class="collapse" aria-labelledby="modulo-matricula" data-parent="#accordionExample">
			    <div class="card-body">
				<ul class="nav flex-column">
				    <li class="nav-item">
					<a class="nav-link" href="usuarios.php">
					    Alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="perfiles.php">
					    Profesores
					</a>
				    </li>
				</ul>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	</nav>
