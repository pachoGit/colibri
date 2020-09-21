<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloRegistros extends Model
{
    protected $table = "Registros";
    protected $primaryKey = "idRegistro";
    protected $returnType = "array";
    protected $allowedFields = ["nombres", "apellidos", "email", "cliente_id",
                                "llave_secreta", "fechaCreacion", "fechaElim", "estado"];
    protected $validationRules = ["nombres"       => "required|string|max_length[255]",
                                  "apellidos"     => "required|string|max_length[255]",
                                  "email"         => "required|valid_email|string"
                                  ];
    protected $validationMessages = ["email"         => ["valid_email" => "El correo no esta correcto"]];
}
