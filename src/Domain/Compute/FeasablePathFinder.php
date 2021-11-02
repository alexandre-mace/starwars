<?php

namespace App\Domain\Compute;

class FeasablePathFinder
{
    public function find($universePaths, $autonomy, $countdown): array
    {
        $pathsTravelTime = array_map(function ($path) {
            return array_sum(array_map(function ($pathNode) {
                return $pathNode['travel_time'];
            }, $path));
        }, $universePaths);

        $pathsTravelTimeWithRefuel = array_map(function ($travelTime) use ($autonomy) {
            if ($travelTime > $autonomy) {
                return $travelTime + 1;
            }

            return $travelTime;
        }, $pathsTravelTime);

        $feasablePathsTravelTime = array_filter($pathsTravelTimeWithRefuel, function ($travelTime) use($countdown) {
            return $travelTime <= $countdown;
        });

        $feasablePaths = [];

        foreach ($feasablePathsTravelTime as $key => $feasablePathTravelTime) {
            $feasablePaths[] = [
                'minimumDays' => $feasablePathTravelTime,
                'path' => $universePaths[$key]
            ];
        }

        return $feasablePaths;
    }
}