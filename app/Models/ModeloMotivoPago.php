<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloMotivoPago extends Model
{
    protected $table = "MotivoPago";
    protected $primaryKey = "idMotivo";
    protected $returnType = "array";
    protected $allowedFields = ["motivo", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["motivo"  => "required|string|max_length[255]"];

    protected $validationMessages = ["motivo" => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los grados de un respectivo cliente
    public function traerMotivoPago($cliente)
    {
        return $this->db->table("MotivoPago g")
                ->where("g.estado", 1)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("MotivoPago g")
                ->where("g.estado", 1)
                ->where("g.idMotivo", $id)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
