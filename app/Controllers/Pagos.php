<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloAlumnos;
use App\Models\ModeloPagos;

class Pagos extends Controller
{
    public function index()
    {
        $cliente = 1;
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
            $modeloPagos = new ModeloPagos();
            $pagos = $modeloPagos->traerPagos($cliente);
            if (empty($pagos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $pagos]);
            return json_encode(["Estado" => 200, "Total" => count($pagos), "Detalles" => $pagos]);
        }
        return json_encode($error, true);
    }

    public function show($id)
    {
        $cliente = 1;
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
            $modeloPagos = new ModeloPagos();
            $pago = $modeloPagos->traerPorId($id, $cliente);
            if (empty($pago))
            {
                return json_encode(["Estado" => 404, "Detalle" => "El pago que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalle" => $pago]);
        }
        return json_encode($error);
    }

    public function create()
    {
        $cliente = 1;
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
                      "id_cliente" => $cliente];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloPagos = new ModeloPagos();
            $validacion->setRules($modeloPagos->validationRules, $modeloPagos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloPerfiles = new ModeloPerfiles();
            $modeloModulos = new ModeloModulos();
            $correcto = $modeloClientes->traerPorId($cliente);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            $correcto = $modeloPerfiles->traerPorId($datos["id_perfil"], $cliente);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el perfil"]);
            $correcto = $modeloModulos->traerPorId($datos["id_modulo"], $cliente);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el modulo"]);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloPagos->insert($datos);
            $data = ["Estado" => 200, "Detalle" => "Registro exitoso, datos del pago guardado"];
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
            $modeloPagos = new ModeloPagos();
            $validacion->setRules($modeloPagos->validationRules, $modeloPagos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $pago = $modeloPagos->where("estado", 1)->find($id);
            if (empty($pago))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el pago"], true);

            $modeloPerfiles = new ModeloPerfiles();
            $modeloModulos = new ModeloModulos();
            
            $correcto = $modeloPerfiles->traerPorId($datos["id_perfil"], $pago["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el perfil"]);
            $correcto = $modeloModulos->traerPorId($datos["id_modulo"], $pago["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el modulo"]);
            $modeloPagos->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del pago actualizado"];
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
            $modeloPagos = new ModeloPagos();
            $pago = $modeloPagos->where("estado", 1)->find($id);
            if (empty($pago))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el pago"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloPagos->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del pago eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}

