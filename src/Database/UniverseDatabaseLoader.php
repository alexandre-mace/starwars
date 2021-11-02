<?php

namespace App\Database;

use App\Domain\Model\MilleniumFalconConfiguration;

class UniverseDatabaseLoader
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function load(MilleniumFalconConfiguration $milleniumFalconConfiguration): array
    {
        $db = new SqliteDatabase($this->projectDir . '/public/' . $milleniumFalconConfiguration->getRoutesDb());

        $result = $db->query('SELECT * FROM ROUTES');
        $rows = [];
        while ($row = $result->fetchArray())
        {
            $rows[] = $row;
        }

        return $rows;
    }
}