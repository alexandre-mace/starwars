<?php

namespace App\Domain\Compute;

use App\Domain\Model\BountyHunterDto;
use App\Domain\Model\EmpireConfiguration;

class LandingDaysService
{
    public function getLandingDays($path, $delay = 0): array
    {
        $alreadyTraveleddDays = 0;

        $landingDays = array_map(function ($pathNode) use ($delay, &$alreadyTraveleddDays) {
            if ($alreadyTraveleddDays === 0) {
                $alreadyTraveleddDays+= $delay;
            }
            $landingDay = $pathNode['travel_time'] + $alreadyTraveleddDays;
            $alreadyTraveleddDays += $pathNode['travel_time'];
            return [
                'day' => $landingDay,
                'planet' => $pathNode['destination']
            ];
        }, $path);

        $this->addDepartureAsALandingDay($landingDays, $path);
        return $landingDays;
    }

    public function getLandingDaysWithBountyHunters(array $landingDays, EmpireConfiguration $empireConfiguration): array
    {
        $bountyHunters = $empireConfiguration->getBountyHunters();

        return array_filter($landingDays, function ($landingDay) use ($bountyHunters) {
            return array_filter($bountyHunters, function (BountyHunterDto $bountyHunterDto) use ($landingDay) {
                return $bountyHunterDto->getDay() === $landingDay['day'] && $bountyHunterDto->getPlanet() === $landingDay['planet'];
            });
        });
    }

    private function addDepartureAsALandingDay(&$landingDays, $path)
    {
        array_unshift($landingDays , ['day' => 0, 'planet' => $path[0]['origin']]);
    }
}