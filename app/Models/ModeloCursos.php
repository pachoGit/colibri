<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloCursos extends Model
{
    protected $table = "Cursos";
    protected $primaryKey = "idCurso";
    protected $returnType = "array";
    protected $allowedFields = ["curso", "estado", "id_tipo", "fechaCreacion",
                                "id_categoria", "id_naturaleza", "fechaElim", "id_cliente"];
    protected $validationRules = ["curso"         => "required|string|max_length[255]",
                                  "id_cliente"    => "required|integer",
                                  "id_categoria"  => "required|integer",
                                  "id_naturaleza" => "required|integer",
                                  "id_tipo"       => "required|integer"
                                  ];
    protected $validationMessages = ["curso"         => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_cliente"    => ["integer"    => "Ingrese un numero"],
                                     "id_categoria"  => ["integer"    => "Ingrese un numero"],
                                     "id_naturaleza" => ["integer"    => "Ingrese un numero"],
                                     "id_tipo"       => ["integer"    => "Ingrese un numero"]
                                     ];

    public function traerCursos($cliente)
    {
        return $this->db->table("Cursos c")
                ->where("c.estado", 1)
                ->where("c.id_cliente", $cliente)
                ->join("TipoCurso tc", "c.id_tipo = tc.idTipoCurso")
                ->join("CategoriaCurso cc", "c.id_categoria = cc.idCategoriaCurso")
                ->join("NaturalezaCurso nc", "c.id_naturaleza = nc.idNaturaleza")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Cursos c")
                ->where("c.estado", 1)
                ->where("c.idCurso", $id)
                ->where("c.id_cliente", $cliente)
                ->join("TipoCurso tc", "c.id_tipo = tc.idTipoCurso")
                ->join("CategoriaCurso cc", "c.id_categoria = cc.idCategoriaCurso")
                ->join("NaturalezaCurso nc", "c.id_naturaleza = nc.idNaturaleza")
                ->get()->getResultArray();
    }

    /* Funciones para manejar la tabla TipoCurso */    

    public function traerTiposCurso($cliente)
    {
        return $this->db->table("TipoCurso tc")
                ->where("tc.estado", 1)
                ->where("tc.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerTipoPorId($id, $cliente)
    {
        return $this->db->table("TipoCurso tc")
                ->where("tc.estado", 1)
                ->where("tc.idTipoCurso", $id)
                ->where("tc.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    /* Funciones para manejar la tabla CategoriaCurso */
    
    public function traerCategoriasCurso($cliente)
    {
        return $this->db->table("CategoriaCurso cc")
                ->where("cc.estado", 1)
                ->where("cc.id_cliente", $cliente)
                ->get()->getResultArray();
    }
    
    public function traerCategoriaPorId($id, $cliente)
    {
        return $this->db->table("CategoriaCurso cc")
                ->where("cc.estado", 1)
                ->where("cc.idCategoriaCurso", $id)
                ->where("cc.id_cliente", $cliente)
                ->get()->getResultArray();
    }
    
    /* Funciones para manejar la tabla NaturalezaCurso */

    public function traerNaturalezasCurso($cliente)
    {
        return $this->db->table("NaturalezaCurso nc")
                ->where("nc.estado", 1)
                ->where("nc.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerNaturalezaPorId($id, $cliente)
    {
        return $this->db->table("NaturalezaCurso nc")
                ->where("nc.estado", 1)
                ->where("nc.idNaturaleza", $id)
                ->where("nc.id_cliente", $cliente)
                ->get()->getResultArray();
    }
    
}
