<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 " style="font-family: Arial; font-size: 18px">
	<p>
    Esta sección contiene la informaciónde la gestión de secciones, el cual se puede acceder a través del POSTMAN en la siguiente dirección:
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/direccion.jpg'; ?>"> </p>
    Cabe mencionar que es necesario que obtengas el token de seguridad para tener acceso a todos los recursos, si todav&iacute;a no cuentas con uno, puedes solicitarlo aqu&iacute;

    </p>

    <h2> Método GET</h2>

    <p> A través de este método usted podrá solicitar información de los secciones que tiene un cliente determinado,en este caso tendrá que especificar el cliente en el campo del headers de POSTMAN "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener su lista de secciones registradas
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/get.jpg'; ?>"> </p>
    
    <h3> Resultado del m&eacute;todo GET </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-get.jpg'; ?>"> </p>

    
    <h3> M&eacute;todo GET - SHOW </h3>

    <p> A través de este método usted podr&aacute; solicitar información de una sección en especif&iacute;co de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del headers de POSTMAN "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener la sección.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/show.jpg'; ?>"> </p>
    <h3> Resultado del m&eacute;todo GET - SHOW </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-show.jpg'; ?>"> </p>


    <h2> M&eacute;todo POST</h2>

    <p>A través de este método usted podrá registrar la información de una sección de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente".
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/post.jpg'; ?>"> </p>
    <h2> Resultado del Método POST</h2>
    <p>El resultado del envío de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-post.jpg'; ?>"> </p>

    <h2> Método PUT</h2>
    <p>A través de este método usted podrá editar la información de una sección de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente"</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/put.jpg'; ?>"> </p>
    <h2> Resultado del Método PUT</h2>
    <p>El resultado de la actualización de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-put.jpg'; ?>"> </p>
    <h2> Método DELETE</h2>
    <p>A través de este método usted podrá eliminar la información de una sección de un cliente determinado,en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente".</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/delete.jpg'; ?>"> </p>
    <h2> Resultado del Método DELETE</h2>
    <p>El resultado de la eliminación de una sección en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/secciones/resultado-delete.jpg'; ?>"> </p>

</main>
