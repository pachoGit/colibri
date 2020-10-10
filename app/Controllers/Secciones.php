<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloSecciones;
use App\Models\ModeloGrados;


class Secciones extends Controller
{
    public function listar(){
        return view ("secciones/listar");
    }
    public function registrar()
    {
        session_start();
        $m_grados = new ModeloGrados();
        $grados = $m_grados->traerGrados($_SESSION["id_cliente"]);
        $data = ["grados" => $grados];
        return view("secciones/registrar", $data);
    }
    public function editar($id)
    {
        session_start();
        $m_secciones = new ModeloSecciones();
        $m_grados = new ModeloGrados();

        $grados = $m_grados->traerGrados($_SESSION["id_cliente"]); // SESSION
        $seccion = $m_secciones->traerPorId($id, $_SESSION["id_cliente"]); // El SESSION
        $mi_grado = $seccion[0]["grado"];
        $data = ["mi_grado" => $mi_grado,
                 "id"        => $id,
                 "grados"  => $grados];
        return view("secciones/editar", $data);
    }
    public function eliminar($id)
    {
        $data = ["id" => $id];
        echo view("secciones/eliminar", $data);
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
            $modeloSecciones = new ModeloSecciones();
            $secciones = $modeloSecciones->traerSecciones($_SERVER["HTTP_CLIENTE"]);
            if (empty($secciones))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $secciones]);
            return json_encode(["Estado" => 200, "Total" => count($secciones), "Detalles" => $secciones]);
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
            $modeloSecciones = new ModeloSecciones();
            $seccion = $modeloSecciones->traerPorId($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($seccion))
            {
                return json_encode(["Estado" => 404, "Detalles" => "La seccion que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $seccion]);
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
            $datos = ["seccion"    => $solicitud->getVar("seccion"),
                      "id_grado"   => $solicitud->getVar("id_grado"),
                      "id_cliente" => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloSecciones = new ModeloSecciones();
            $validacion->setRules($modeloSecciones->validationRules, $modeloSecciones->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloGrados = new ModeloGrados();
            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            
            $correcto = $modeloGrados->traerPorId($datos["id_grado"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el grado"]);

            $seccion = $modeloSecciones->where(["estado"     => 1,
                                                "id_cliente" => $datos["id_cliente"],
                                                "seccion"    => $datos["seccion"],
                                                "id_grado"   => $datos["id_grado"]])->findAll();
            if (!empty($seccion))
                return json_encode(["Estado" => 404, "Detalles" => "Este seccion ya existe"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloSecciones->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos de la seccion guardado"];
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
            $modeloSecciones = new ModeloSecciones();
            $validacion->setRules($modeloSecciones->validationRules, $modeloSecciones->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            $modeloGrados = new ModeloGrados();
            // Insertamos los datos a la base de datos
            $seccion = $modeloSecciones->where("estado", 1)->find($id);
            if (empty($seccion))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el seccion"], true);
            // Validamos si los datos son correctos
            $correcto = $modeloGrados->traerPorId($datos["id_grado"], $seccion["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el grado"]);
            
            $modeloSecciones->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la seccion actualizado"];
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
            $modeloSecciones = new ModeloSecciones();
            $seccion = $modeloSecciones->where("estado", 1)->find($id);
            if (empty($seccion))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el seccion"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloSecciones->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos de la seccion eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
