<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloSedes extends Model
{
    protected $table = "Sedes";
    protected $primaryKey = "idSede";
    protected $returnType = "array";
    protected $allowedFields = ["sede", "direccion", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["sede"      => "required|string|max_length[255]"];

    protected $validationMessages = ["sede"      => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los grados de un respectivo cliente
    public function traerSedes($cliente)
    {
        return $this->db->table("Sedes g")
                ->where("g.estado", 1)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Sedes g")
                ->where("g.estado", 1)
                ->where("g.idSede", $id)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}