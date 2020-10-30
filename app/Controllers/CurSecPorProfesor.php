<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloCurSecPorProfesor;
use App\Models\ModeloProfesores;
use App\Models\ModeloCursos;
use App\Models\ModeloSecciones;
use App\Models\ModeloCiclos;
use App\Models\ModeloGrados;


class CurSecPorProfesor extends Controller
{
    public function listar()
    {
        return view("matriculas/profesores/listar");
    }

    public function registrar()
    {
        session_start();
        $m_profesores = new ModeloProfesores();
        $m_cursos = new ModeloCursos();
        $m_ciclos = new ModeloCiclos();
        $m_grados = new ModeloGrados();        

        $profesores = $m_profesores->traerProfesores($_SESSION["id_cliente"]);
        $cursos = $m_cursos->traerCursos($_SESSION["id_cliente"]);
        $ciclos = $m_ciclos->traerCiclos($_SESSION["id_cliente"]);
        $grados = $m_grados->traerGrados($_SESSION["id_cliente"]);        
        
        $data = ["profesores" => $profesores, "cursos" => $cursos,
                 "ciclos"     => $ciclos,     "grados" => $grados];
        return view("matriculas/profesores/registrar", $data);
        
    }

    public function ver($id)
    {
        $data = ["id" => $id];
        
        return view("matriculas/profesores/ver",$data);
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
            $modeloCurSecPorProfesor = new ModeloCurSecPorProfesor();
            $reg = $modeloCurSecPorProfesor->traerProfesoresPorCurso($_SERVER["HTTP_CLIENTE"]);
            if (empty($reg))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $reg]);
            return json_encode(["Estado" => 200, "Total" => count($reg), "Detalles" => $reg]);
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
            $modeloCurSecPorProfesor = new ModeloCurSecPorProfesor();
            $curso = $modeloCurSecPorProfesor->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($curso))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El CurSecPorProfesor que busca no esta registrado"], true);
            }
            $curso = $curso[0];
            $datos = $modeloCurSecPorProfesor->traerMostrar($curso["id_profesor"], $curso["id_ciclo"], $curso["id_cliente"]);
            return json_encode(["Estado" => 200, "Detalles" => $datos]);
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
            $datos = ["id_profesor"  => $solicitud->getVar("id_profesor"),
                      "id_curso"   => $solicitud->getVar("id_curso"),
                      "id_seccion" => $solicitud->getVar("id_seccion"),
                      "id_ciclo"   => $solicitud->getVar("id_ciclo"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloCurSecPorProfesor = new ModeloCurSecPorProfesor();
            $validacion->setRules($modeloCurSecPorProfesor->validationRules, $modeloCurSecPorProfesor->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloProfesores = new ModeloProfesores();
            $modeloCursos = new ModeloCursos();
            $modeloSecciones = new ModeloSecciones();
            $modeloCiclos = new ModeloCiclos();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            
            $correcto = $modeloProfesores->traerPorId($datos["id_profesor"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el profesor"]);
            $correcto = $modeloCursos->traerPorId($datos["id_curso"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el curso"]);
            $correcto = $modeloSecciones->traerPorId($datos["id_seccion"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe la seccion"]);
            $correcto = $modeloCiclos->traerPorId($datos["id_ciclo"], $datos["id_cliente"]);            
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el ciclo"]);

            $correcto = $modeloCurSecPorProfesor->where(["estado"     => 1,
                                                         "id_cliente" => $datos["id_cliente"],
                                                         "id_profesor"  => $datos["id_profesor"],
                                                         "id_curso"   => $datos["id_curso"],
                                                         "id_seccion" => $datos["id_seccion"],
                                                         "id_ciclo"   => $datos["id_ciclo"]])->findAll();
            if (!empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "Este profesor ya esta registrado <curso,seccion,ciclo>"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloCurSecPorProfesor->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del CurSecPorProfesor guardado"];
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
            $modeloCurSecPorProfesor = new ModeloCurSecPorProfesor();
            $validacion->setRules($modeloCurSecPorProfesor->validationRules, $modeloCurSecPorProfesor->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $reg = $modeloCurSecPorProfesor->where("estado", 1)->find($id);
            if (empty($reg))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el CurSecPorProfesor"], true);
            
            $modeloProfesores = new ModeloProfesores();
            $modeloCursos = new ModeloCursos();
            $modeloSecciones = new ModeloSecciones();
            $modeloCiclos = new ModeloCiclos();
            // Validamos si los datos son correctos
            $correcto = $modeloProfesores->traerPorId($datos["id_profesor"], $reg["id_cliente"]);
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
            
            $modeloCurSecPorProfesor->update($id, $datos);
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
            $modeloCurSecPorProfesor = new ModeloCurSecPorProfesor();
            $reg = $modeloCurSecPorProfesor->where("estado", 1)->find($id);
            if (empty($reg))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el CurSecPorProfesor"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloCurSecPorProfesor->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del CurSecPorProfesor eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
