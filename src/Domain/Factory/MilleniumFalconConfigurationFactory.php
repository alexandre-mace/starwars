<?php

namespace App\Domain\Factory;

use App\Domain\Model\EmpireConfiguration;
use App\Domain\Model\MilleniumFalconConfiguration;

class MilleniumFalconConfigurationFactory
{
    public function create(array $milleniumFalconConfigurationData): MilleniumFalconConfiguration
    {
        $milleniumFalconConfiguration = new MilleniumFalconConfiguration();
        $milleniumFalconConfiguration->setAutonomy($milleniumFalconConfigurationData['autonomy']);
        $milleniumFalconConfiguration->setDeparture($milleniumFalconConfigurationData['departure']);
        $milleniumFalconConfiguration->setArrival($milleniumFalconConfigurationData['arrival']);
        $milleniumFalconConfiguration->setRoutesDb($milleniumFalconConfigurationData['routes_db']);

        return $milleniumFalconConfiguration;
    }

    public function createExample1(): MilleniumFalconConfiguration
    {
        $milleniumFalconConfiguration = new MilleniumFalconConfiguration();
        $milleniumFalconConfiguration->setAutonomy(6);
        $milleniumFalconConfiguration->setDeparture('Tatooine');
        $milleniumFalconConfiguration->setArrival('Endor');

        return $milleniumFalconConfiguration;
    }
}