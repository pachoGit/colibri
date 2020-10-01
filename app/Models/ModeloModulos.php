<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloModulos extends Model
{
    protected $table = "Modulos";
    protected $primaryKey = "idModulo";
    protected $returnType = "array";
    protected $allowedFields = ["modulo", "id_moduloPadre", "fechaElim", "id_cliente",
                                "fechaCreacion", "url", "estado"];
    protected $validationRules = ["modulo" => "required|string|max_length[255]",
                                  "url"    => "required|string|max_length[255]"];

    protected $validationMessages = ["modulo" => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "url"    => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los modulos de un respectivo cliente
    public function traerModulos($cliente)
    {
        return $this->db->table("Modulos m")
                ->where("m.estado", 1)
                ->where("m.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Modulos m")
                ->where("m.estado", 1)
                ->where("m.idModulo", $id)
                ->where("m.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerHijos($id, $cliente)
    {
        return $this->db->table("Modulos m")
                ->where("m.estado", 1)
                ->where("m.id_moduloPadre", $id)
                ->where("m.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
