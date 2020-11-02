<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 " style="font-family: monospace;">
    <p>
    Esta secci&oacute;n contiene la informaci&oacute;n de la gesti&oacute;n de usuarios, el cual se puede acceder a trav&eacute;s del POSTMAN en la siguiente direcci&oacute;n:
    Cabe mencionar que es necesario que obtengas el token de seguridad para tener acceso a todos los recursos, si todav&iacute;a no cuentas con uno, puedes solicitarlo aqu&iacute;
    </p>

    <h2> M&eacute;todo GET</h2>

    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; solicitar informaci&oacute;n de los usuarios de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener su lista de usuarios registrados
    </p>
    <p><img src="<?= base_url().'/public/documentacion/seguridad/usuarios/get.png'; ?>"> </p>
    
    <h3> Resultado del m&eacute;todo GET </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/seguridad/usuarios/resultado_get.png'; ?>"> </p>

    
    <h3> M&eacute;todo GET - SHOW </h3>

    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; solicitar informaci&oacute;n de un usuario en especif&iacute;co de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener el usuario
    </p>
    <p><img src="<?= base_url().'/public/documentacion/seguridad/usuarios/get_show.png'; ?>"> </p>
    <h3> Resultado del m&eacute;todo GET - SHOW </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/seguridad/usuarios/resultado_get.png'; ?>"> </p>


    <h2> M&eacute;todo POST</h2>

    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; registrar informaci&oacute;n de un usuario de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente en el cual se crear&aacute; el usuario
    </p>
    <p><img src="<?= base_url().'/public/documentacion/seguridad/usuarios/get.png'; ?>"> </p>


</main>