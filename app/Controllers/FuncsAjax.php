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
use App\Models\ModeloGrados;


class FuncsAjax extends Controller
{
    // Funcion de ayuda, para obtener las secciones de un determinado grado
    public function seccionesDeGrado()
    {
        header("Access-Control-Allow-Origin: *");
        /*
        $m_grados = new ModeloGrados();
        // $_POST es lo que obtenemos de AJAX
        $secciones = $m_grados->traerSeccionesDeGrado($_POST["id"], $_POST["id_cliente"]);
        return json_encode($secciones, true);
        */
        return json_encode(["Estado" => 200, "Hola" => "Bien"], true);
    }

}
