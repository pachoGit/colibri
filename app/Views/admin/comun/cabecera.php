<!-- Cabecera -->
<!doctype html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Jekyll v4.1.1">
	<title>Colibri | Administraci&oacute;n</title>

	<!-- Bootstrap core CSS -->
	<link href="<?= base_url().'/public/bootstrap-4.5.2/css/bootstrap.min.css'; ?>" rel="stylesheet">

        <style>
         .bd-placeholder-img {
             font-size: 1.125rem;
             text-anchor: middle;
             -webkit-user-select: none;
             -moz-user-select: none;
             -ms-user-select: none;
             user-select: none;
         }

	 @media (min-width: 768px) {
	     .bd-placeholder-img-lg {
		 font-size: 3.5rem;
	     }
	 }
	</style>
	<!-- Custom styles for this template -->
        <link href="<?= base_url().'/public/ayudas/dashboard.css'; ?>" rel="stylesheet">
	<link href="<?= base_url().'/public/ayudas/album.css'; ?>" rel="stylesheet">
    </head>
    <body>
	<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Administrador</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	    </button>
	    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            <ul class="navbar-nav px-3">
		<li class="nav-item text-nowrap">
		    <a class="nav-link" href="<?= base_url().'/admin/salir'; ?>">Salir</a>
		</li>
	    </ul>
	</nav>

	<div class="container-fluid">
	    <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
		    <div class="sidebar-sticky pt-3">
                        <ul class="nav flex-column">
			    <li class="nav-item">
				<a class="nav-link active" href="<?= base_url().'/index.php/admin'; ?>">
				    <span data-feather="home"></span>
				    Usuarios <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
				<a class="nav-link" href="<?= base_url().'/admin/publicaciones'; ?>">
                                    <span data-feather="file"></span>
				    Publicaciones
				</a>
			    </li>
			    <li class="nav-item">
				<a class="nav-link" href="<?= base_url().'/admin/paises'; ?>">
				    <span data-feather="shopping-cart"></span>
				    Paises
				</a>
			    </li>
			    <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="users"></span>
				    Customers
				</a>
			    </li>
			    <li class="nav-item">
				<a class="nav-link" href="#">
				    <span data-feather="bar-chart-2"></span>
				    Reports
				</a>
			    </li>
			    <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="layers"></span>
				    Integrations
				</a>
			    </li>
			</ul>

			<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
			    <span>Saved reports</span>
			    <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
				<span data-feather="plus-circle"></span>
                            </a>
                        </h6>
                        <ul class="nav flex-column mb-2">
			    <li class="nav-item">
				<a class="nav-link" href="#">
				    <span data-feather="file-text"></span>
				    Current month
				</a>
			    </li>
			    <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span>
				    Last quarter
				</a>
			    </li>
			    <li class="nav-item">
				<a class="nav-link" href="#">
				    <span data-feather="file-text"></span>
				    Social engagement
				</a>
			    </li>
			    <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span>
				    Year-end sale
				</a>
			    </li>
			</ul>
		    </div>
		</nav>
		<!-- Fin cabecera -->
