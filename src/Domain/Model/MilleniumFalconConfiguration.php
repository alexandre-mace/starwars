<?php

namespace App\Domain\Model;

class MilleniumFalconConfiguration
{
    private int $autonomy;
    private string $departure;
    private string $arrival;
    private string $routesDb;

    /**
     * @return int
     */
    public function getAutonomy(): int
    {
        return $this->autonomy;
    }

    /**
     * @param int $autonomy
     */
    public function setAutonomy(int $autonomy): void
    {
        $this->autonomy = $autonomy;
    }

    /**
     * @return string
     */
    public function getDeparture(): string
    {
        return $this->departure;
    }

    /**
     * @param string $departure
     */
    public function setDeparture(string $departure): void
    {
        $this->departure = $departure;
    }

    /**
     * @return string
     */
    public function getArrival(): string
    {
        return $this->arrival;
    }

    /**
     * @param string $arrival
     */
    public function setArrival(string $arrival): void
    {
        $this->arrival = $arrival;
    }

    /**
     * @return string
     */
    public function getRoutesDb(): string
    {
        return $this->routesDb;
    }

    /**
     * @param string $routesDb
     */
    public function setRoutesDb(string $routesDb): void
    {
        $this->routesDb = $routesDb;
    }
}