<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloCursos;
use App\Models\ModeloClientes;

class Cursos extends Controller
{
    public function listar()
    {
        return view("cursos/listar");
    }

    public function registrar()
    {
        return view("cursos/registrar");
    }

    public function editar($id)
    {
        $data = ["id" => $id];
        return view("cursos/editar", $data);
    }

    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("cursos/eliminar", $data);
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
            $cursos = $modeloCursos->traerCursos($_SERVER["HTTP_CLIENTE"]);
            if (empty($cursos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $cursos]);
            return json_encode(["Estado" => 200, "Total" => count($cursos), "Detalles" => $cursos]);
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
            $curso = $modeloCursos->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($curso))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El curso que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $curso]);
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
            $datos = ["curso"         => $solicitud->getVar("curso"),
                      "id_categoria"  => $solicitud->getVar("id_categoria"),
                      "id_naturaleza" => $solicitud->getVar("id_naturaleza"),
                      "id_tipo"       => $solicitud->getVar("id_tipo"),
                      "id_cliente"    => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloCursos = new ModeloCursos();
            $validacion->setRules($modeloCursos->validationRules, $modeloCursos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            
            $correcto = $modeloCursos->traerCategoriaPorId($datos["id_categoria"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la categoria"]);
            $correcto = $modeloCursos->traerNaturalezaPorId($datos["id_naturaleza"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la naturaleza"]);
            $correcto = $modeloCursos->traerTipoPorId($datos["id_tipo"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el tipo"]);

            $curso = $modeloCursos->where(["estado"     => 1,
                                           "id_cliente" => $datos["id_cliente"],
                                           "curso"      => $datos["curso"]])->findAll();
            if (!empty($curso))
                return json_encode(["Estado" => 404, "Detalles" => "Este curso ya existe"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloCursos->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del curso guardado"];
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
            $modeloCursos = new ModeloCursos();
            $validacion->setRules($modeloCursos->validationRules, $modeloCursos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $curso = $modeloCursos->where("estado", 1)->find($id);
            if (empty($curso))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el curso"], true);
            // Validamos si los datos son correctos
            $correcto = $modeloCursos->traerCategoriaPorId($datos["id_categoria"], $curso["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la categoria"]);
            $correcto = $modeloCursos->traerNaturalezaPorId($datos["id_naturaleza"], $curso["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la naturaleza"]);
            $correcto = $modeloCursos->traerTipoPorId($datos["id_tipo"], $curso["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el tipo"]);
            
            $modeloCursos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del curso actualizado"];
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
            $modeloCursos = new ModeloCursos();
            $curso = $modeloCursos->where("estado", 1)->find($id);
            if (empty($curso))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el curso"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloCursos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del curso eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
