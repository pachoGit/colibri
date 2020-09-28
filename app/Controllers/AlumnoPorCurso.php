<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloAlumnoPorCurso;
use App\Models\ModeloAlumnos;
use App\Models\ModeloCursos;
use App\Models\ModeloSecciones;
use App\Models\ModeloCiclos;


class AlumnoPorCurso extends Controller
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
            $modeloAlumnoPorCurso = new ModeloAlumnoPorCurso();
            $cursos = $modeloAlumnoPorCurso->traerAlumnosPorCurso($cliente);
            if (empty($cursos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $cursos]);
            return json_encode(["Estado" => 200, "Total" => count($cursos), "Detalles" => $cursos]);
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
            $modeloAlumnoPorCurso = new ModeloAlumnoPorCurso();
            $curso = $modeloAlumnoPorCurso->traerPorId($id, $cliente);
            if (empty($curso))
            {
                return json_encode(["Estado" => 404, "Detalle" => "El AlumnoPorCurso que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalle" => $curso]);
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
            $datos = ["id_alumno"  => $solicitud->getVar("id_alumno"),
                      "id_curso"   => $solicitud->getVar("id_curso"),
                      "id_seccion" => $solicitud->getVar("id_seccion"),
                      "id_ciclo"   => $solicitud->getVar("id_ciclo"),
                      "id_cliente" => $cliente];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloAlumnoPorCurso = new ModeloAlumnoPorCurso();
            $validacion->setRules($modeloAlumnoPorCurso->validationRules, $modeloAlumnoPorCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloAlumnos = new ModeloAlumnos();
            $modeloCursos = new ModeloCursos();
            $modeloSecciones = new ModeloSecciones();
            $modeloCiclos = new ModeloCiclos();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            
            $correcto = $modeloAlumnos->traerPorId($datos["id_alumno"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el alumno"]);
            $correcto = $modeloCursos->traerPorId($datos["id_curso"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el curso"]);
            $correcto = $modeloSecciones->traerPorId($datos["id_seccion"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la seccion"]);
            $correcto = $modeloCiclos->traerPorId($datos["id_ciclo"], $datos["id_cliente"]);            
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el ciclo"]);

            $correcto = $modeloAlumnoPorCurso->where(["estado"     => 1,
                                                      "id_cliente" => $cliente,
                                                      "id_alumno"  => $datos["id_alumno"],
                                                      "id_curso"   => $datos["id_curso"],
                                                      "id_seccion" => $datos["id_seccion"],
                                                      "id_ciclo"   => $datos["id_ciclo"]])->findAll();
            if (!empty($correcto))
                return json_encode(["Estado" => 404, "Detalle" => "Este alumno ya esta registrado <curso,seccion,ciclo>"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloAlumnoPorCurso->insert($datos);
            $data = ["Estado" => 200, "Detalle" => "Registro exitoso, datos del alumnoPorCurso guardado"];
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
            $modeloAlumnoPorCurso = new ModeloAlumnoPorCurso();
            $validacion->setRules($modeloAlumnoPorCurso->validationRules, $modeloAlumnoPorCurso->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $reg = $modeloAlumnoPorCurso->where("estado", 1)->find($id);
            if (empty($reg))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el AlumnoPorCurso"], true);
            
            $modeloAlumnos = new ModeloAlumnos();
            $modeloCursos = new ModeloCursos();
            $modeloSecciones = new ModeloSecciones();
            $modeloCiclos = new ModeloCiclos();
            // Validamos si los datos son correctos
            $correcto = $modeloAlumnos->traerPorId($datos["id_alumno"], $reg["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el alumno"]);
            $correcto = $modeloCursos->traerPorId($datos["id_curso"], $reg["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el curso"]);
            $correcto = $modeloSecciones->traerPorId($datos["id_seccion"], $reg["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la seccion"]);
            $correcto = $modeloCiclos->traerPorId($datos["id_ciclo"], $reg["id_cliente"]);            
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el ciclo"]);
            
            $modeloAlumnoPorCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del curso actualizado"];
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
            $modeloAlumnoPorCurso = new ModeloAlumnoPorCurso();
            $reg = $modeloAlumnoPorCurso->where("estado", 1)->find($id);
            if (empty($reg))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el AlumnoPorCurso"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloAlumnoPorCurso->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del AlumnoPorCurso eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
