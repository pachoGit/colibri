<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloRegistros;
use App\Models\ModeloClientes;
use App\Models\ModeloAlumnoPorCurso;
use App\Models\ModeloAlumnos;
use App\Models\ModeloProfesores;
use App\Models\ModeloCursos;
use App\Models\ModeloSecciones;
use App\Models\ModeloCiclos;
use App\Models\ModeloGrados;


class FuncsAjax extends Controller
{
    // Funcion de ayuda, para obtener las secciones de un determinado grado
    public function seccionesDeGrado()
    {
        header("Access-Control-Allow-Origin: *");

        $m_grados = new ModeloGrados();
        // $_POST es lo que obtenemos de AJAX
        $secciones = $m_grados->traerSeccionesDeGrado($_POST["id"], $_POST["id_cliente"]);
        return json_encode($secciones, true);
    }

    public function reporte_matricula()
    {
        header("Access-Control-Allow-Origin: *");
        
        $modelo = new ModeloAlumnoPorCurso();
        $resultado = $modelo->traerReporte($_POST["desde"], $_POST["hasta"], $_POST["cliente"]);

        return json_encode($resultado, true);
    }

    public function reporte_alumnos()
    {
        header("Access-Control-Allow-Origin: *");
        
        $modelo = new ModeloAlumnos();
        $resultado = $modelo->traerReporte($_POST["desde"], $_POST["hasta"], $_POST["cliente"]);
        
        return json_encode($resultado, true);
    }

    public function reporte_profesores()
    {
        header("Access-Control-Allow-Origin: *");
        
        $modelo = new ModeloProfesores();
        $resultado = $modelo->traerReporte($_POST["desde"], $_POST["hasta"], $_POST["cliente"]);
        
        return json_encode($resultado, true);
    }

}
