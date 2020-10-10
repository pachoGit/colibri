<?php

namespace App\Controllers;

//use CodeIgniter\Controller;

class Casa extends BaseController
{
    public function traerModulos()
    {
                // Permisos que tiene el usuario
        $modeloPermisos = new \App\Models\ModeloPermisos();
        $permisos = $modeloPermisos->traerDePerfil($_SESSION["id_perfil"], $_SESSION["id_cliente"]);

        // Modulos a lo que tiene acceso el usuario
        $modeloModulos = new \App\Models\ModeloModulos();
        $i = 0;
        $modulos = []; // Modulos en general al que tiene acceso
        $hijos = [];   // SubModulos de '$modulos' al que tiene acceso
        foreach ($permisos as $permiso)
        {
            // Obtenemos un modulo padre
            $modulosHijos = $modeloModulos->traerHijos($permiso["id_modulo"], $permiso["id_cliente"]);
            $j = 0;
            // Obtenemos los hijos del modulo padre
            foreach ($modulosHijos as $nhijos)
            {
                // Guardamos hijos
                $hijos[$j++] = ["modulo" => $nhijos["modulo"], "url" => $nhijos["url"]];
            }
            // Guardamos padres
            $modulos[$i++] = ["modulo" => $permiso["modulo"], "hijos" => $hijos];
            $j = 0;
            $hijos = [];
        }
        // Limpiar aquellos modulos sin hijos
        $nmodulos = []; // Datos para el menu
        $i = 0;
        foreach ($modulos as $modulo)
        {
            if (empty($modulo["hijos"]))
                continue;
            $nmodulos[$i++] = ["modulo" => $modulo["modulo"], "hijos" => $modulo["hijos"]];
        }

        return $nmodulos;
    }

    public function index()
    {
        $nmodulos = $this->traerModulos();

        $data = ["perfil"  => $_SESSION["perfil"],
                 "titulo"  => "INICIO",
                 "nombre"  => $_SESSION["nombres"],
                 "modulos" => $nmodulos];
        
        $this->cargarVistas("comun/inicio", $data);
    }



    // Terminar la sesion
    public function salir()
    {
        session_destroy();
        return redirect()->to(base_url()."/index.php/home/iniciar");
    }
}
