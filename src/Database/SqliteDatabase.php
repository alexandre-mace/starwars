<?php

namespace App\Database;

use SQLite3;

class SqliteDatabase extends SQLite3
{
    function __construct($databasePath)
    {
        $this->open($databasePath);
    }
}