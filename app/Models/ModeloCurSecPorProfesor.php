<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloCurSecPorProfesor extends Model
{
    protected $table = "CurSecPorProfesor";
    protected $primaryKey = "idCurSecPorProfesor";
    protected $returnType = "array";
    protected $allowedFields = ["id_profesor", "id_curso", "id_seccion", "id_ciclo",
                                "notaFinal", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["id_profesor"  => "required|integer",
                                  "id_curso"   => "required|integer",
                                  "id_seccion" => "required|integer",
                                  "id_ciclo"   => "required|integer",
                                  ];
    protected $validationMessages = ["id_profesor"  => ["integer"    => "Ingrese un numero"],
                                     "id_curso"   => ["integer"    => "Ingrese un numero"],
                                     "id_seccion" => ["integer"    => "Ingrese un numero"],
                                     "id_ciclo"   => ["integer"    => "Ingrese un numero"]
                                     ];

    public function traerProfesoresPorCurso($cliente)
    {
        return $this->db->table("CurSecPorProfesor c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Profesores tc", "c.id_profesor = tc.idProfesor")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("CurSecPorProfesor c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.idCurSecPorProfesor", $id)
                ->join("Profesores tc", "c.id_profesor = tc.idProfesor")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }
}
