<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloClientes;

class Admin extends Controller
{
    // Inicio de para administrar nuestro clientes
    public function index()
    {
        // Esta variable no sirve
        $data = ["Estado" => 200];

        $this->vistasAdmin("admin/clientes/listar", $data);
    }
}
