<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloPermisos;
use App\Models\ModeloClientes;
use App\Models\ModeloModulos;
use App\Models\ModeloPerfiles;

class Permisos extends Controller
{
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
            $modeloPermisos = new ModeloPermisos();
            $permisos = $modeloPermisos->traerPermisos($_SERVER["HTT_CLIENTE"]);
            if (empty($permisos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $permisos]);
            return json_encode(["Estado" => 200, "Total" => count($permisos), "Detalles" => $permisos]);
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
            $modeloPermisos = new ModeloPermisos();
            $permiso = $modeloPermisos->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($permiso))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El permiso que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $permiso]);
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

            // Tomamos los datos de HTTP
            $datos = ["id_perfil"  => $solicitud->getVar("id_perfil"),
                      "id_modulo"  => $solicitud->getVar("id_modulo"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloPermisos = new ModeloPermisos();
            $validacion->setRules($modeloPermisos->validationRules, $modeloPermisos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloPerfiles = new ModeloPerfiles();
            $modeloModulos = new ModeloModulos();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            $correcto = $modeloPerfiles->traerPorId($datos["id_perfil"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el perfil"]);
            $correcto = $modeloModulos->traerPorId($datos["id_modulo"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el modulo"]);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloPermisos->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del permiso guardado"];
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
            $modeloPermisos = new ModeloPermisos();
            $validacion->setRules($modeloPermisos->validationRules, $modeloPermisos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $permiso = $modeloPermisos->where("estado", 1)->find($id);
            if (empty($permiso))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el permiso"], true);

            $modeloPerfiles = new ModeloPerfiles();
            $modeloModulos = new ModeloModulos();
            
            $correcto = $modeloPerfiles->traerPorId($datos["id_perfil"], $permiso["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el perfil"]);
            $correcto = $modeloModulos->traerPorId($datos["id_modulo"], $permiso["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el modulo"]);
            $modeloPermisos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del permiso actualizado"];
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
            $modeloPermisos = new ModeloPermisos();
            $permiso = $modeloPermisos->where("estado", 1)->find($id);
            if (empty($permiso))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el permiso"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloPermisos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del permiso eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }

    public function verDePerfil($id)
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
            $modeloPermisos = new ModeloPermisos();
            $permisos = $modeloPermisos->traerDePerfil($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($permisos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Este perfil no tiene permisos"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $permisos]);
        }
        return json_encode($error);
    }
}

