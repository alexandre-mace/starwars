<?php

namespace App\Domain\Compute;

use App\Domain\Model\EmpireConfiguration;
use App\Domain\Model\MilleniumFalconConfiguration;

class OddsComputer
{
    private UniversePathFinder $universePathFinder;
    private FeasablePathFinder $feasablePathFinder;
    private BountyHuntersComputer $bountyHuntersComputer;
    private RefuelService $refuelService;
    private WaitingService $waitingService;
    private LandingDaysService $landingDaysService;

    public function __construct(
        UniversePathFinder $universePathFinder,
        FeasablePathFinder $feasablePathFinder,
        BountyHuntersComputer $bountyHuntersComputer,
        RefuelService $refuelService,
        WaitingService $waitingService,
        LandingDaysService $landingDaysService
    )
    {
        $this->universePathFinder = $universePathFinder;
        $this->feasablePathFinder = $feasablePathFinder;
        $this->bountyHuntersComputer = $bountyHuntersComputer;
        $this->refuelService = $refuelService;
        $this->waitingService = $waitingService;
        $this->landingDaysService = $landingDaysService;
    }

    public function compute(
        MilleniumFalconConfiguration $milleniumFalconConfiguration,
        EmpireConfiguration $empireConfiguration,
        $universeRoutes
    ): int
    {
        $universePaths = $this->universePathFinder->find($milleniumFalconConfiguration, $universeRoutes);
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
    ): float|int
    {
        $extraDays = $empireConfiguration->getCountdown() - $feasablePath['minimumDays'];
        $landingDays = $this->landingDaysService->getLandingDays($feasablePath['path'], 0);

        $this->refuelService->refuelIfNeeded($landingDays, $milleniumFalconConfiguration);
        $landingDaysWithBountyHunters = $this->landingDaysService->getLandingDaysWithBountyHunters($landingDays, $empireConfiguration);

        if ($extraDays > 0) {
            $this->waitingService->tryToWait($landingDaysWithBountyHunters, $extraDays, $landingDays);
            $landingDaysWithBountyHunters = $this->landingDaysService->getLandingDaysWithBountyHunters($landingDays, $empireConfiguration);
        }

        return (1 - $this->bountyHuntersComputer->computeChancesOfGettingCaptured(\count($landingDaysWithBountyHunters))) * 100;
    }
}