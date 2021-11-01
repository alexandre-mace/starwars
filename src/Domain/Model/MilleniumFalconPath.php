<?php

namespace App\Domain\Model;

class MilleniumFalconPath
{
    private array $departures;

    /**
     * @return array
     */
    public function getDepartures(): array
    {
        return $this->departures;
    }

    /**
     * @param array $departures
     */
    public function setDepartures(array $departures): void
    {
        $this->departures = $departures;
    }
}