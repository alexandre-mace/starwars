<?php

namespace App\Domain\Factory;

use App\Domain\Model\MilleniumFalconConfiguration;

class MilleniumFalconConfigurationFactory
{
    public function createExample1(): MilleniumFalconConfiguration
    {
        $milleniumFalconConfiguration = new MilleniumFalconConfiguration();
        $milleniumFalconConfiguration->setAutonomy(6);
        $milleniumFalconConfiguration->setDeparture('Tatooine');
        $milleniumFalconConfiguration->setArrival('Endor');

        return $milleniumFalconConfiguration;
    }
}