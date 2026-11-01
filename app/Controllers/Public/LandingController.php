<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use App\Models\TpsModel;
use App\Models\WasteRouteModel;

class LandingController extends BaseController
{
    public function index(): string
    {
        $reportModel = new ReportModel();

        return $this->render('layouts/public', 'public/landing', [
            'stats'   => $reportModel->getStats(),
            'mapLat'  => $this->setting('map_center_lat',  '-6.2884'),
            'mapLng'  => $this->setting('map_center_long', '106.7135'),
            'mapZoom' => $this->setting('map_default_zoom', '12'),
            'geoJson' => $this->setting('city_boundary_geojson', ''),
        ]);
    }

    public function map(): string
    {
        return $this->render('layouts/public', 'public/map', [
            'mapLat'  => $this->setting('map_center_lat',  '-6.2884'),
            'mapLng'  => $this->setting('map_center_long', '106.7135'),
            'mapZoom' => $this->setting('map_default_zoom', '12'),
            'geoJson' => $this->setting('city_boundary_geojson', ''),
        ]);
    }

    /** Public TPS points GeoJSON. */
    public function tpsGeojson()
    {
        $rows = (new TpsModel())->where('is_active', 1)->findAll();
        $features = array_map(static fn($r) => [
            'type' => 'Feature',
            'properties' => [
    'id' => (int)$r['id'],
    'name' => $r['name'],
    'district' => $r['district'],
    'address' => $r['address'],
    'phone' => $r['phone'],
    'operational_hours' => $r['operational_hours'],
    'type' => $r['type'],
    'description' => $r['description'],
],
            'geometry' => ['type'=>'Point','coordinates'=>[(float)$r['longitude'], (float)$r['latitude']]],
        ], $rows);

        return $this->response->setContentType('application/json')->setJSON(['type'=>'FeatureCollection','features'=>$features]);
    }

    /** Public waste-truck route GeoJSON. */
    public function routesGeojson()
    {
        $rows = (new WasteRouteModel())->where('is_active', 1)->findAll();
        $features = [];
        foreach ($rows as $r) {
            $feature = json_decode($r['geojson'], true);
            if (!$feature) continue;
            $feature['properties'] = array_merge($feature['properties'] ?? [], [
                'id'=>(int)$r['id'], 'name'=>$r['name'], 'destination_name'=>$r['destination_name'], 'description'=>$r['description'],
            ]);
            $features[] = $feature;
        }
        return $this->response->setContentType('application/json')->setJSON(['type'=>'FeatureCollection','features'=>$features]);
    }

    /** Public GeoJSON endpoint – only cleaned/active reports, no personal data. */
    public function geojson()
    {
        $reportModel = new ReportModel();
        $status      = $this->request->getGet('status');
        $collection  = $reportModel->toGeoJson($status ?: null);

        return $this->response
            ->setContentType('application/json')
            ->setJSON($collection);
    }
}
