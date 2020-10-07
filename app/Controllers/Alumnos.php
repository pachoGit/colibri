<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloAlumnos;

class Alumnos extends Controller
{
    // Funciona
    public function listar()
    {
        return view("alumnos/listar");
    }

    public function registrar()
    {
        return view("alumnos/registrar");
    }
    // Funciona 
    public function ver($id)
    {
        $data = ["id" => $id];
        return view("alumnos/ver", $data);
        
    }

    public function editar($id)
    {
        $data = ["id" => $id];
        return view("alumnos/editar", $data);
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("alumnos/eliminar", $data);
    }

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
            $modeloAlumnos = new ModeloAlumnos();
            $alumnos = $modeloAlumnos->traerAlumnos($cliente);
            if (empty($alumnos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $alumnos]);
            return json_encode(["Estado" => 200, "Total" => count($alumnos), "Detalles" => $alumnos]);
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
            $modeloAlumnos = new ModeloAlumnos();
            $alumno = $modeloAlumnos->traerPorId($id, $cliente);
            if (empty($alumno))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El alumno que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $alumno]);
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

            $datos = ["nombres"     => $solicitud->getVar("nombres"),
                      "apellidos"   => $solicitud->getVar("apellidos"),
                      "dni"         => $solicitud->getVar("dni"),
                      "sexo"        => $solicitud->getVar("sexo"),
                      "rutaFoto"    => $solicitud->getVar("rutaFoto"),
                      "direccion"   => $solicitud->getVar("direccion"),
                      "correo"      => $solicitud->getVar("correo"),
                      "edad"        => $solicitud->getVar("edad"),
                      "nombrePadre" => $solicitud->getVar("nombrePadre"),
                      "nombreMadre" => $solicitud->getVar("nombreMadre"),
                      "comentario"  => $solicitud->getVar("comentario"),
                      "id_cliente"  => $cliente];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloAlumnos = new ModeloAlumnos();
            $validacion->setRules($modeloAlumnos->validationRules, $modeloAlumnos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloAlumnos->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del alumno guardado"];
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
            $modeloAlumnos = new ModeloAlumnos();
            $validacion->setRules($modeloAlumnos->validationRules, $modeloAlumnos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $alumno = $modeloAlumnos->where("estado", 1)->find($id);
            if (empty($alumno))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el alumno"], true);
            $modeloAlumnos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del alumno actualizado"];
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
            $modeloAlumnos = new ModeloAlumnos();
            $alumno = $modeloAlumnos->where("estado", 1)->find($id);
            if (empty($alumno))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el alumno"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloAlumnos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del alumno eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
