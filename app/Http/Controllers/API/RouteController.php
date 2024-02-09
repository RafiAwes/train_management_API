<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function findOptimalRoute(Station $source, Station $destination, string $optimizationType)
    {
        $stations = Station::all();
        $trains = Train::all();

        // Build graph representation
        $graph = $this->buildGraph($stations, $trains);

        // Find optimal route based on optimization type
        if ($optimizationType === 'time') {
            $route = $this->dijkstra($graph, $source->id, $destination->id);
        } else {
            $route = $this->bellmanFord($graph, $source->id, $destination->id);
        }

        if (!$route) {
            throw new NoRouteAvailableException("No route available from {$source->name} to {$destination->name}");
        }

        // Construct Route object
        $routeObject = new Route([
            'total_cost' => $route['cost'],
            'total_time' => $route['time'],
            'stations' => $this->constructStations($route['path'], $stations, $trains),
        ]);

        return $routeObject;
    }

    private function buildGraph($stations, $trains)
    {
        $graph = [];
        foreach ($stations as $station) {
            $graph[$station->id] = [];
            foreach ($trains as $train) {
                if ($train->source_station_id === $station->id) {
                    $graph[$station->id][$train->destination_station_id] = [
                        'cost' => $train->fare,
                        'time' => $train->travel_time,
                    ];
                }
            }
        }
        return $graph;
    }


    private function dijkstra($graph, $source, $destination)
    {
        $distances = [];
        $previous = [];
        $visited = [];
        $queue = new SplMinHeap();

        // Initialize distances and add source to the queue
        foreach ($graph as $node => $neighbors) {
            $distances[$node] = INF;
            $previous[$node] = null;
            $queue->insert($node, $distances[$node]);
        }
        $distances[$source] = 0;
        $queue->insert($source, 0);

        while (!$queue->isEmpty()) {
            $u = $queue->extract();
            $visited[$u] = true;

            if ($u === $destination) {
                
                $path = [$destination];
                $current = $destination;
                while ($previous[$current] !== null) {
                    $path[] = $previous[$current];
                    $current = $previous[$current];
                }
                $path = array_reverse($path);

                return [
                    'path' => $path,
                    'cost' => $distances[$destination],
                    'time' => $distances[$destination],
                ];
            }

            foreach ($graph[$u] as $v => $edgeData) {
                $alt = $distances[$u] + $edgeData['time'];
                if (!isset($visited[$v]) && $alt < $distances[$v]) {
                    $distances[$v] = $alt;
                    $previous[$v] = $u;
                    $queue->insert($v, $alt);
                }
            }
        }

        return null;
    }


    private function bellmanFord($graph, $source, $destination)
    {
        $distances = [];
    $previous = [];


    foreach ($graph as $node => $_) {
        $distances[$node] = INF;
        $previous[$node] = null;
    }
    $distances[$source] = 0;

    for ($i = 0; $i < count($graph) - 1; $i++) {
        foreach ($graph as $u => $neighbors) {
            foreach ($neighbors as $v => $edgeData) {
                $alt = $distances[$u] + $edgeData['cost'];
                if ($alt < $distances[$v]) {
                    $distances[$v] = $alt;
                    $previous[$v] = $u;
                }
            }
        }
    }


    foreach ($graph as $u => $neighbors) {
        foreach ($neighbors as $v => $edgeData) {
            if ($distances[$u] + $edgeData['cost'] < $distances[$v]) {
                return null; // Negative cycle detected, no valid path
            }
        }
    }


    $path = [$destination];
    $current = $destination;
    while ($previous[$current] !== null) {
        $path[] = $previous[$current];
        $current = $previous[$current];
    }
    $path = array_reverse($path);

    return [
        'path' => $path,
        'cost' => $distances[$destination],
    ];
    }

    private function constructStations($path, $stations, $trains)
    {
        $routeStations = [];
        for ($i = 0; $i < count($path) - 1; $i++) {
            $stationId = $path[$i];
            $nextStationId = $path[$i + 1];
            $trainDetails = $this->getTrainDetails($stationId, $nextStationId, $trains);

            $routeStations[] = [
                'station_id' => $stationId,
                'train_id' => $trainDetails['id'],
                'departure_time' => $trainDetails['departure_time'],
                'arrival_time' => $trainDetails['arrival_time'],
            ];
        }

        // Add last station without departure time
        $routeStations[] = [
            'station_id' => $path[count($path) - 1],
            'train_id' => null,
            'departure_time' => null,
            'arrival_time' => null,
        ];

        return $routeStations;
    }

    private function getTrainDetails($source, $destination, $trains)
    {
        foreach ($trains as $train) {
            if ($train->source_station_id === $source && $train->destination_station_id === $destination) {
                return [
                    'id' => $train->id,
                    'departure_time' => $train->departure_time
                ];
        }
    }
    }
}
