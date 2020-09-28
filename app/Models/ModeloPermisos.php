<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloPermisos extends Model
{
    protected $table = "Permisos";
    protected $primaryKey = "idPermiso";
    protected $returnType = "array";
    protected $allowedFields = ["id_modulo", "id_perfil", "estado", "fechaCreacion",
                                "fechaElim", "id_cliente"];
    protected $validationRules = ["id_modulo"  => "required|integer",
                                  "id_perfil"  => "required|integer",
                                  ];
    protected $validationMessages = ["id_modulo" => ["integer" => "Ingrese un numero"],
                                     "id_perfil" => ["integer" => "Ingrese un numero"],
                                     ];

    public function traerPermisos($cliente)
    {
        return $this->db->table("Permisos c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Modulos m", "c.id_modulo = m.idModulo")
                ->join("Perfiles p", "c.id_perfil = p.idPerfil")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Permisos c")
                ->where("c.estado", 1)
                ->where("c.idPermiso", $id)
                ->where("c.id_cliente", $cliente)
                ->join("Modulos m", "c.id_modulo = m.idModulo")
                ->join("Perfiles p", "c.id_perfil = p.idPerfil")
                ->get()->getResultArray();
    }

}
