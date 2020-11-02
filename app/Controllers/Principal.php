<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Principal extends Controller
{
    public function index()
    {
        return $this->vistasPrincipalSimple("principal/inicio");
    }

    public function documentacion()
    {
        return $this->vistasDocSimple("principal/documentacion/inicio");
    }

    /*** Documentacion del modulo seguridad ***/

    public function usuarios()
    {
        return $this->vistasDocSimple("principal/documentacion/seguridad/usuarios");
    }

    public function perfiles()
    {
        return $this->vistasDocSimple("principal/documentacion/seguridad/perfiles");
    }

    /*** Documentacion del modulo mantenimiento ***/

    public function alumnos()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/alumnos");
    }

    public function profesores()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/profesores");
    }

    public function cursos()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/cursos");
    }

    public function secciones()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/secciones");
    }

    public function grados()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/grados");
    }

    public function sedes()
    {
        return $this->vistasDocSimple("principal/documentacion/mantenimiento/sedes");
    }

    /*** Documentacion del modulo pagos ***/

    public function palumnos()
    {
        return $this->vistasDocSimple("principal/documentacion/pagos/alumnos");
    }

    public function pprofesores()
    {
        return $this->vistasDocSimple("principal/documentacion/pagos/profesores");
    }

    public function pmotivo()
    {
        return $this->vistasDocSimple("principal/documentacion/pagos/motivo");
    }

    /*** Documentacion del modulo matriculas ***/

    public function malumnos()
    {
        return $this->vistasDocSimple("principal/documentacion/matriculas/alumnos");
    }

    public function mprofesores()
    {
        return $this->vistasDocSimple("principal/documentacion/matriculas/profesores");
    }

}


    
