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

    public function usuarios()
    {
        return $this->vistasDocSimple("principal/documentacion/seguridad/usuarios");
    }

}


    
