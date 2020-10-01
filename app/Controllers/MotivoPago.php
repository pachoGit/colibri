<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloMotivoPago;

class MotivoPago extends Controller
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
            $modeloMotivoPago = new ModeloMotivoPago();
            $motivo = $modeloMotivoPago->traerMotivoPago($cliente);
            if (empty($motivo))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $motivo]);
            return json_encode(["Estado" => 200, "Total" => count($motivo), "Detalles" => $motivo]);
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
            $modeloMotivoPago = new ModeloMotivoPago();
            $motivo = $modeloMotivoPago->traerPorId($id, $cliente);
            if (empty($motivo))
            {
                return json_encode(["Estado" => 404, "Detalle" => "El motivo de pago que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalle" => $motivo]);
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
            $datos = ["motivo"    => $solicitud->getVar("motivo"),
                      "id_cliente" => $cliente];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloMotivoPago = new ModeloMotivoPago();
            $validacion->setRules($modeloMotivoPago->validationRules, $modeloMotivoPago->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            $motivo = $modeloMotivoPago->where(["estado"     => 1,
                                                "id_cliente" => $cliente,
                                                "motivo"      => $datos["motivo"]])->findAll();
            if (!empty($motivo))
                return json_encode(["Estado" => 404, "Detalle" => "Este motivo de pago ya existe"], true);
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloMotivoPago->insert($datos);
            $data = ["Estado" => 200, "Detalle" => "Registro exitoso, datos del motivo guardado"];
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
            $modeloMotivoPago = new ModeloMotivoPago();
            $validacion->setRules($modeloMotivoPago->validationRules, $modeloMotivoPago->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $motivo = $modeloMotivoPago->where("estado", 1)->find($id);
            if (empty($motivo))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el motivo de pago"], true);
            $modeloMotivoPago->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del motivo actualizado"];
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
            $modeloMotivoPago = new ModeloMotivoPago();
            $motivo = $modeloMotivoPago->where("estado", 1)->find($id);
            if (empty($motivo))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el motivo de pago"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la ba[e de datos
            $modeloMotivoPago->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del motivo eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
