<?php
namespace App\Models;

use CodeIgniter\Model;

class TpsModel extends Model
{
    protected $table = 'tps';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'name','district','address','latitude','longitude','type','description','is_active'
    ];
}
