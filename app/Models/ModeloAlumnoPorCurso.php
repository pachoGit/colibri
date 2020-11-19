<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloAlumnoPorCurso extends Model
{
    protected $table = "AlumnoPorCurso";
    protected $primaryKey = "idAlumnoPorCurso";
    protected $returnType = "array";
    protected $allowedFields = ["id_alumno", "id_curso", "id_seccion", "id_ciclo",
                                "notaFinal", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["id_alumno"  => "required|integer",
                                  "id_ciclo"   => "required|integer",
                                  ];
    protected $validationMessages = ["id_alumno"  => ["integer"    => "Ingrese un numero"],
                                     "id_curso"   => ["integer"    => "Ingrese un numero"],
                                     "id_seccion" => ["integer"    => "Ingrese un numero"],
                                     "id_ciclo"   => ["integer"    => "Ingrese un numero"]
                                     ];

    public function traerAlumnosPorCurso($cliente)
    {
        return $this->db->table("AlumnoPorCurso c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("Alumnos tc", "c.id_alumno = tc.idAlumno")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("AlumnoPorCurso c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.idAlumnoPorCurso", $id)
                ->join("Alumnos tc", "c.id_alumno = tc.idAlumno")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }

    public function traerMostrar($idalumno, $idciclo, $cliente)
    {
        return $this->db->table("AlumnoPorCurso c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.id_alumno", $idalumno)
                ->where("c.id_ciclo", $idciclo)                
                ->join("Alumnos tc", "c.id_alumno = tc.idAlumno")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }
    
    public function traerReporte($desde, $hasta, $cliente)
    {
        return $this->db->table("AlumnoPorCurso c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->where("c.fechaCreacion >", $desde)
                ->where("c.fechaCreacion <", $hasta)
                ->join("Alumnos tc", "c.id_alumno = tc.idAlumno")
                ->join("Cursos cc", "c.id_curso = cc.idCurso")
                ->join("Secciones nc", "c.id_seccion = nc.idSeccion")
                ->join("Ciclos cl", "c.id_ciclo = cl.idCiclo")
                ->get()->getResultArray();
    }

}
