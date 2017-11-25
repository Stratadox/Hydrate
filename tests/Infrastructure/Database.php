<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

return [
    // Initialise
    "PRAGMA foreign_keys = ON;",
    "DROP TABLE IF EXISTS `text`;",
    "DROP TABLE IF EXISTS `chapter`;",
    "DROP TABLE IF EXISTS `content`;",
    "DROP TABLE IF EXISTS `book`;",

    // Tables
    "CREATE TABLE `text` (
      `chapter_id` INTEGER NOT NULL,
      `element_number` INTEGER NOT NULL,
      `contents` TEXT NOT NULL,
      FOREIGN KEY(`chapter_id`)
        REFERENCES `chapter`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      UNIQUE (`chapter_id`, `element_number`)
    );",
    "CREATE TABLE `chapter` (
      `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
      `title` VARCHAR(512) NOT NULL
    );",
    "CREATE TABLE `content` (
      `book_id` VARCHAR(13) NOT NULL,
      `chapter_id` INTEGER NOT NULL,
      `chapter_number` INTEGER NOT NULL,
      FOREIGN KEY(`book_id`) 
        REFERENCES `book`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      FOREIGN KEY(`chapter_id`) 
        REFERENCES `chapter`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      UNIQUE (`chapter_id`, `chapter_number`)
    );",
    "CREATE TABLE `book` (
      `id` VARCHAR(13) NOT NULL PRIMARY KEY,
      `title` VARCHAR(512),
      `author_first_name` VARCHAR(255),
      `author_last_name` VARCHAR(255),
      `format` VARCHAR(255) NOT NULL,
      `chapters` INTEGER NOT NULL
    );",

    // Data
    "INSERT INTO `book` VALUES
      (9781493634149, 
      'Fruit Infused Water: 50 Quick & Easy Recipes for Delicious & Healthy Hydration',
      'Elle', 'Garner',
      'default', 2);",
    "INSERT INTO `chapter` VALUES
      (0, 'Chapter 1'),
      (1, 'Chapter 2');",
    "INSERT INTO `content` VALUES
      (9781493634149, 0, 0),
      (9781493634149, 1, 1);",
    "INSERT INTO `text` VALUES
      (0, 0, 'Content not available:'),
      (0, 1, 'Purchase book for actual content...'),
      (1, 0, 'Purchase book for actual content of chapter 2...');",
];
