<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloSecciones extends Model
{
    protected $table = "Secciones";
    protected $primaryKey = "idSeccion";
    protected $returnType = "array";
    protected $allowedFields = ["seccion", "id_grado", "fechaCreacion",
                                "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["seccion"  => "required|string|max_length[255]",
                                  "id_grado"   => "required|integer",
                                  ];
    protected $validationMessages = ["seccion"  => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_grado" => ["integer"    => "Ingrese un numero"],
                                     ];

    public function traerSecciones($cliente)
    {
        return $this->db->table("Secciones c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Grados tc", "c.id_grado = tc.idGrado")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Secciones c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.idSeccion", $id)
                ->join("Grados tc", "c.id_grado = tc.idGrado")
                ->get()->getResultArray();
    }
}
