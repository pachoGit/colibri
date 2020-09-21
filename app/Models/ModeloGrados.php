<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloGrados extends Model
{
    protected $table = "Grados";
    protected $primaryKey = "idGrado";
    protected $returnType = "array";
    protected $allowedFields = ["grado", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["grado"      => "required|string|max_length[255]",
                                  "id_cliente" => "required|integer"
                                  ];
    protected $validationMessages = ["grado"      => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_cliente" => ["integer"    => "Ingrese un numero"]
                                     ];

    // Trae los grados de un respectivo cliente
    public function traerGrados($cliente)
    {
        return $this->db->table("Grados g")
                ->where("g.estado", 1)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Grados g")
                ->where("g.estado", 1)
                ->where("g.idGrado", $id)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
