<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 " style="font-family: monospace;">
	<p>
    Esta sección contiene la informaciónde la gestión de secciones, el cual se puede acceder a través del POSTMAN en la siguiente dirección:
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/direccion.jpg'; ?>"> </p>
    Cabe mencionar que es necesario que obtengas el token de seguridad para tener acceso a todos los recursos, si todav&iacute;a no cuentas con uno, puedes solicitarlo aqu&iacute;

    </p>

    <h2> Método GET</h2>

    <p> A través de este método usted podrá solicitar información de los secciones que tiene un cliente determinado, se debe rellenar
	un campo más en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener su lista de secciones registradas
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/get.jpg'; ?>"> </p>
    
    <h3> Resultado del m&eacute;todo GET </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-get.jpg'; ?>"> </p>

    
    <h3> M&eacute;todo GET - SHOW </h3>

    <p> A través de este método usted podr&aacute; solicitar informaci&oacute;n de una sección en especif&iacute;co de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener la sección.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/show.jpg'; ?>"> </p>
    <h3> Resultado del m&eacute;todo GET - SHOW </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-show.jpg'; ?>"> </p>


    <h2> M&eacute;todo POST</h2>

    <p> A trav&eacute;s de este m&eacute;todo usted podr&aacute; registrar informaci&oacute;n de una sección de un cliente determinado, se debe rellenar
	un campo m&aacute;s en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente en el cual se crear&aacute; el usuario
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/post.jpg'; ?>"> </p>
    <h2> Resultado del Método POST</h2>
    <p>El resultado del envío de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-post.jpg'; ?>"> </p>

    <h2> Método PUT</h2>
    <p>A través de este método puede actualizar información de una sección en particular, para solicitar esta información se debe  rellenar  un campo más en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente en el cual se actualizará la sección y también debe hacerlo a trávés de la siguiente dirección:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/put.jpg'; ?>"> </p>
    <h2> Resultado del Método PUT</h2>
    <p>El resultado de la actualización de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-put.jpg'; ?>"> </p>
    <h2> Método DELETE</h2>
    <p>A través de este método puede eliminar información de una sección en particular, para solicitar esta información se debe  rellenar  un campo más en la cabecera (headers) con el nombre de "cliente", esto representa el id (en este caso 1) del cliente en el cual se eliminará la sección y también debe hacerlo a trávés de la siguiente dirección:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/delete.jpg'; ?>"> </p>
    <h2> Resultado del Método PUT</h2>
    <p>El resultado de la actualización de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-delete.jpg'; ?>"> </p>

</main>
