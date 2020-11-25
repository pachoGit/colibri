<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloClientes extends Model
{
    protected $table = "Clientes";
    protected $primaryKey = "idCliente";
    protected $returnType = "array";
    protected $allowedFields = ["cliente", "ruc", "fechaContrato", "fechaCreacion", "correoCliente", "url",
                                "nombreEncar", "apellidoEncar", "foto", "fechaElim", "estado", "terminos"];
    protected $validationRules = ["cliente"       => "required|string|max_length[255]",
                                  "ruc"           => "required|string|max_length[11]|min_length[11]",
                                  "nombreEncar"   => "required|string|max_length[255]",
                                  "apellidoEncar" => "required|string|max_length[255]",
                                  "fechaContrato" => "required|valid_date"
                                  ];
    protected $validationMessages = ["cliente"       => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "ruc"           => ["max_length" => "El ruc debe ser de 11 digitos",
                                                         "min_length" => "El ruc debe ser de 11 digitos"],
                                     "nombreEncar"   => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "apellidoEncar" => ["max_length" => "Se ha sobrepasado el tamanio del texto"]
                                     ];

    // Trae los clientes de un respectivo cliente
    public function traerClientes()
    {
        return $this->db->table("Clientes c")
                ->where("c.estado", 1)
                ->get()->getResultArray();
    }

    public function traerPorId($id)
    {
        return $this->db->table("Clientes c")
                ->where("c.estado", 1)
                ->where("c.idCliente", $id)
                ->get()->getResultArray();
    }

    // Traer todos los usuarios que un cliente ha creado
    public function traerUsuarios($idcliente)
    {
        return $this->db->table("Usuarios u")
                ->where("u.estado", 1)
                ->where("u.id_cliente". $idcliente)
                ->get()->getResultArray();
    }
}
