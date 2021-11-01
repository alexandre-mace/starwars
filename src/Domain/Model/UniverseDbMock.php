<?php

namespace App\Domain\Model;

class UniverseDbMock
{
    public static function getMock(): array
    {
        return [
            [
                'origin' => 'Tatooine',
                'destination' => 'Dagobah',
                'travelTime' => 6
            ],
            [
                'origin' => 'Dagobah',
                'destination' => 'Endor',
                'travelTime' => 4
            ],
            [
                'origin' => 'Dagobah',
                'destination' => 'Hoth',
                'travelTime' => 1
            ],
            [
                'origin' => 'Hoth',
                'destination' => 'Endor',
                'travelTime' => 1
            ],
            [
                'origin' => 'Tatooine',
                'destination' => 'Hoth',
                'travelTime' => 6
            ]
        ];
    }
}