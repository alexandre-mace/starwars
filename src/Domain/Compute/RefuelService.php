<?php

namespace App\Domain\Compute;

use App\Domain\Model\MilleniumFalconConfiguration;

class RefuelService
{
    public function refuelIfNeeded(
        &$landingDays,
        MilleniumFalconConfiguration $milleniumFalconConfiguration
    )
    {
        $autonomy = $milleniumFalconConfiguration->getAutonomy();

        foreach ($landingDays as $key => $landingDay) {
            if (array_key_exists(($key - 1), $landingDays)) {
                $autonomy -= $landingDay['day'] - $landingDays[$key - 1]['day'];
            } else {
                $autonomy -= $landingDay['day'];
            }

            if ($this->isForcedToRefuel($landingDay, $landingDays, $autonomy, $key)) {
                $this->refuelAndRecalculateNextLandingDays($landingDay, $landingDays, $key);
                $autonomy = $milleniumFalconConfiguration->getAutonomy();
            }
        }
    }

    private function isForcedToRefuel($landingDay, $landingDays, $autonomy, $index): bool
    {
        if (!array_key_exists($index + 1, $landingDays)) {
            return false;
        }

        return $autonomy < ($landingDays[$index + 1]['day'] - $landingDay['day']);
    }

    private function refuelAndRecalculateNextLandingDays($landingDay, &$landingDays, $index)
    {
        if (array_key_exists('isRefuelingDay', $landingDay)) {
            return;
        }

        for ($i = $index + 1; $i < \count($landingDays); $i++) {
            $landingDays[$i] = [
                'day' => $landingDays[$i]['day'] + 1,
                'planet' => $landingDays[$i]['planet']
            ];
        }

        array_splice($landingDays , $index + 1, 0, [[
            'day' => $landingDay['day'] + 1,
            'planet' => $landingDay['planet'],
            'isRefuelingDay' => true
        ]]);
    }
}