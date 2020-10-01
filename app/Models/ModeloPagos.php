<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloPagos extends Model
{
    protected $table = "Pagos";
    protected $primaryKey = "idPago";
    protected $returnType = "array";
    protected $allowedFields = ["id_alumno", "id_motivo", "estado", "fechaCreacion",
                                "fechaPago", "fechaElim", "id_cliente", "monto"];
    protected $validationRules = ["id_alumno"  => "required|integer",
                                  "id_motivo"  => "required|integer",
                                  "monto"      => "required|decimal",
                                  "fechaPago"  => "required|valid_date"
                                  ];
    protected $validationMessages = ["id_alumno" => ["integer" => "Ingrese un numero"],
                                     "id_motivo" => ["integer" => "Ingrese un numero"],
                                     "monto"     => ["decimal" => "Ingrese un numero decimal"],
                                     "fechaPago" => ["valid_date" => "Ingrese una fecha correcta"]
                                     ];

    public function traerPagos($cliente)
    {
        return $this->db->table("Pagos c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Alumnos m", "c.id_alumno = m.idAlumno")
                ->join("MotivoPago p", "c.id_motivo = p.idMotivo")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Pagos c")
                ->where("c.estado", 1)
                ->where("c.idPago", $id)
                ->where("c.id_cliente", $cliente)
                ->join("Alumnos m", "c.id_alumno = m.idAlumno")
                ->join("MotivoPago p", "c.id_motivo = p.idMotivo")
                ->get()->getResultArray();
    }

}
