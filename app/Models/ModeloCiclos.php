<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloCiclos extends Model
{
    protected $table = "Ciclos";
    protected $primaryKey = "idCiclo";
    protected $returnType = "array";
    protected $allowedFields = ["ciclo", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["ciclo"      => "required|string|max_length[255]"];
    protected $validationMessages = ["ciclo"      => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los ciclos de un respectivo cliente
    public function traerCiclos($cliente)
    {
        return $this->db->table("Ciclos c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Ciclos c")
                ->where("c.estado", 1)
                ->where("c.idCiclo", $id)
                ->where("c.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
