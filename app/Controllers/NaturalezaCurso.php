<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloNaturalezaCurso;
use App\Models\ModeloCursos;

class NaturalezaCurso extends Controller
{
    public function crear()
    {
        $solicitud = \Config\Services::request();

        $data = ["naturaleza" => $solicitud->getVar("naturaleza")];
        return view("naturalezaCurso/registrar", $data);
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
            $modeloCursos = new ModeloCursos();
            $naturalezas = $modeloCursos->traerNaturalezasCurso($cliente);
            if (empty($naturalezas))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $naturalezas]);
            return json_encode(["Estado" => 200, "Total" => count($naturalezas), "Detalles" => $naturalezas]);
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
            $modeloCursos = new ModeloCursos();
            $naturaleza = $modeloCursos->traerNaturalezaPorId($id, $cliente);
            if (empty($naturaleza))
            {
                return json_encode(["Estado" => 404, "Detalles" => "La naturaleza del curso que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $naturaleza]);
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
            $datos = ["naturaleza" => $solicitud->getVar("naturaleza"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloNaturalezaCurso = new ModeloNaturalezaCurso();
            $validacion->setRules($modeloNaturalezaCurso->validationRules, $modeloNaturalezaCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $naturaleza = $modeloNaturalezaCurso->where(["estado"     => 1,
                                           "id_cliente" => $datos["id_cliente"],
                                           "naturaleza" => $datos["naturaleza"]])->findAll();
            if (!empty($naturaleza))
                return json_encode(["Estado" => 404, "Detalles" => "Esta naturaleza ya existe"], true);                
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la base de datos
            $modeloNaturalezaCurso->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos de la naturaleza guardado"];
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
            $modeloNaturalezaCurso = new ModeloNaturalezaCurso();
            $validacion->setRules($modeloNaturalezaCurso->validationRules, $modeloNaturalezaCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $naturaleza = $modeloNaturalezaCurso->where("estado", 1)->find($id);
            if (empty($naturaleza))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la naturaleza"], true);
            $modeloNaturalezaCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la naturaleza actualizado"];
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
            $modeloNaturalezaCurso = new ModeloNaturalezaCurso();
            $datos = ["fechaElim" => date("Y-m-d"),
                      "estado"    => 0];
            // Insertamos los datos a la base de datos
            $naturaleza = $modeloNaturalezaCurso->where("estado", 1)->find($id);
            if (empty($naturaleza))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la naturaleza"], true);
            $modeloNaturalezaCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la naturaleza eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
