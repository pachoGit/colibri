<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeloClientes;

class Admin extends Controller
{
    // Inicio de para administrar nuestro clientes
    public function index()
    {
        $mclientes = new ModeloClientes();
        $clientes = $mclientes->traerClientes();

        $data = ["clientes" => $clientes];

        $this->vistasAdmin("admin/clientes/listar", $data);
    }
}
