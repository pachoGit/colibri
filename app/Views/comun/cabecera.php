<!doctype html>
<html lang="en">
    <head>
	<meta http-equiv="Content-type" content="text/html"  charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Jekyll v4.1.1">
	<title>Coliibri Software</title>

	<!-- Bootstrap core CSS -->
	<link href="<?= base_url().'/public/bootstrap-4.5.2/css/bootstrap.min.css';?>" rel="stylesheet">

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
	<link href="<?php echo base_url().'/app/Views/dashboard.css';?>" rel="stylesheet">
    </head>
    <body>
	<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
	    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#"> <img class="img-thumbnail" src="<?= base_url().'/public/media/icono-contacto.png'; ?>">  <?= $nombre; ?> </a>
	    <div class="container">
		<!--<p class="navbar navbar-dark text-white h5">Administrador</p>-->
		<blockquote class="blockquote text-right">
		    <p class="mb-0 text-white text-center"><?= $perfil; ?></p>
		    <footer class="blockquote-footer text-light"><img src="<?= base_url().'/public/media/calendario.png'; ?>"> <?php echo date("d-m-Y"); ?> <img src="<?= base_url().'/public/media/reloj.png'; ?>"> 13:45</footer>
		</blockquote>
	    </div>
	    <div class="container">
		<p class="navbar h1 text-white"> <?= $titulo; ?> </p>
	    </div>
	    <div class="container d-flex justify-content-end">
		<img class="img-thumbnail" src="<?= base_url().'/public/media/colibri.jpg'; ?>" style="width:48px; height:48px;">
	    </div>
	</nav>
