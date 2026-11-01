<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTpsAndWasteRoutesTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>150],
            'district' => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'address' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'latitude' => ['type'=>'DECIMAL','constraint'=>'10,7'],
            'longitude' => ['type'=>'DECIMAL','constraint'=>'10,7'],
            'type' => ['type'=>'VARCHAR','constraint'=>50,'default'=>'TPS 3R'],
            'description' => ['type'=>'TEXT','null'=>true],
            'is_active' => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['latitude','longitude']);
        $this->forge->addKey('district');
        $this->forge->createTable('tps');

        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>150],
            'origin_tps_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true],
            'destination_name' => ['type'=>'VARCHAR','constraint'=>150,'null'=>true],
            'geojson' => ['type'=>'LONGTEXT'],
            'description' => ['type'=>'TEXT','null'=>true],
            'is_active' => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('origin_tps_id');
        $this->forge->addForeignKey('origin_tps_id', 'tps', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('waste_routes');
    }

    public function down(): void
    {
        $this->forge->dropTable('waste_routes', true);
        $this->forge->dropTable('tps', true);
    }
}
