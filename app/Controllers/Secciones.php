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
        $m_grados = new ModeloGrados();
        $grados = $m_grados->traerGrados(1); // Reemplazar por la variable SESSION
        $data = ["grados" => $grados];
        return view("secciones/registrar", $data);
        return redirect()->to(base_url()."/index.php/secciones/listar");
    }
    public function editar($id)
    {
        $m_secciones = new ModeloSecciones();
        $m_grados = new ModeloGrados();

        $grados = $m_grados->traerGrados(1); // SESSION
        $seccion = $m_secciones->traerPorId($id, 1); // El SESSION
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
        return redirect()->to(base_url()."/index.php/secciones/listar");
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
            $modeloSecciones = new ModeloSecciones();
            $secciones = $modeloSecciones->traerSecciones($cliente);
            if (empty($secciones))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $secciones]);
            return json_encode(["Estado" => 200, "Total" => count($secciones), "Detalles" => $secciones]);
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
            $modeloSecciones = new ModeloSecciones();
            $seccion = $modeloSecciones->traerPorId($id, $cliente);
            if (empty($seccion))
            {
                return json_encode(["Estado" => 404, "Detalle" => "La seccion que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $seccion]);
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
            $datos = ["seccion"    => $solicitud->getVar("seccion"),
                      "id_grado"   => $solicitud->getVar("id_grado"),
                      "id_cliente" => $cliente];
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
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
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
                                                "id_cliente" => $cliente,
                                                "seccion"    => $datos["seccion"],
                                                "id_grado"   => $datos["id_grado"]])->findAll();
            if (!empty($seccion))
                return json_encode(["Estado" => 404, "Detalle" => "Este seccion ya existe"], true);

            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloSecciones->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del seccion guardado"];
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
                return json_encode(["Estado" => 404, "Detalle" => $errores]);
            }
            $modeloGrados = new ModeloGrados();
            // Insertamos los datos a la base de datos
            $seccion = $modeloSecciones->where("estado", 1)->find($id);
            if (empty($seccion))
                return json_encode(["Estado" => 404, "Detalle" => "No existe el seccion"], true);
            // Validamos si los datos son correctos
            $correcto = $modeloGrados->traerPorId($datos["id_grado"], $seccion["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el grado"]);
            
            $modeloSecciones->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del seccion actualizado"];
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
                return json_encode(["Estado" => 404, "Detalle" => "No existe el seccion"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloSecciones->update($id, $datos);
            $data = ["Estado" => 200, "Detalle" => "Datos del seccion eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}
