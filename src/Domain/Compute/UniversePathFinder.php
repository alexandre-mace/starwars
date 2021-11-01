<?php

namespace App\Domain\Compute;

use App\Domain\Model\MilleniumFalconConfiguration;

class UniversePathFinder
{
    public function find(MilleniumFalconConfiguration $milleniumFalconConfiguration, $universeData): array
    {
        $departure = $milleniumFalconConfiguration->getDeparture();
        $arrival = $milleniumFalconConfiguration->getArrival();

        $nestedPaths = $this->buildPaths($universeData, $departure, $arrival);

        $flattenedPaths = [];
        $numberOfCompletedPaths = 0;
        $this->flattenPaths($nestedPaths, $departure, $arrival, $flattenedPaths, $numberOfCompletedPaths);

        return $flattenedPaths;
    }

    private function flattenPaths($nestedPaths, $departure, $destination, &$flattenedPaths, &$numberOfCompletedPaths)
    {
        foreach ($nestedPaths as $nestedPath) {
            $path = $nestedPath;
            unset($path['next']);

            if ($nestedPath['destination'] === $destination) {
                $pathPrefix = $flattenedPaths[$numberOfCompletedPaths];
                $flattenedPaths[$numberOfCompletedPaths][] = $path;
                $numberOfCompletedPaths++;
            } else {
                if (!array_key_exists($numberOfCompletedPaths, $flattenedPaths) && $path['origin'] !== $departure) {
                    $flattenedPaths[$numberOfCompletedPaths] = $pathPrefix;
                }
                $flattenedPaths[$numberOfCompletedPaths][] = $path;
                $this->flattenPaths($nestedPath['next'], $departure, $destination, $flattenedPaths, $numberOfCompletedPaths);
            }
        }

        return $flattenedPaths;
    }

    private function buildPaths(array $universePaths, $origin, $destination): array
    {
        $paths = [];

        foreach ($universePaths as $universePath) {
            if ($universePath['origin'] === $origin && $universePath['origin'] !== $destination) {
                $nextStep = $this->buildPaths($universePaths, $universePath['destination'], $destination);
                if ($nextStep) {
                    $universePath['next'] = $nextStep;
                }
                $paths[] = $universePath;
            }
        }

        return $paths;
    }
}