<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloNaturalezaCurso extends Model
{
    protected $table = "NaturalezaCurso";
    protected $primaryKey = "idNaturaleza";
    protected $returnType = "array";
    protected $allowedFields = ["naturaleza","fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["naturaleza" => "required|string|max_length[255]",
                                  "id_cliente" => "required|integer"];
    protected $validationMessages = ["naturaleza"  => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_cliente"  => ["integer"    => "Ingrese un numero"]
                                     ];


}
