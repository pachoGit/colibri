<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloSolicitudes extends Model
{
    protected $table = "Solicitudes";
    protected $primaryKey = "idSolicitud";
    protected $returnType = "array";
    protected $allowedFields = ["nombrePadre", "correoPadre", "telefono", "nombreHijo",
                                "nivel", "id_cliente", "estado", "fechaCreacion"];
    protected $validationRules = ["nombrePadre"   => "required|string|max_length[255]",
    							  "correoPadre"   => "required|string|max_length[255]|valid_email",
    							  "telefono"      => "required|string|max_length[9]|min_length[9]",
    							  "nombreHijo"    => "required|string|max_length[255]",    							  
    							  "nivel"    	  => "required|string|max_length[255]",
                                  "id_cliente"    => "required|integer"
                                  ];
    protected $validationMessages = ["nombrePadre"   => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
    								 "correoPadre"   => ["max_length" => "Se ha sobrepasado el tamanio del texto",
                                                       "valid_email"  => "Ingrese la cadena en formato de correo"],
                                     "telefono"    	 => ["max_length" => "El telefono debe tener 9 digitos"],
    								 "nombreHijo"    => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "nivel" 		 => ["max_length" => "Se ha sobrepasado el tamanio del texto"],
                                     "id_cliente"    => ["integer" 	  => "Ingrese a un al cliente"]
                                     ];

    public function traerSolicitudes($cliente)
    {
        return $this->db->table("Solicitudes s")
                ->where("s.estado", 1)
                ->where("s.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("Solicitudes s")
                ->where("s.estado", 1)
                ->where("s.idSolicitud", $id)
                ->where("s.id_cliente", $cliente)
                ->get()->getResultArray();
    }  
    
    
}