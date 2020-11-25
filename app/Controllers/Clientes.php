<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloPerfiles;
use App\Models\ModeloModulos;
use App\Models\ModeloUsuarios;
use App\Models\ModeloPermisos;

class Clientes extends Controller
{
    public function registrar()
    {
        $this->vistasAdminSimple("admin/clientes/registrar");
    }

    public function ver($id)
    {
        $data = ["id" => $id];
        $this->vistasAdmin("admin/clientes/ver", $data);
    }

    // Eliminar un cliente, al hacerlo tambien se eliminara los
    // usuarios que tiene disponible para ingresar al servicio
    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("admin/clientes/eliminar", $data);
    }

    public function editar($id)
    {
        $data = ["id" => $id];
        $this->vistasAdmin("admin/clientes/editar", $data);
    }

    public function traerCorreo()
    {
        $musuarios = new ModeloUsuarios();

        $correo = $musuarios->where("correo", $_POST["correo"])->findAll();
        return json_encode($correo, true);
    }
    
    public function create()
    {
        $solicitud = \Config\Services::request();

        $fecha = date("Y-m-d"); 

        /*** Creacion del registro para la tabla clientes ***/
        
        $ruta = "/public/clientes/".$_FILES["foto"]["name"];
        $ruta2 = "/var/www/html/colibri/public/clientes/".$_FILES["foto"]["name"];
        //$ruta2 = $_SERVER["DOCUMENT_ROOT"]."/public/clientes/".$_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta2);

        $datosCliente = ["cliente"       => $solicitud->getVar("cliente"),
                         "ruc"           => $solicitud->getVar("ruc"),
                         "nombreEncar"   => $solicitud->getVar("nombreEncar"),
                         "apellidoEncar" => $solicitud->getVar("apellidoEncar"),
                         "fechaContrato" => $solicitud->getVar("fechaContrato"),
                         "correoCliente" => $solicitud->getVar("correoCliente"),
                         "url"           => $solicitud->getVar("url"),
                         "foto"          => $ruta,
                         "fechaCreacion" => $fecha];
        $mclientes = new ModeloClientes();
        $id_cliente = $mclientes->insert($datosCliente);

        /*** Creacion del perfil administrador ***/
        
        $mperfiles = new ModeloPerfiles();
        $datosPerfil = ["perfil"        => "Administrador",
                        "fechaCreacion" => $fecha,
                        "id_cliente"    => $id_cliente];
        $id_perfil = $mperfiles->insert($datosPerfil);

        $rutaFoto = "/public/usuarios/".$_FILES["rutaFoto"]["name"];
        $ruta2 = "/var/www/html/colibri/public/usuarios/".$_FILES["rutaFoto"]["name"];
        //$ruta2 = $_SERVER["DOCUMENT_ROOT"]."/public/usuarios/".$_FILES["rutaFoto"]["name"];        
        move_uploaded_file($_FILES["rutaFoto"]["tmp_name"], $ruta2);


        /*** Creacion del usuario ***/
        $datosUsuario = ["nombres"       => $solicitud->getVar("nombres"),
                         "apellidos"     => $solicitud->getVar("apellidos"),
                         "edad"          => $solicitud->getVar("edad"),
                         "dni"           => $solicitud->getVar("dni"),
                         "sexo"          => $solicitud->getVar("sexo"),
                         "rutaFoto"      => $rutaFoto,
                         "correo"        => $solicitud->getVar("correo"),
                         "contra"        => $solicitud->getVar("contra"),
                         "direccion"     => $solicitud->getVar("direccion"),
                         "comentario"    => $solicitud->getVar("comentario"),
                         "fechaCreacion" => $fecha,
                         "id_perfil"     => $id_perfil,
                         "id_cliente"    => $id_cliente];
        
        $musuarios = new ModeloUsuarios();
        $idusuario = $musuarios->insert($datosUsuario);

        /*** Creacion de los modulos ***/
        $data = []; // Guarda los id's de los modulos para la tabla permisos

        $mmodulos = new ModeloModulos();

        // Modulo seguridad
        $seguridad = ["modulo"        => "Seguridad",
                      "fechaCreacion" => $fecha,
                      "url"           => "Views/seguridad",
                      "id_cliente"    => $id_cliente];
        $idseguridad = $mmodulos->insert($seguridad);
        array_push($data, $idseguridad);
        
        // Modulo mantenimiento
        $mantenimiento = ["modulo"        => "Mantenimiento",
                          "fechaCreacion" => $fecha,
                          "url"           => "Views/mantenimiento",
                          "id_cliente"    => $id_cliente];
        $idmantenimiento = $mmodulos->insert($mantenimiento);
        array_push($data, $idmantenimiento);

        // Modulo Pagos
        $pagos = ["modulo"        => "Pagos",
                  "fechaCreacion" => $fecha,
                  "url"           => "Views/pagos",
                  "id_cliente"    => $id_cliente];
        $idpago = $mmodulos->insert($pagos);
        array_push($data, $idpago);

        // Modulo Matriculas
        $matriculas = ["modulo"        => "Matriculas",
                       "fechaCreacion" => $fecha,
                       "url"           => "Views/matriculas",
                       "id_cliente"    => $id_cliente];
        $idmatricula = $mmodulos->insert($matriculas);
        array_push($data, $idmatricula);

        // Modulo Reportes
        $reportes = ["modulo"        => "Reportes",
                     "fechaCreacion" => $fecha,
                     "url"           => "Views/reportes",
                     "id_cliente"    => $id_cliente];
        $idreporte = $mmodulos->insert($reportes);
        array_push($data, $idreporte);

        /* Submodulos hijos del modulo seguridad */

        // Modulo Usuarios
        $usuarios = ["modulo"         => "Usuarios",
                     "fechaCreacion"  => $fecha,
                     "url"            => "usuarios/listar",
                     "id_moduloPadre" => $idseguridad,
                     "id_cliente"     => $id_cliente];
        $idusuario = $mmodulos->insert($usuarios);
        array_push($data, $idusuario);

        // Modulo Perfiles
        $perfiles = ["modulo"         => "Perfiles",
                     "fechaCreacion"  => $fecha,
                     "url"            => "perfiles/listar",
                     "id_moduloPadre" => $idseguridad,
                     "id_cliente"     => $id_cliente];
        $idperfil = $mmodulos->insert($perfiles);
        array_push($data, $idperfil);

        // Modulo Sesiones
        $sesiones = ["modulo"         => "Sesiones",
                     "fechaCreacion"  => $fecha,
                     "url"            => "sesiones/listar",
                     "id_moduloPadre" => $idseguridad,
                     "id_cliente"     => $id_cliente];
        $idsesion = $mmodulos->insert($sesiones);
        array_push($data, $idsesion);

        /* Submodulos hijos del modulo mantenimiento*/

        // Modulo Alumnos
        $alumnos = ["modulo"         => "Alumnos",
                    "fechaCreacion"  => $fecha,
                    "url"            => "alumnos/listar",
                    "id_moduloPadre" => $idmantenimiento,
                    "id_cliente"     => $id_cliente];
        $idalumno = $mmodulos->insert($alumnos);
        array_push($data, $idalumno);        

        // Modulo Profesores
        $profesores = ["modulo"         => "Profesores",
                       "fechaCreacion"  => $fecha,
                       "url"            => "profesores/listar",
                       "id_moduloPadre" => $idmantenimiento,
                       "id_cliente"     => $id_cliente];
        $idprofesor = $mmodulos->insert($profesores);
        array_push($data, $idprofesor);

        // Modulo Cursos
        $cursos = ["modulo"         => "Cursos",
                   "fechaCreacion"  => $fecha,
                   "url"            => "cursos/listar",
                   "id_moduloPadre" => $idmantenimiento,
                   "id_cliente"     => $id_cliente];
        $idcurso = $mmodulos->insert($cursos);
        array_push($data, $idcurso);

        // Modulo Grados
        $grados = ["modulo"         => "Grados",
                   "fechaCreacion"  => $fecha,
                   "url"            => "grados/listar",
                   "id_moduloPadre" => $idmantenimiento,
                   "id_cliente"     => $id_cliente];
        $idgrado = $mmodulos->insert($grados);
        array_push($data, $idgrado);

        // Modulo Secciones
        $secciones = ["modulo"         => "Secciones",
                      "fechaCreacion"  => $fecha,
                      "url"            => "secciones/listar",
                      "id_moduloPadre" => $idmantenimiento,
                      "id_cliente"     => $id_cliente];
        $idseccion = $mmodulos->insert($secciones);
        array_push($data, $idseccion);
        
        // Modulo Sedes
        $sedes = ["modulo"         => "Sedes",
                  "fechaCreacion"  => $fecha,
                  "url"            => "sedes/listar",
                  "id_moduloPadre" => $idmantenimiento,
                  "id_cliente"     => $id_cliente];
        $idsede = $mmodulos->insert($sedes);
        array_push($data, $idsede);

        // Modulo Periodos
        $periodos = ["modulo"         => "Periodos",
                     "fechaCreacion"  => $fecha,
                     "url"            => "ciclos/listar",
                     "id_moduloPadre" => $idmantenimiento,
                     "id_cliente"     => $id_cliente];
        $idperiodo = $mmodulos->insert($periodos);
        array_push($data, $idperiodo);

        // Modulo Solicitudes
        $solicitudes = ["modulo"         => "Solicitudes",
                        "fechaCreacion"  => $fecha,
                        "url"            => "solicitudes/listar",
                        "id_moduloPadre" => $idmantenimiento,
                        "id_cliente"     => $id_cliente];
        $idsolicitud = $mmodulos->insert($solicitudes);
        array_push($data, $idsolicitud);


        /* Submodulos hijos del modulo Pagos */
        
        // Modulo Pagos de alumnos
        $palumnos = ["modulo"         => "Pagos de alumnos",
                     "fechaCreacion"  => $fecha,
                     "url"            => "pagos/listar_alumnos",
                     "id_moduloPadre" => $idpago,
                     "id_cliente"     => $id_cliente];
        $idpalumno = $mmodulos->insert($palumnos);
        array_push($data, $idpalumno);

        // Modulo Pagos a profesores
        $pprofesores = ["modulo"         => "Pagos a profesores",
                        "fechaCreacion"  => $fecha,
                        "url"            => "pagos/listar_profesores",
                        "id_moduloPadre" => $idpago,
                        "id_cliente"     => $id_cliente];
        $idpprofesor = $mmodulos->insert($pprofesores);
        array_push($data, $idpprofesor);

        // Modulo motivo de pago
        $motivos = ["modulo"         => "Motivo pago",
                    "fechaCreacion"  => $fecha,
                    "url"            => "motivoPago/listar_motivo",
                    "id_moduloPadre" => $idpago,
                    "id_cliente"     => $id_cliente];
        $idmotivo = $mmodulos->insert($motivos);
        array_push($data, $idmotivo);

        /* Submodulos hijos del modulo Matriculas */
        
        // Modulo matricula de alumno
        $malumnos = ["modulo"         => "Alumnos",
                     "fechaCreacion"  => $fecha,
                     "url"            => "alumnoPorCurso/listar",
                     "id_moduloPadre" => $idmatricula,
                     "id_cliente"     => $id_cliente];
        $idmalumno = $mmodulos->insert($malumnos);
        array_push($data, $idmalumno);

        // Modulo matricula de profesor
        $mprofesores = ["modulo"         => "Profesores",
                        "fechaCreacion"  => $fecha,
                        "url"            => "curSecPorProfesor/listar",
                        "id_moduloPadre" => $idmatricula,
                        "id_cliente"     => $id_cliente];
        $idmprofesor = $mmodulos->insert($mprofesores);
        array_push($data, $idmprofesor);


        /*** Fin de la creacion de los modulos ***/

        /*** Asignacion de permisos al perfil de administrador ***/

        $mpermisos = new ModeloPermisos();
        foreach ($data as $modulo)
        {
            $datosPermiso = ["id_modulo"     => $modulo,
                             "id_perfil"     => $id_perfil,
                             "id_cliente"    => $id_cliente,
                             "fechaCreacion" => $fecha];
            $mpermisos->insert($datosPermiso);
        }

        /*** Fin de los permisos ***/
        
        //echo "<script>alert('Cliente creado');window.location.href = '".base_url()."/index.php/admin';</script>";
        return json_encode(["Estado" => 200, "Detalles" => "Registro existoso, datos del cliente guardado", "id_cliente" => $id_cliente], true);
    }
    
    public function index()
    {
        $solicitud = \Config\Services::request();
        $validacion =\Config\Services::validation();
        $cabecera = $solicitud->getHeaders();
        $modeloRegistros = new ModeloRegistros();

        $registros = $modeloRegistros->where("estado", 1)->findAll();

        foreach ($registros as $clave => $valor)
        {
            if (!(array_key_exists("Authorization", $cabecera) && !empty($cabecera["Authorization"])))
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "No esta autorizado para guardar registros"], true);
                continue;
            }
            $autorizacion = "Authorization: Basic ".base64_encode($valor["cliente_id"].":".$valor["llave_secreta"]);
            if ($cabecera["Authorization"] != $autorizacion)
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "Token no valido"], true);
                continue;
            }
            $modeloClientes = new ModeloClientes();
            $clientes = $modeloClientes->traerClientes();
            if (empty($clientes))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $clientes]);
            return json_encode(["Estado" => 200, "Total" => count($clientes), "Detalles" => $clientes]);
        }
        return json_encode($error, true);
    }

    public function show($id)
    {
        $solicitud = \Config\Services::request();
        $validacion =\Config\Services::validation();
        $cabecera = $solicitud->getHeaders();
        $modeloRegistros = new ModeloRegistros();

        $registros = $modeloRegistros->where("estado", 1)->findAll();

        foreach ($registros as $clave => $valor)
        {
            if (!(array_key_exists("Authorization", $cabecera) && !empty($cabecera["Authorization"])))
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "No esta autorizado para guardar registros"], true);
                continue;
            }
            $autorizacion = "Authorization: Basic ".base64_encode($valor["cliente_id"].":".$valor["llave_secreta"]);
            if ($cabecera["Authorization"] != $autorizacion)
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "Token no valido"], true);
                continue;
            }
            $modeloClientes = new ModeloClientes();
            $cliente = $modeloClientes->traerPorId($id);
            if (empty($cliente))
            {
                return json_encode(["Estado" => 404, "Detalle" => "El cliente que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalle" => $cliente]);
        }
        return json_encode($error);
    }

    public function crear()
    {
        $solicitud = \Config\Services::request();
        $validacion = \Config\Services::validation();
        $cabecera = $solicitud->getHeaders(); // Para utilizar el token basico que hemos creado
        $modeloRegistros = new ModeloRegistros();

        $registros = $modeloRegistros->where("estado", 1)->findAll();
        foreach ($registros as $clave => $valor) // Recorremos la tabla registros
        {
            // Verificamos si tiene autorizacion
            if (!(array_key_exists("Authorization", $cabecera) && !empty($cabecera["Authorization"])))
            {
                $error =  json_encode(["Estado" => 404, "Detalles" => "No esta autorizado para guardar registros"], true);
                continue;
            }

            $autorizacion = "Authorization: Basic ".base64_encode($valor["cliente_id"].":".$valor["llave_secreta"]);

            if ($cabecera["Authorization"] != $autorizacion)
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "Token no valido"], true);
                continue;
            }

            // Tomamos los datos de HTTP
            $datos = ["cliente"       => $solicitud->getVar("cliente"),
                      "ruc"           => $solicitud->getVar("ruc"),
                      "nombreEncar"   => $solicitud->getVar("nombreEncar"),
                      "apellidoEncar" => $solicitud->getVar("apellidoEncar"),
                      "fechaContrato" => $solicitud->getVar("fechaContrato"),
                      "correoCliente" => $solicitud->getVar("correoCliente"),
                      "url"           => $solicitud->getVar("url"),
                      "foto"          => $ruta];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloClientes = new ModeloClientes();
            $validacion->setRules($modeloClientes->validationRules, $modeloClientes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloClientes->insert($datos);
            $data = ["Estado" => 200, "Detalle" => "Registro exitoso, datos del cliente guardado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }

    public function update($id)
    {
        $solicitud = \Config\Services::request();
        $validacion = \Config\Services::validation();
        $cabecera = $solicitud->getHeaders(); // Para utilizar el token basico que hemos creado
        $modeloRegistros = new ModeloRegistros();

        $registros = $modeloRegistros->where("estado", 1)->findAll();
        foreach ($registros as $clave => $valor) // Recorremos la tabla registros
        {
            // Verificamos si tiene autorizacion
            if (!(array_key_exists("Authorization", $cabecera) && !empty($cabecera["Authorization"])))
            {
                $error =  json_encode(["Estado" => 404, "Detalles" => "No esta autorizado para guardar registros"], true);
                continue;
            }

            $autorizacion = "Authorization: Basic ".base64_encode($valor["cliente_id"].":".$valor["llave_secreta"]);

            if ($cabecera["Authorization"] != $autorizacion)
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "Token no valido"], true);
                continue;
            }

            // Tomamos los datos de HTTP
            $datos = $solicitud->getRawInput();
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloClientes = new ModeloClientes();
            $validacion->setRules($modeloClientes->validationRules, $modeloClientes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $cliente = $modeloClientes->where("estado", 1)->find($id);
            if (empty($cliente))
                return json_encode(["Estado" => 200, "Detalle" => "No existe el cliente"], true);
            $modeloClientes->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del cliente actualizado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }

    public function delete($id)
    {
        $solicitud = \Config\Services::request();
        $validacion = \Config\Services::validation();
        $cabecera = $solicitud->getHeaders(); // Para utilizar el token basico que hemos creado
        $modeloRegistros = new ModeloRegistros();

        $registros = $modeloRegistros->where("estado", 1)->findAll();
        foreach ($registros as $clave => $valor) // Recorremos la tabla registros
        {
            // Verificamos si tiene autorizacion
            if (!(array_key_exists("Authorization", $cabecera) && !empty($cabecera["Authorization"])))
            {
                $error =  json_encode(["Estado" => 404, "Detalles" => "No esta autorizado para guardar registros"], true);
                continue;
            }

            $autorizacion = "Authorization: Basic ".base64_encode($valor["cliente_id"].":".$valor["llave_secreta"]);

            if ($cabecera["Authorization"] != $autorizacion)
            {
                $error = json_encode(["Estado" => 404, "Detalles" => "Token no valido"], true);
                continue;
            }
            // Configuramos las reglas de validacion
            $modeloClientes = new ModeloClientes();
            $cliente = $modeloClientes->where("estado", 1)->find($id);
            if (empty($cliente))
                return json_encode(["Estado" => 200, "Detalle" => "No existe el cliente"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la ba[e de datos
            $modeloClientes->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del cliente eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
