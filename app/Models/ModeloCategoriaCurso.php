<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloCategoriaCurso extends Model
{
    protected $table = "CategoriaCurso";
    protected $primaryKey = "idCategoriaCurso";
    protected $returnType = "array";
    protected $allowedFields = ["categoria","fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["categoria"  => "required|string|max_length[255]"];
    protected $validationMessages = ["categoria"   => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];
}
