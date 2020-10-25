<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="sidebar-sticky pt-3 mt-4">
        <div class="accordion" id="accordionExample">
	  <?php $contador = 1; foreach ($modulos as $modulo): ?>
	  <div class="card">
	    <div class="card-header" id="<?php echo 'cabecera'.$contador; ?>">
	      <h2 class="mb-0">
		<button class="btn card-link btn-block text-left" type="button" data-toggle="collapse" data-target="#<?php echo 'colapse'.$contador; ?>" aria-expanded="true" aria-controls="<?php echo 'colapse'.$contador; ?>">
		  <?= $modulo["modulo"]; ?>
		</button>
	      </h2>
	    </div>

	    <div id="<?php echo 'colapse'.$contador; ?>" class="collapse" aria-labelledby="<?php echo 'cabecera'.$contador; ?>" data-parent="#accordionExample">
	      <div class="card-body">
		
		<ul class="nav flex-column">

		  <?php foreach ($modulo["hijos"] as $hijo): ?>
		  <li class="nav-item">
		    <a class="nav-link" href="<?= base_url()."/index.php/".$hijo['url'];?>">

		      <?= $hijo["modulo"]; ?>
		    </a>
		  </li>
		  <?php endforeach; ?>
		</ul>
	      </div>
	    </div>
	  </div>
	  <?php $contador++; endforeach;?>
	</div>
	<a href="<?= base_url().'/index.php/casa/salir'; ?>"  class="btn btn-outline-danger mt-5"> SALIR </a>
      </div>
    </nav>
