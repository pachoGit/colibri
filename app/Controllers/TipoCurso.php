<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloTipoCurso;
use App\Models\ModeloCursos;

class TipoCurso extends Controller
{
    public function crear()
    {
        $solicitud = \Config\Services::request();

        $data = ["tipo" => $solicitud->getVar("tipo")];
        return view("tipoCurso/registrar", $data);
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
            $modeloCursos = new ModeloCursos();
            $tipos = $modeloCursos->traerTiposCurso($_SERVER["HTTP_CLIENTE"]);
            if (empty($tipos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $tipos]);
            return json_encode(["Estado" => 200, "Total" => count($tipos), "Detalles" => $tipos]);
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
            $modeloCursos = new ModeloCursos();
            $tipo = $modeloCursos->traerTipoPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($tipo))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El tipo del curso que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $tipo]);
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
            $datos = ["tipo"       => $solicitud->getVar("tipo"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloTipoCurso = new ModeloTipoCurso();
            $validacion->setRules($modeloTipoCurso->validationRules, $modeloTipoCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $tipo = $modeloTipoCurso->where(["estado"     => 1,
                                             "id_cliente" => $datos["id_cliente"],
                                             "tipo"       => $datos["tipo"]])->findAll();
            if (!empty($tipo))
                return json_encode(["Estado" => 404, "Detalles" => "Este tipo ya existe"]);
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la base de datos
            $modeloTipoCurso->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del tipo guardado"];
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
            $modeloTipoCurso = new ModeloTipoCurso();
            $validacion->setRules($modeloTipoCurso->validationRules, $modeloTipoCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $tipo = $modeloTipoCurso->where("estado", 1)->find($id);
            if (empty($tipo))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el tipo"], true);
            $modeloTipoCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la tipo actualizado"];
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
            $modeloTipoCurso = new ModeloTipoCurso();
            $tipo = $modeloTipoCurso->where("estado", 1)->find($id);
            if (empty($tipo))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el tipo"], true);
            $datos = ["fechaElim" => date("Y-m-d"),
                      "estado"    => 0];
            // Insertamos los datos a la base de datos
            $modeloTipoCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del tipo eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
