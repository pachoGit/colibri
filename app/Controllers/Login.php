<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloUsuarios;

class Login extends Controller
{
    // Esta funcion recoge el usuario y contrasena
    public function create()
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
            $usuario = $modeloUsuarios->where(["estado"     => 1,
                                               "correo"     => $solicitud->getVar("correo"),
                                               "contra"     => $solicitud->getVar("contra"),
                                               "id_cliente" => $solicitud->getVar("id_cliente")])->findAll();
            if (empty($usuario))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $usuario], true);
            return json_encode(["Estado" => 200, "Detalles" => $usuario], true);
        }
        return json_encode($error, true);

    }
}
