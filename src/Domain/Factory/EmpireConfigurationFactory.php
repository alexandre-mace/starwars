<?php

namespace App\Domain\Factory;

use App\Domain\Model\BountyHunterDto;
use App\Domain\Model\EmpireConfiguration;

class EmpireConfigurationFactory
{
    public function createExample1(): EmpireConfiguration
    {
        $empireConfiguration = new EmpireConfiguration();
        $empireConfiguration->setCountdown(7);

        return $this->createBountyHunters($empireConfiguration);
    }

    public function createExample2(): EmpireConfiguration
    {
        $empireConfiguration = new EmpireConfiguration();
        $empireConfiguration->setCountdown(8);

        return $this->createBountyHunters($empireConfiguration);
    }

    public function createExample3(): EmpireConfiguration
    {
        $empireConfiguration = new EmpireConfiguration();
        $empireConfiguration->setCountdown(9);

        return $this->createBountyHunters($empireConfiguration);
    }

    public function createExample4(): EmpireConfiguration
    {
        $empireConfiguration = new EmpireConfiguration();
        $empireConfiguration->setCountdown(10);

        return $this->createBountyHunters($empireConfiguration);
    }

    /**
     * @param EmpireConfiguration $empireConfiguration
     * @return EmpireConfiguration
     */
    private function createBountyHunters(EmpireConfiguration $empireConfiguration): EmpireConfiguration
    {
        $bountyHunterDto = new BountyHunterDto();
        $bountyHunterDto->setPlanet('Hoth');
        $bountyHunterDto->setDay(6);

        $bountyHunterDto2 = new BountyHunterDto();
        $bountyHunterDto2->setPlanet('Hoth');
        $bountyHunterDto2->setDay(7);

        $bountyHunterDto3 = new BountyHunterDto();
        $bountyHunterDto3->setPlanet('Hoth');
        $bountyHunterDto3->setDay(8);

        $empireConfiguration->setBountyHunters([
            $bountyHunterDto,
            $bountyHunterDto2,
            $bountyHunterDto3,
        ]);

        return $empireConfiguration;
    }
}