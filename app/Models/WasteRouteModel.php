<?php
namespace App\Models;

use CodeIgniter\Model;

class WasteRouteModel extends Model
{
    protected $table = 'waste_routes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'name','origin_tps_id','destination_name','geojson','description','is_active'
    ];
}
