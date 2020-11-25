<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloSolicitudes;
use App\Models\ModeloClientes;

class Solicitud extends Controller
{
    
    public function index()
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
            $ModeloSolicitudes = new ModeloSolicitudes();
            $solicitudes = $ModeloSolicitudes->traerSolicitudes($_SERVER["HTTP_CLIENTE"]);
            if (empty($solicitudes))
            {
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $solicitudes]);
            }
            return json_encode(["Estado" => 200, "Total" => count($solicitudes), "Detalles" => $solicitudes]);
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
            $modeloSolicitudes = new ModeloSolicitudes();
            $solicitudes = $modeloSolicitudes->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($solicitudes))
            {
                return json_encode(["Estado" => 404, "Detalles" => "La solicitud que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $solicitudes]);
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
            $datos = ["nombrePadre"  => $solicitud->getVar("nombrePadre"),
                      "correoPadre"  => $solicitud->getVar("correoPadre"),
                      "telefono" 	 => $solicitud->getVar("telefono"),
                      "nombreHijo"   => $solicitud->getVar("nombreHijo"),
                      "nivel"        => $solicitud->getVar("nivel"),
                      "id_cliente"   => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloSolicitudes = new ModeloSolicitudes();
            $validacion->setRules($modeloSolicitudes->validationRules, $modeloSolicitudes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);

            $solicitudes = $modeloSolicitudes->where(["estado"     => 1,
                                           "id_cliente" => $datos["id_cliente"],
                                           "solicitudes"=> $datos["nombrePadre"]])->findAll();

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloSolicitudes->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos de la solicitud guardado"];
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
            $modeloSolicitudes = new ModeloSolicitudes();
            $validacion->setRules($modeloSolicitudes->validationRules, $modeloSolicitudes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $solicitud = $modeloSolicitudes->where("estado", 1)->find($id);
            if (empty($solicitud))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la solicitud"], true);
            // Validamos si los datos son correctos
            
            $modeloSolicitudes->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la solicitud actualizado"];
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
            $modeloSolicitudes = new ModeloSolicitudes();
            $solicitud = $modeloSolicitudes->where("estado", 1)->find($id);
            if (empty($solicitud))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la solicitud"], true);
            $datos = ["estado"    => 0];
            // Insertamos los datos a la base de datos
            $modeloSolicitudes->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la solicitud eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}