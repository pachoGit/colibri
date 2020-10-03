<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloProfesores;

class Profesores extends Controller
{
    public function listar()
    {
        return view("profesores/listar");
    }

    public function registrar()
    {
        return view("profesores/registrar");
        return redirect()->to(base_url()."/index.php/profesores/listar");
    }

    public function ver($id)
    {
        $data = ["id" => $id];
        return view("profesores/ver", $data);
    }

    public function editar($id)
    {
        $data = ["id" => $id];
        return view("profesores/editar", $data);
        //return redirect()->to(base_url()."/index.php/profesores/listar");
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("profesores/eliminar", $data);
        return redirect()->to(base_url()."/index.php/profesores/listar");
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
            $modeloProfesores = new ModeloProfesores();
            $profesores = $modeloProfesores->traerProfesores($cliente);
            if (empty($profesores))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $profesores]);
            return json_encode(["Estado" => 200, "Total" => count($profesores), "Detalles" => $profesores]);
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
            $modeloProfesores = new ModeloProfesores();
            $profesor = $modeloProfesores->traerPorId($id, $cliente);
            if (empty($profesor))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El profesor que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $profesor]);
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
                      "estudios"    => $solicitud->getVar("estudios"),
                      "comentario"  => $solicitud->getVar("comentario"),
                      "id_cliente"  => $cliente];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }

            
            // Configuramos las reglas de validacion
            $modeloProfesores = new ModeloProfesores();
            $validacion->setRules($modeloProfesores->validationRules, $modeloProfesores->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloProfesores->insert($datos);
            $data = ["Estado" => 200, "Detalle" => "Registro exitoso, datos del profesor guardado"];
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
            $modeloProfesores = new ModeloProfesores();
            $validacion->setRules($modeloProfesores->validationRules, $modeloProfesores->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datosn
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            // Insertamos los datos a la ba[e de datos
            $profesor = $modeloProfesores->where("estado", 1)->find($id);
            if (empty($profesor))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el profesor"], true);
            $modeloProfesores->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del profesor actualizado"];
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
            $modeloProfesores = new ModeloProfesores();
            // Insertamos los datos a la base de datos
            $profesor = $modeloProfesores->where("estado", 1)->find($id);
            if (empty($profesor))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el profesor"], true);
            $datos = ["estado" => 0, "fechaElim" => date("Y-m-d")];
            $modeloProfesores->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del profesor eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
