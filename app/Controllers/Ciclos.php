<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloCiclos;

class Ciclos extends Controller
{
     public function listar()
    {
        return view("ciclos/listar");
    }

    public function registrar()
    {
        return view("ciclos/registrar");
    }
    // Funciona 

    public function editar($id)
    {
        $data = ["id" => $id];
        return view("ciclos/editar", $data);
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("ciclos/eliminar", $data);
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
            $modeloCiclos = new ModeloCiclos();
            $ciclos = $modeloCiclos->traerCiclos($_SERVER["HTTP_CLIENTE"]);
            if (empty($ciclos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $ciclos]);
            return json_encode(["Estado" => 200, "Total" => count($ciclos), "Detalles" => $ciclos]);
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
            $modeloCiclos = new ModeloCiclos();
            $ciclo = $modeloCiclos->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($ciclo))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El ciclo que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $ciclo]);
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
            $datos = ["ciclo"      => $solicitud->getVar("ciclo"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloCiclos = new ModeloCiclos();
            $validacion->setRules($modeloCiclos->validationRules, $modeloCiclos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $ciclo = $modeloCiclos->where(["estado"     => 1,
                                           "id_cliente" => $datos["id_cliente"],
                                           "ciclo"      => $datos["ciclo"]])->findAll();
            if (!empty($ciclo))
                return json_encode(["Estado" => 404, "Detalles" => "Este ciclo ya existe"]);
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloCiclos->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del ciclo guardado"];
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
            $modeloCiclos = new ModeloCiclos();
            $validacion->setRules($modeloCiclos->validationRules, $modeloCiclos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $ciclo = $modeloCiclos->where("estado", 1)->find($id);
            if (empty($ciclo))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el ciclo"], true);
            $modeloCiclos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del ciclo actualizado"];
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

            $modeloCiclos = new ModeloCiclos();
            $ciclo = $modeloCiclos->where("estado", 1)->find($id);
            if (empty($ciclo))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el ciclo"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la ba[e de datos
            $modeloCiclos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del ciclo eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
