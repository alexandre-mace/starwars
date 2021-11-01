<?php

namespace App\Domain\Model;

/**
 *
 */
class BountyHunterDto
{
    /**
     * @var string
     */
    private string $planet;
    /**
     * @var int
     */
    private int $day;

    /**
     * @return string
     */
    public function getPlanet(): string
    {
        return $this->planet;
    }

    /**
     * @param string $planet
     */
    public function setPlanet(string $planet): void
    {
        $this->planet = $planet;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay(int $day): void
    {
        $this->day = $day;
    }
}