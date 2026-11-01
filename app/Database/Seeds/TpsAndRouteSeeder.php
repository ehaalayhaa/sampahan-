<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Demo data layer for TPS 3R and waste-truck routes.
 *
 * The project is structured to accept official DLH data. The coordinates below
 * are demonstration points and must be replaced by verified field/DLH data
 * before being presented as official locations or official truck routes.
 */
class TpsAndRouteSeeder extends Seeder
{
    public function run(): void
    {
        $tpsTable = $this->db->table('tps');
        $routeTable = $this->db->table('waste_routes');

        if ($tpsTable->countAllResults() > 0) {
            echo "  ! TPS data already exists; skipped demo seed.\n";
            return;
        }

        $rows = [
            ['name'=>'TPS 3R Setu','district'=>'Setu','address'=>'Wilayah Kecamatan Setu, Tangerang Selatan','latitude'=>-6.3530000,'longitude'=>106.6810000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Serpong','district'=>'Serpong','address'=>'Wilayah Kecamatan Serpong, Tangerang Selatan','latitude'=>-6.3180000,'longitude'=>106.6640000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Serpong Utara','district'=>'Serpong Utara','address'=>'Wilayah Kecamatan Serpong Utara, Tangerang Selatan','latitude'=>-6.2710000,'longitude'=>106.6710000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Pamulang','district'=>'Pamulang','address'=>'Wilayah Kecamatan Pamulang, Tangerang Selatan','latitude'=>-6.3430000,'longitude'=>106.7380000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Ciputat','district'=>'Ciputat','address'=>'Wilayah Kecamatan Ciputat, Tangerang Selatan','latitude'=>-6.3150000,'longitude'=>106.7500000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Ciputat Timur','district'=>'Ciputat Timur','address'=>'Wilayah Kecamatan Ciputat Timur, Tangerang Selatan','latitude'=>-6.2960000,'longitude'=>106.7570000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
            ['name'=>'TPS 3R Pondok Aren','district'=>'Pondok Aren','address'=>'Wilayah Kecamatan Pondok Aren, Tangerang Selatan','latitude'=>-6.2760000,'longitude'=>106.7160000,'type'=>'TPS 3R','description'=>'Titik demo untuk layer informasi TPS.'],
        ];

        $tpsTable->insertBatch($rows);
        $tps = $tpsTable->orderBy('id','ASC')->get()->getResultArray();

        // Demonstration route only: polyline from several demo TPS points toward
        // the Cipeucang area. It is NOT an official truck route.
        $coords = [];
        foreach ($tps as $row) {
            $coords[] = [(float)$row['longitude'], (float)$row['latitude']];
        }
        $coords[] = [106.6630000, -6.3020000]; // Cipeucang-area demo endpoint

        $geojson = json_encode([
            'type'=>'Feature',
            'properties'=>['name'=>'Rute Pengangkutan Sampah (Demo)'],
            'geometry'=>['type'=>'LineString','coordinates'=>$coords],
        ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        $routeTable->insert([
            'name'=>'Rute Pengangkutan Sampah (Demo)',
            'origin_tps_id'=>$tps[0]['id'] ?? null,
            'destination_name'=>'Area TPA Cipeucang (endpoint demo)',
            'geojson'=>$geojson,
            'description'=>'Polyline demo untuk pengembangan SIG. Ganti dengan rute jalan truk yang telah diverifikasi dari DLH.',
            'is_active'=>1,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);

        echo "  ✓ Inserted ".count($rows)." demo TPS points and 1 demo waste route.\n";
    }
}
