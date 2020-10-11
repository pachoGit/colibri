<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloSedes;

class Sedes extends Controller
{
     public function listar()
    {
        return view("sedes/listar");
    }

    public function registrar()
    {
        return view("sedes/registrar");
    }
    // Funciona 

    public function editar($id)
    {
        $data = ["id" => $id];
        return view("sedes/editar", $data);
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("sedes/eliminar", $data);
    }

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
            $modeloSedes= new ModeloSedes();
            $sedes = $modeloSedes->traerSedes($_SERVER["HTTP_CLIENTE"]);
            if (empty($sedes))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $sedes]);
            return json_encode(["Estado" => 200, "Total" => count($sedes), "Detalles" => $sedes]);
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
            $modeloSedes = new ModeloSedes();
            $sede = $modeloSedes->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($sede))
            {
                return json_encode(["Estado" => 404, "Detalles" => "La sede que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $sede]);
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
            $datos = ["sede"      => $solicitud->getVar("sede"),
            			"direccion"=> $solicitud->getVar("direccion"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloSedes = new ModeloSedes();
            $validacion->setRules($modeloSedes->validationRules, $modeloSedes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $sede = $modeloSedes->where(["estado"     => 1,
                                           "id_cliente" => $datos["id_cliente"],
                                           "sede"      => $datos["sede"]])->findAll();
            if (!empty($sede))
                return json_encode(["Estado" => 404, "Detalles" => "Esta sede ya existe"], true);
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloSedes->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos de la sede guardado"];
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
            $modeloSedes = new ModeloSedes();
            $validacion->setRules($modeloSedes->validationRules, $modeloSedes->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $sede = $modeloSedes->where("estado", 1)->find($id);
            if (empty($sede))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la sede"], true);
            $modeloSedes->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la sede actualizado"];
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
            $modeloSedes= new ModeloSedes();
            $sede = $modeloSedes->where("estado", 1)->find($id);
            if (empty($sede))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la sede"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la ba[e de datos
            $modeloSedes->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la sede eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}

