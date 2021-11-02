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
                'travel_time' => 6
            ],
            [
                'origin' => 'Dagobah',
                'destination' => 'Endor',
                'travel_time' => 4
            ],
            [
                'origin' => 'Dagobah',
                'destination' => 'Hoth',
                'travel_time' => 1
            ],
            [
                'origin' => 'Hoth',
                'destination' => 'Endor',
                'travel_time' => 1
            ],
            [
                'origin' => 'Tatooine',
                'destination' => 'Hoth',
                'travel_time' => 6
            ]
        ];
    }
}