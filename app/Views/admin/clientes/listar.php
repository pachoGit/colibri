<!-- Cuerpo -->
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 py-md-4 px-md-4">
    <section class="jumbotron text-center">
	<div class="container">
	    <h1>Lista de clientes</h1>
	    <p class="lead text-muted">Clientes registrados y usando los servicios de Colibri</p>
	    <p>
		<a href="<?= base_url().'/index.php/clientes/registrar'; ?>" class="btn btn-primary my-2">Nuevo cliente</a>
		<a href="#" class="btn btn-secondary my-2">Secondary action</a>
	    </p>
	</div>
    </section>

    <div class="album py-5 bg-light">
	<div class="container">
	    <div class="row">
		<?php foreach ($clientes as $cliente) :?>
		<div class="col-md-4">
		    <div class="card mb-4 shadow-sm">
			<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="<?= base_url().$cliente["foto"]; ?>" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"> <title><?= $cliente["correoCliente"]; ?></title><rect width="100%" height="100%" fill="#55595c"/> </img>
			<div class="card-body">
			    <p class="card-text"><?= $cliente["cliente"]; ?></p>
			    <div class="d-flex justify-content-between align-items-center">
				<div class="btn-group">
				    <a href="<?= base_url().'/index.php/clientes/ver/'.$cliente["idCliente"]; ?>" type="button" class="btn btn-sm btn-outline-primary">Ver</a>
				    <a href="" type="button" class="btn btn-sm btn-outline-secondary">Editar</a>
				    <a href="<?= base_url().'/index.php/clientes/delete/'.$cliente["idCliente"]; ?>" type="button" class="btn btn-sm btn-outline-danger">Eliminar</a>				    
				</div>
				<small class="text-muted"><?= $cliente["fechaContrato"]; ?></small>
			    </div>
			</div>
		    </div>
		</div>
		<?php endforeach; ?>
	    </div>
	</div>
    </div>
    
</main>

<!-- Fin cuerpo -->
<script>
 function alerta()
 {
     var m = confirm("Desea eliminar a este usuario?");
     if (m)
	 return true;
     else
	 return false;
 }
</script>
