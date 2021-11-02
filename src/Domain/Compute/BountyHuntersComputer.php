<?php

namespace App\Domain\Compute;

class BountyHuntersComputer
{
    public function computeChancesOfGettingCaptured(int $count): float|int
    {
        if ($count === 0) {
            return 0;
        }

        if ($count === 1) {
            return 1/10;
        }

        if ($count > 1) {
            $chances = [];
            $chances[] = 1/10;

            for ($i = 1; $i < $count; $i++) {
                $chances[] = 9**$i/10**($i + 1);
            }

            return array_sum($chances);
        }

        throw new \LogicException();
    }
}