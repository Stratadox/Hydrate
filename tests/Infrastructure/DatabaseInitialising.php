<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

use SQLite3;

trait DatabaseInitialising
{
    private function newDatabaseIn(string $file) : SQLite3
    {
        $database = new SQLite3($file);
        $this->createTheTablesInThe($database);
        $this->insertTheDataIntoThe($database);
        return $database;
    }

    private function createTheTablesInThe(SQLite3 $database) : void
    {
        foreach (require('Tables.php') as $statement) {
            $database->exec($statement);
        }
    }

    private function insertTheDataIntoThe(SQLite3 $database) : void
    {
        $database->exec("INSERT INTO `book` VALUES
          (9781493634149, 
          'Fruit Infused Water: 50 Quick & Easy Recipes for Delicious & Healthy Hydration',
          'Elle', 'Garner',
          'default', 2);");
        $database->exec("INSERT INTO `chapter` VALUES
          (0, 'Chapter 1'),
          (1, 'Chapter 2');");
        $database->exec("INSERT INTO `content` VALUES
          (9781493634149, 0, 0),
          (9781493634149, 1, 1);");
        $database->exec("INSERT INTO `text` VALUES
          (0, 0, 'Content not available:'),
          (0, 1, 'Purchase book for actual content...'),
          (1, 0, 'Purchase book for actual content of chapter 2...');");
    }
}
