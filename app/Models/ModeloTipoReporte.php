<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloTipoReporte extends Model
{
    protected $table = "TipoReporte";
    protected $primaryKey = "idTipoReporte";
    protected $returnType = "array";
    protected $allowedFields = ["reporte", "fechaCreacion", "fechaElim", "id_cliente", "estado"];
    protected $validationRules = ["reporte"  => "required|string|max_length[255]"];

    protected $validationMessages = ["reporte" => ["max_length" => "Se ha sobrepasado el tamanio del texto"]];

    // Trae los grados de un respectivo cliente
    public function traerTipoReporte($cliente)
    {
        return $this->db->table("TipoReporte g")
                ->where("g.estado", 1)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }

    public function traerPorId($id, $cliente)
    {
        return $this->db->table("TipoReporte g")
                ->where("g.estado", 1)
                ->where("g.idTipoReporte", $id)
                ->where("g.id_cliente", $cliente)
                ->get()->getResultArray();
    }
}
