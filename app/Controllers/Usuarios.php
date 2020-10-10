<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloUsuarios;
use App\Models\ModeloPerfiles;

class Usuarios extends BaseController
{
    // De esta funcion se le envia a la vista de listar
    public function listar()
    {
        return view("usuarios/listar");
    }

    public function registrar()
    {
        $m_perfiles = new ModeloPerfiles();
        $perfiles = $m_perfiles->traerPerfiles($_SESSION["id_cliente"]); // Reemplazar por la variable SESSION
        $data = ["perfiles" => $perfiles];
        return view("usuarios/registrar", $data);
    }

    public function ver($id)
    {
        $m_usuarios = new ModeloUsuarios();
        $usuario = $m_usuarios->traerPorId($id, $_SESSION["id_cliente"]);
        $perfil = $usuario[0]["perfil"];
        $data = ["perfil" => $perfil,
                 "id"     => $id];
        return view("usuarios/ver", $data);
    }

    public function editar($id)
    {
        $m_usuarios = new ModeloUsuarios();
        $m_perfiles = new ModeloPerfiles();

        $perfiles = $m_perfiles->traerPerfiles($_SESSION["id_cliente"]); // SESSION
        $usuario = $m_usuarios->traerPorId($id, $_SESSION["id_cliente"]); // El SESSION
        $mi_perfil = $usuario[0]["perfil"];
        $data = ["mi_perfil" => $mi_perfil,
                 "id"        => $id,
                 "perfiles"  => $perfiles];
        return view("usuarios/editar", $data);
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("usuarios/eliminar", $data);
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
            $modeloUsuarios = new ModeloUsuarios();
            $usuarios = $modeloUsuarios->traerUsuarios($_SERVER["HTTP_CLIENTE"]);

            if (empty($usuarios))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $usuarios], true);
            return json_encode(["Estado" => 200, "Total" => count($usuarios), "Detalles" => $usuarios], true);
        }
        return json_encode($error, true);
    }

    public function show($id)
    {
        $solictud = \Config\Services::request();
        $validacion =\Config\Services::validation();
        $cabecera = $solictud->getHeaders();
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
            $modeloUsuarios = new ModeloUsuarios();
            $usuario = $modeloUsuarios->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($usuario))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El usuario que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $usuario]);
        }
        return json_encode($error);
    }

    public function create()
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

            $datos = ["nombres"     => $solicitud->getVar("nombres"),
                      "apellidos"   => $solicitud->getVar("apellidos"),
                      "dni"         => $solicitud->getVar("dni"),
                      "sexo"        => $solicitud->getVar("sexo"),
                      "rutaFoto"    => $solicitud->getVar("rutaFoto"),
                      "direccion"   => $solicitud->getVar("direccion"),
                      "correo"      => $solicitud->getVar("correo"),
                      "contra"      => $solicitud->getVar("contra"),                      
                      "edad"        => $solicitud->getVar("edad"),
                      "id_perfil"   => $solicitud->getVar("id_perfil"),
                      "comentario"  => $solicitud->getVar("comentario"),
                      "id_cliente"  => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloUsuarios = new ModeloUsuarios();
            $validacion->setRules($modeloUsuarios->validationRules, $modeloUsuarios->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $modeloPerfiles = new ModeloPerfiles();

            $perfil = $modeloPerfiles->where("estado", 1)->find($datos["id_perfil"]);
            if (empty($perfil))
                return json_encode(["Estado" => 404, "Detalles" => "No existe ese perfil"], true);

            // El correo se lee en todos lados, no es independiente del cliente
            $usuario = $modeloUsuarios->where(["estado" => 1, "correo" => $datos["correo"]])->findAll();
            if (!empty($usuario))
                return json_encode(["Estado" => 404, "Detalles" => "Ya existe este correo"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloUsuarios->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del usuario guardado"];
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
            $modeloUsuarios = new ModeloUsuarios();
            $validacion->setRules($modeloUsuarios->validationRules, $modeloUsuarios->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $modeloPerfiles = new ModeloPerfiles();

            $perfil = $modeloPerfiles->where("estado", 1)->find($datos["id_perfil"]);
            if (empty($perfil))
                return json_encode(["Estado" => 404, "Detalles" => "No existe ese perfil"], true);

            // Insertamos los datos a la base de datos
            $usuario = $modeloUsuarios->where("estado", 1)->find($id);
            if (empty($usuario))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el usuario"], true);

            $usuario = $modeloUsuarios->where("correo", $datos["correo"])->findAll();
            if (!empty($usuario) and ($usuario[0]["idUsuario"] != $id))
                return json_encode(["Estado" => 404, "Detalles" => "Ya existe este correo"], true);

            $modeloUsuarios->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del usuario actualizado"];
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
            $modeloUsuarios = new ModeloUsuarios();
            $usuario = $modeloUsuarios->where("estado", 1)->find($id);
            if (empty($usuario))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el usuario"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloUsuarios->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del usuario eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }

    // Funcion usuada para el inicio de sesion
    public function traerUsuario($correo, $contra)
    {
        $modelo = new ModeloUsuarios();
        $usuario = $modelo->where(["estado" => 1,
                                   "correo" => $correo,
                                   "contra" => $contra])->findAll();
        return (empty($usuario) ? null : $usuario);
    }
}
