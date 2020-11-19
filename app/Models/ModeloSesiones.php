<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloSesiones extends Model
{
    protected $table = "Sesiones";
    protected $primaryKey = "idSesion";
    protected $returnType = "array";
    protected $allowedFields = ["sesion", "id_usuario",
                                "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["id_usuario"   => "required|integer"

                                  ];
    protected $validationMessages = ["id_usuario" => ["integer"    => "Ingrese un numero"]
                                     ];

    public function traerSesiones($cliente)
    {
        return $this->db->table("Sesiones c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Usuarios tc", "c.id_usuario = tc.idUsuario")
                ->orderBy("c.sesion", "DESC")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Sesiones c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.idSesion", $id)
                ->join("Usuarios tc", "c.id_usuario = tc.idUsuario")
                ->get()->getResultArray();
    }
}
