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
					<a class="nav-link" href="<?= base_url().'/index.php/principal/usuarios';?>">
					    Usuarios
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/perfiles';?>">
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
					<a class="nav-link" href="<?= base_url().'/index.php/principal/alumnos';?>">
					    Alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/profesores';?>">
					    Profesores
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/grados';?>">
					    Grados
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/secciones';?>">
					    Secciones
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/cursos';?>">
					    Cursos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/sedes';?>">
					    Sedes
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/periodos';?>">
					    Periódos
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
					<a class="nav-link" href="<?= base_url().'/index.php/principal/palumnos';?>">
					    Pagos de alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/pprofesores';?>">
					    Pagos a profesores
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/pmotivo';?>">
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
					<a class="nav-link" href="<?= base_url().'/index.php/principal/malumnos';?>">
					    Alumnos
					</a>
				    </li>
				    <li class="nav-item">
					<a class="nav-link" href="<?= base_url().'/index.php/principal/mprofesores';?>">
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
