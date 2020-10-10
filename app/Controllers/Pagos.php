<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloAlumnos;
use App\Models\ModeloProfesores;
use App\Models\ModeloPagos;
use App\Models\ModeloMotivoPago;

class Pagos extends Controller
{
    
    public function listar_alumnos()
    {
        return view("pagos/alumnos/listar");
    }
    
    public function registrar_alumno()
    {
        session_start();
        $m_alumnos = new ModeloAlumnos();
        $m_motivos = new ModeloMotivoPago();
        
        $alumnos = $m_alumnos->traerAlumnos($_SESSION["id_cliente"]);
        $motivos = $m_motivos->traerMotivoPago($_SESSION["id_cliente"]);
        
        $data = ["alumnos" => $alumnos, "motivos" => $motivos];
        return view("pagos/alumnos/registrar", $data);
    }

    public function ver_alumno($id)
    {
        session_start();
        $data = ["id" => $id];

        return view("pagos/alumnos/ver", $data);
    }

    public function editar_alumno($id)
    {
        session_start();

        $m_motivos = new ModeloMotivoPago();
        $motivos = $m_motivos->traerMotivoPago($_SESSION["id_cliente"]);
        
        $data = ["id" => $id, "motivos" => $motivos];

        return view("pagos/alumnos/editar", $data);
    }

    public function eliminar_alumno($id)
    {
        session_start();

        $data = ["id" => $id];
        echo view("pagos/alumnos/eliminar", $data);
    }



    public function listar_profesores()
    {
        return view("pagos/profesores/listar");
    }
    
    public function registrar_profesor()
    {
        session_start();
        $m_profesores = new ModeloProfesores();
        $m_motivos = new ModeloMotivoPago();
        
        $profesores = $m_profesores->traerProfesores($_SESSION["id_cliente"]);
        $motivos = $m_motivos->traerMotivoPago($_SESSION["id_cliente"]);
        
        $data = ["profesores" => $profesores, "motivos" => $motivos];
        return view("pagos/profesores/registrar", $data);
    }

    public function ver_profesor($id)
    {
        session_start();
        $data = ["id" => $id];

        return view("pagos/profesores/ver", $data);
    }

    public function editar_profesor($id)
    {
        session_start();

        $m_motivos = new ModeloMotivoPago();
        $motivos = $m_motivos->traerMotivoPago($_SESSION["id_cliente"]);
        
        $data = ["id" => $id, "motivos" => $motivos];

        return view("pagos/profesores/editar", $data);
    }

    public function eliminar_profesor($id)
    {
        session_start();

        $data = ["id" => $id];
        echo view("pagos/profesores/eliminar", $data);
    }


    

    public function index_alumnos()
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
            $modeloPagos = new ModeloPagos();
            $pagos = $modeloPagos->traerPagosAlumnos($_SERVER["HTTP_CLIENTE"]);
            if (empty($pagos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $pagos]);
            return json_encode(["Estado" => 200, "Total" => count($pagos), "Detalles" => $pagos]);
        }
        return json_encode($error, true);
    }

    public function show_alumno($id)
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
            $modeloPagos = new ModeloPagos();
            $pago = $modeloPagos->traerPorIdAlumno($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($pago))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El pago que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $pago]);
        }
        return json_encode($error);
    }


    public function index_profesores()
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
            $modeloPagos = new ModeloPagos();
            $pagos = $modeloPagos->traerPagosProfesores($_SERVER["HTTP_CLIENTE"]);
            if (empty($pagos))
                return json_encode(["Estado" => 404, "Resultados" => 0, "Detalles" => $pagos]);
            return json_encode(["Estado" => 200, "Total" => count($pagos), "Detalles" => $pagos]);
        }
        return json_encode($error, true);
    }

    public function show_profesor($id)
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
            $modeloPagos = new ModeloPagos();
            $pago = $modeloPagos->traerPorIdProfesor($id, $_SERVER["HTTP_CLIENTE"]);
            if (empty($pago))
            {
                return json_encode(["Estado" => 404, "Detalles" => "El pago que busca no esta registrado"], true);
            }
            return json_encode(["Estado" => 200, "Detalles" => $pago]);
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
            $datos = ["id_alumno"   => $solicitud->getVar("id_alumno"),
                      "id_profesor" => $solicitud->getVar("id_profesor"),
                      "id_motivo"   => $solicitud->getVar("id_motivo"),
                      "fechaPago"   => $solicitud->getVar("fechaPago"),
                      "monto"       => $solicitud->getVar("monto"),
                      "id_cliente"  => $solicitud->getVar("id_cliente")];
            if (empty($datos))
            {
                return json_encode(["Estado" => 404, "Detalles" => "Hay datos vacios"], true);
            }
            // Configuramos las reglas de validacion
            $modeloPagos = new ModeloPagos();
            $validacion->setRules($modeloPagos->validationRules, $modeloPagos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            /* Validamos las relaciones de la tabla */
            $modeloClientes = new ModeloClientes();
            $modeloMotivoPago = new ModeloMotivoPago();

            $correcto = $modeloClientes->traerPorId($datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el cliente"]);
            $correcto = $modeloMotivoPago->traerPorId($datos["id_motivo"], $datos["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el motivo de pago"]);
            if (is_null($datos["id_alumno"]))
            {
                $modeloProfesores = new ModeloProfesores();
                $correcto = $modeloProfesores->traerPorId($datos["id_profesor"], $datos["id_cliente"]);
                if (empty($correcto))
                    return json_encode(["Estado" => 404, "Detalles" => "No existe el profesor"]);
            }
            else
            {
                $modeloAlumnos = new ModeloAlumnos();
                $correcto = $modeloAlumnos->traerPorId($datos["id_alumno"], $datos["id_cliente"]);
                if (empty($correcto))
                    return json_encode(["Estado" => 404, "Detalles" => "No existe el alumno"]);
            }
                
            $datos["fechaCreacion"] = date("Y-m-d");
            // Insertamos los datos a la ba[e de datos
            $modeloPagos->insert($datos);
            $data = ["Estado" => 200, "Detalles" => "Registro exitoso, datos del pago guardado"];
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
            $modeloPagos = new ModeloPagos();
            $validacion->setRules($modeloPagos->validationRules, $modeloPagos->validationMessages);
            $validacion->withRequest($this->request)->run(); // Le damos los datos de "solicitud" para que valide
            // Verificamos si no hay errores en la validacion de los datos
            if (($errores = $validacion->getErrors()))
            {
                return json_encode(["Estado" => 404, "Detalles" => $errores]);
            }
            // Insertamos los datos a la base de datos
            $pago = $modeloPagos->where("estado", 1)->find($id);
            if (empty($pago))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el pago"], true);

            $modeloMotivoPago = new ModeloMotivoPago();
            
            $correcto = $modeloMotivoPago->traerPorId($datos["id_motivo"], $pago["id_cliente"]);
            if (empty($correcto))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el motivo de pago"]);



            $modeloPagos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del pago actualizado"];
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
            $modeloPagos = new ModeloPagos();
            $pago = $modeloPagos->where("estado", 1)->find($id);
            if (empty($pago))
                return json_encode(["Estado" => 404, "Detalles" => "No existe el pago"], true);
            $datos = ["estado"    => 0,
                      "fechaElim" => date("Y-m-d")];
            // Insertamos los datos a la base de datos
            $modeloPagos->update($id, $datos);
            $data = ["Estado" => 200, "Detalles" => "Datos del pago eliminado"];
            return json_encode($data, true);
        }
        return json_encode($error);
    }
}

