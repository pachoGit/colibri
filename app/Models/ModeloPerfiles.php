<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloPerfiles extends Model
{
    protected $table = "Perfiles";
    protected $primaryKey = "idPerfil";
    protected $returnType = "array";
    protected $allowedFields = ["perfil", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["perfil"      => "required|string|max_length[255]"];
    protected $validationMessages = ["perfil"      => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los perfils de un respectivo cliente
    public function traerPerfiles($cliente)
    {
        return $this->db->table("Perfiles p")
                ->where("p.estado", 1)
                ->where("p.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Perfiles p")
                ->where("p.estado", 1)
                ->where("p.idPerfil", $id)
                ->where("p.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
