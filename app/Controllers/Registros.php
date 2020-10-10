<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;

class Registros extends Controller
{
    public function index()
    {
        $modelo = new ModeloRegistros();
        $registros = $modelo->where("estado", 1)->findAll();
        if (count($registros) == 0)
        {
            echo json_encode(["Error" => true, "Mensaje" => "No hay elementos"]);
            return;
        }
        echo json_encode($registros);
    }

    public function create()
    {
        // Crear los tokens de autorizacion basica para el CRUD de todas las tablas
        $solicitud = \Config\Services::request(); // Creamos un servicio de solicitud - request
        $validacion = \Config\Services::validation(); // Creamos un servicio de validacion
        // Puede ser $solicitud = service("request");
        // Puede ser $validacion = service("validation");
        $datos = ["nombres"   => $solicitud->getVar("nombres"),
                  "apellidos" => $solicitud->getVar("apellidos"),
                  "email"     => $solicitud->getVar("email")];
        // Validar datos
        if (empty($datos))
        {
            $data = ["Estado" => 404, "Detalle" => "Registro con errores"];
            return json_encode($data, true);
        }
        $modelo = new ModeloRegistros();
        $validacion->setRules($modelo->validationRules, $modelo->validationMessages);
        // Establecer los datos que llegan de 'solicitud' para validarlos
        $validacion->withRequest($this->request)->run();
        // Si la validacion genera errores
        if (($errores = $validacion->getErrors()))
        {
            $data = ["Estado" => 404, "Detalles" => $errores];
            return json_encode($data, true);
        }
        //$data_encriptar = $datos["nombres"].$datos["apellidos"].$datos["email"];
        $llave_cifr = '$2a$07$dfhdfrexfhgdfhdferttgsad$';
        // Formamos los credenciales
        //$llave_cifr = "2a07"; // Llave para cifrar
        $cliente_id = crypt($datos["nombres"].$datos["apellidos"].$datos["email"], $llave_cifr);
        $llave_secreta = crypt($datos["email"].$datos["apellidos"].$datos["nombres"], $llave_cifr);

        $datos = ["nombres" => $datos["nombres"],
                  "apellidos" => $datos["apellidos"],
                  "email" => $datos["email"],
                  "fechaCreacion" => date("Y-m-d"),
                  "cliente_id" => str_replace("$", "a", $cliente_id),
                  "llave_secreta" => str_replace("$", "o", $llave_secreta)];


        $registro = $modelo->insert($datos);
        // Mensaje de registro
        $data = ["Estado"       => 200,
                 "Detalles"      => "Registro exitoso, tome sus credenciales y guardeselas",
                 "credenciales" => ["cliente_id"    => str_replace("$", "a", $cliente_id),
                                    "llave_secreta" => str_replace("$", "o", $llave_secreta)]];
        return json_encode($data, true);
    }
    
}
