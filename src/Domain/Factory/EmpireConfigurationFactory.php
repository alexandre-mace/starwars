<?php

namespace App\Domain\Factory;

use App\Domain\Model\BountyHunterDto;
use App\Domain\Model\EmpireConfiguration;

class EmpireConfigurationFactory
{
    public function create(array $empireConfigurationData): EmpireConfiguration
    {
        $empireConfiguration = new EmpireConfiguration();
        $empireConfiguration->setCountdown($empireConfigurationData['countdown']);

        return $this->createBountyHunters($empireConfiguration, $empireConfigurationData['bounty_hunters']);
    }

    /**
     * @param EmpireConfiguration $empireConfiguration
     * @param array $bountyHuntersData
     * @return EmpireConfiguration
     */
    private function createBountyHunters(EmpireConfiguration $empireConfiguration, array $bountyHuntersData): EmpireConfiguration
    {
        $bountyHunters = [];
        foreach ($bountyHuntersData as $bountyHuntersDatum) {
            $bountyHunterDto = new BountyHunterDto();
            $bountyHunterDto->setPlanet($bountyHuntersDatum['planet']);
            $bountyHunterDto->setDay($bountyHuntersDatum['day']);
            $bountyHunters[] = $bountyHunterDto;
        }

        $empireConfiguration->setBountyHunters($bountyHunters);

        return $empireConfiguration;
    }
}