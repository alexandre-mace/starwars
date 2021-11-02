<?php

namespace App\Domain\Compute;

class WaitingService
{
    public function tryToWait($landingDaysWithBountyHunters, $extraDays, &$landingDays)
    {
        foreach ($landingDaysWithBountyHunters as $landingDayWithBountyHunters) {
            if ($extraDays === 0) {
                break;
            }
            if ($this->canWait($landingDayWithBountyHunters, $landingDays)) {
                $extraDays--;
                $this->waitAndRecalculateNextLandingDays(
                    $landingDayWithBountyHunters,
                    $landingDays,
                    array_search($landingDayWithBountyHunters, $landingDays)
                );
            }
        }
    }

    private function canWait($dayToWait, $landingDays): bool
    {
        return in_array($dayToWait, $landingDays, true) && $landingDays[0] !== $dayToWait;
    }

    private function waitAndRecalculateNextLandingDays($landingDay, &$landingDays, $index)
    {
        if (array_key_exists('isWaitingDay', $landingDay)) {
            return;
        }

        for ($i = $index; $i < \count($landingDays); $i++) {
            $landingDays[$i] = [
                'day' => $landingDays[$i]['day'] + 1,
                'planet' => $landingDays[$i]['planet']
            ];
        }

        array_splice($landingDays , $index, 0, [[
            'day' => $landingDay['day'],
            'planet' => $landingDays[$index - 1]['planet'],
            'isWaitingDay' => true
        ]]);
    }
}