<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloUsuarios extends Model
{
    protected $table = "Usuarios";
    protected $primaryKey = "idUsuario";
    protected $returnType = "array";
    protected $allowedFields = ["nombres", "apellidos", "dni", "sexo", "rutaFoto", "direccion", "edad",
                                "comentario","fechaCreacion", "estado", "id_perfil",
                                "fechaElim", "correo", "id_cliente", "contra"];
    protected $validationRules = ["nombres"     => "required|string|max_length[255]",
                                  "apellidos"   => "required|string|max_length[255]",
                                  "dni"         => "required|string|max_length[8]|min_length[8]",
                                  "sexo"        => "required|string|max_length[1]|min_length[1]",
                                  "rutaFoto"    => "required|string|max_length[255]",
                                  "direccion"   => "required|string|max_length[255]",
                                  "edad"        => "required|integer|is_natural",
                                  "contra"      => "required|string|max_length[255]",
                                  "id_perfil"   => "required|integer",
                                  "comentario"  => "required|string|max_length[255]",
                                  "correo"      => "required|string|max_length[255]|valid_email"
                                  ];
    protected $validationMessages = ["nombres"     => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "apellidos"   => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "contra"      => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "rutaFoto"    => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "direccion"   => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "dni"         => ["max_length"  => "El dni debe ser de 8 digitos",
                                                       "min_length"  => "El dni debe ser de 8 digitos"],
                                     "sexo"        => ["max_length"  => "El sexo debe ser de M o F",
                                                       "min_length"  => "El sexo debe ser de M o F"],
                                     "comentario"  => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "edad"        => ["max_length"  => "Se ha sobrepasado el tamanio del texto"],
                                     "correo"      => ["max_length"  => "Se ha sobrepasado el tamanio del texto",
                                                       "valid_email" => "Ingrese la cadena en formato de correo"],
                                     "id_perfil"   => ["integer"     => "Ingrese un numero"]
                                     ];

    // Trae los usuarios de un respectivo cliente
    public function traerUsuarios($cliente)
    {
        return $this->db->table("Usuarios a")
                ->where("a.estado", 1)
                ->where("a.id_cliente", $cliente)
                ->join("Perfiles p", "a.id_perfil = p.idPerfil")
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Usuarios a")
                ->where("a.estado", 1)
                ->where("a.id_cliente", $cliente)
                ->where("a.idUsuario", $id)
                ->join("Perfiles p", "a.id_perfil = p.idPerfil")                
                ->get()->getResultArray();
    }
}
