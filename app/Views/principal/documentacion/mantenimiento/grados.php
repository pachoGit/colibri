<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 " style="font-family: Arial; font-size: 18px">
    <p>
    Esta sección contiene la informaciónde la gestión de grados, el cual se puede acceder a través del POSTMAN en la siguiente dirección:
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/direccion.jpg'; ?>"> </p>
    Cabe mencionar que es necesario que obtengas el token de seguridad para tener acceso a todos los recursos, si todav&iacute;a no cuentas con uno, puedes solicitarlo <a href="<?= base_url().'/index.php/home'?>"> aquí </a>

    </p>

    <h2> Método GET</h2>

    <p> A través de este método usted podrá solicitar información de los grados que tiene un cliente determinado,en este caso tendrá que especificar el cliente en el campo del headers de POSTMAN "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener su lista de grados registradas
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/get.jpg'; ?>"> </p>
    
    <h3> Resultado del m&eacute;todo GET </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/resultado-get.jpg'; ?>"> </p>

    
    <h3> M&eacute;todo GET - SHOW </h3>

    <p> A través de este método usted podr&aacute; solicitar informaci&oacute;n de un grado en especif&iacute;co de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del headers de POSTMAN "cliente", esto representa el id (en este caso 1) del cliente del cual se desea obtener la sección.
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/show.jpg'; ?>"> </p>
    <h3> Resultado del m&eacute;todo GET - SHOW </h3>
    <p> Usted debe obtener un resultado como este </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/resultado-show.jpg'; ?>"> </p>


    <h2> M&eacute;todo POST</h2>

    <p>A través de este método usted podrá registrar la información de un grado de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente".
    </p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/post.jpg'; ?>"> </p>
    <h2> Resultado del Método POST</h2>
    <p>El resultado del envío de un grado en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/resultado-post.jpg'; ?>"> </p>

    <h2> Método PUT</h2>
    <p>A través de este método usted podrá editar la información de un grado de un cliente determinado, en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente"</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/put.jpg'; ?>"> </p>
    <h2> Resultado del Método PUT</h2>
    <p>El resultado de la actualización de un grado en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/resultado-put.jpg'; ?>"> </p>
    <h2> Método DELETE</h2>
    <p>A través de este método usted podrá eliminar la información de un grado de un cliente determinado,en este caso tendrá que especificar el cliente en el campo del body de POSTMAN "id_cliente".</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/delete.jpg'; ?>"> </p>
    <h2> Resultado del Método DELETE</h2>
    <p>El resultado de la eliminación de un grado en particular debería ser como se muestra en la siguiente imagen:</p>
    <p><img src="<?= base_url().'/public/documentacion/mantenimiento/grados/resultado-delete.jpg'; ?>"> </p>

</main>
</div>
</div>
