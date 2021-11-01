<?php

namespace App\Domain\Model;

/**
 *
 */
class EmpireConfiguration
{
    /**
     * @var int
     */
    private int $countdown;
    /**
     * @var BountyHunterDto[]
     */
    private array $bountyHunters;

    /**
     * @return int
     */
    public function getCountdown(): int
    {
        return $this->countdown;
    }

    /**
     * @param int $countdown
     */
    public function setCountdown(int $countdown): void
    {
        $this->countdown = $countdown;
    }

    /**
     * @return BountyHunterDto[]
     */
    public function getBountyHunters(): array
    {
        return $this->bountyHunters;
    }

    /**
     * @param BountyHunterDto[] $bountyHunters
     */
    public function setBountyHunters(array $bountyHunters): void
    {
        $this->bountyHunters = $bountyHunters;
    }
}