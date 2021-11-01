<?php

namespace App\Domain\Compute;

use App\Domain\Model\BountyHunterDto;
use App\Domain\Model\EmpireConfiguration;
use App\Domain\Model\MilleniumFalconConfiguration;

class OddsComputer
{
    private UniversePathFinder $universePathFinder;
    private FeasablePathFinder $feasablePathFinder;

    public function __construct(UniversePathFinder $universePathFinder, FeasablePathFinder $feasablePathFinder)
    {
        $this->universePathFinder = $universePathFinder;
        $this->feasablePathFinder = $feasablePathFinder;
    }

    public function compute(
        MilleniumFalconConfiguration $milleniumFalconConfiguration,
        EmpireConfiguration $empireConfiguration,
        $universeDb
    ): int
    {
        $universePaths = $this->universePathFinder->find($milleniumFalconConfiguration, $universeDb);
        $feasablePaths = $this->feasablePathFinder->find(
            $universePaths,
            $milleniumFalconConfiguration->getAutonomy(),
            $empireConfiguration->getCountdown()
        );

        if (\count($feasablePaths) === 0) {
            return 0;
        }

        $odds = array_map(function ($feasablePath) use ($milleniumFalconConfiguration, $empireConfiguration) {
            return $this->computeSinglePathOdds($feasablePath, $milleniumFalconConfiguration, $empireConfiguration);
        }, $feasablePaths);

        return max($odds);
    }

    private function computeSinglePathOdds(
        $feasablePath,
        MilleniumFalconConfiguration $milleniumFalconConfiguration,
        EmpireConfiguration $empireConfiguration
    )
    {
        $extraDays = $empireConfiguration->getCountdown() - $feasablePath['minimumDays'];
        $landingDays = $this->getLandingDays($feasablePath['path'], 0);
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

        $landingDaysWithBountyHunters = $this->getLandingDaysWithBountyHunters($landingDays, $empireConfiguration);
        if ($extraDays > 0) {
            foreach ($landingDaysWithBountyHunters as $landingDayWithBountyHunters) {
                if ($extraDays === 0) {
                    break;
                }
                if ($this->canDodge($landingDayWithBountyHunters, $landingDays)) {
                    $extraDays--;
                    $this->waitAndRecalculateNextLandingDays(
                        $landingDayWithBountyHunters,
                        $landingDays,
                        array_search($landingDayWithBountyHunters, $landingDays)
                    );
                }
            }

            $landingDaysWithBountyHunters = $this->getLandingDaysWithBountyHunters($landingDays, $empireConfiguration);
        }

        return (1 - $this->getBountyHuntersChancesOfGettingCaptured(\count($landingDaysWithBountyHunters))) * 100;
    }

    private function canDodge($dayToDodge, $landingDays): bool
    {
        return in_array($dayToDodge, $landingDays, true) && $landingDays[0] !== $dayToDodge;
    }

    private function getLandingDays($path, $delay = 0): array
    {
        $alreadyTraveleddDays = 0;

        $landingDays = array_map(function ($pathNode) use ($delay, &$alreadyTraveleddDays) {
            if ($alreadyTraveleddDays === 0) {
                $alreadyTraveleddDays+= $delay;
            }
            $landingDay = $pathNode['travelTime'] + $alreadyTraveleddDays;
            $alreadyTraveleddDays += $pathNode['travelTime'];
            return [
                'day' => $landingDay,
                'planet' => $pathNode['destination']
            ];
        }, $path);

        $this->addDepartureAsALandingDay($landingDays, $path);
        return $landingDays;
    }

    private function getNumberOfRefuelingDays($path, int $autonomy, int $countdown)
    {
        return floor(array_sum(array_map(function ($pathNode) {
            return $pathNode['travelTime'];
        }, $path)) / $autonomy);
    }

    private function addDepartureAsALandingDay(&$landingDays, $path)
    {
        array_unshift($landingDays , ['day' => 0, 'planet' => $path[0]['origin']]);
    }

    private function getLandingDaysWithBountyHunters(array $landingDays, EmpireConfiguration $empireConfiguration): array
    {
        $bountyHunters = $empireConfiguration->getBountyHunters();

        return array_filter($landingDays, function ($landingDay) use ($bountyHunters) {
            return array_filter($bountyHunters, function (BountyHunterDto $bountyHunterDto) use ($landingDay) {
                return $bountyHunterDto->getDay() === $landingDay['day'] && $bountyHunterDto->getPlanet() === $landingDay['planet'];
            });
        });
    }

    private function getBountyHuntersChancesOfGettingCaptured(int $count): float|int
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