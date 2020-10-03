<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloProfesores extends Model
{
    protected $table = "Profesores";
    protected $primaryKey = "idProfesor";
    protected $returnType = "array";
    protected $allowedFields = ["nombres", "apellidos", "dni", "sexo", "rutaFoto", "direccion", "edad",
                                "estudios", "comentario","fechaCreacion", "estado",
                                "fechaElim", "correo", "id_cliente"];
    protected $validationRules = ["nombres"     => "required|string|max_length[255]",
                                  "apellidos"   => "required|string|max_length[255]",
                                  "dni"         => "required|string|max_length[8]|min_length[8]",
                                  "sexo"        => "required|string|max_length[1]|min_length[1]",
                                  "rutaFoto"    => "required|string|max_length[255]",
                                  "direccion"   => "required|string|max_length[255]",
                                  "edad"        => "required|integer|is_natural",
                                  "estudios"    => "required|string|max_length[255]",
                                  "correo"      => "required|string|max_length[255]|valid_email"
                                  ];
    protected $validationMessages = ["nombres"     => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "apellidos"   => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "estudios"    => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "rutaFoto"    => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "direccion"   => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "dni"         => ["max_length"  => "El dni debe ser de 8 digitos",
                                                       "min_length"  => "El dni debe ser de 8 digitos"],
                                     "sexo"        => ["max_length"  => "El sexo debe ser de M o F",
                                                       "min_length"  => "El sexo debe ser de M o F"],
                                     "comentario"  => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "edad"        => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "correo"      => ["max_length"  => "Se ha sobrepasado el tamanio del texto",
                                                       "valid_email" => "Ingrese la cadena en formato de correo"]
                                     ];

    // Trae los alumnos de un respectivo cliente
    public function traerProfesores($cliente)
    {
        return $this->db->table("Profesores p")
                ->where("p.estado", 1)
                ->where("p.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Profesores p")
                ->where("p.estado", 1)
                ->where("p.id_cliente", $cliente)
                ->where("p.idProfesor", $id)
                ->get()->getResultArray();
    }
}
