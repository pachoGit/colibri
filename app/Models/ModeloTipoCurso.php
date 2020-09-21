<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloTipoCurso extends Model
{
    protected $table = "TipoCurso";
    protected $primaryKey = "idTipoCurso";
    protected $returnType = "array";
    protected $allowedFields = ["tipo","fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["tipo"       => "required|string|max_length[255]",
                                  "id_cliente" => "required|integer"];
    protected $validationMessages = ["tipo"        => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_cliente"  => ["integer"    => "Ingrese un numero"]
                                     ];


}
