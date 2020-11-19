<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 " style="font-family: Arial; font-size: 18px">
    <p>
    Esta secci&oacute;n contiene la informaci&oacute;n de la gesti&oacute;n de los pagos a los alumnos, el cual se puede acceder a trav&eacute;s del POSTMAN en la siguiente direcci&oacute;n:
    Cabe mencionar que es necesario que obtengas el token de seguridad para tener acceso a todos los recursos, si todav&iacute;a no cuentas con uno, puedes solicitarlo <a href="<?= base_url().'/index.php/home'?>"> aquí </a>
    </p>


    <h2> M&eacute;todo GET</h2>
    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; solicitar informaci&oacute;n de los pagos de los alumnos de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener su lista de pagos registrados
    </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/get.png'; ?>"> </p>
    
    <h3> Resultado del m&eacute;todo GET </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/resultado_get.png'; ?>"> </p>

    
    <h3> M&eacute;todo GET - SHOW </h3>

    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; solicitar informaci&oacute;n de un pago de un alumno en especif&iacute;co de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener el usuario
    </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/get_show.png'; ?>"> </p>
    <h3> Resultado del m&eacute;todo GET - SHOW </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/resultado_get_show.png'; ?>"> </p>


    <h2> M&eacute;todo POST</h2>
    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; registrar la informaci&oacute;n de un pago de un alumno de un cliente determinado, en este caso tendrá que
	especificar el cliente en el campo del body de POSTMAN.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/post.png'; ?>"> </p>

    <h2> Método PUT </h2>
    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; editar la informaci&oacute;n de un pago de un alumno de un cliente determinado.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/put.png'; ?>"> </p>
    
    <h2> Método DELETE </h2>
    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; eliminar la informaci&oacute;n de un pago de un alumno de un cliente determinado.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/pagos/alumnos/delete.png'; ?>"> </p>
</main>
</div>
</div>
