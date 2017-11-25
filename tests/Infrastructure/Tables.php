<?php

namespace Stratadox\Hydrate\Test\Infrastructure;

return [
    "PRAGMA foreign_keys = ON;",
    "DROP TABLE IF EXISTS `text`;",
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
    "DROP TABLE IF EXISTS `chapter`;",
    "CREATE TABLE `chapter` (
      `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
      `title` VARCHAR(512) NOT NULL
    );",
    "DROP TABLE IF EXISTS `content`;",
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
    "DROP TABLE IF EXISTS `book`;",
    "CREATE TABLE `book` (
      `id` VARCHAR(13) NOT NULL PRIMARY KEY,
      `title` VARCHAR(512),
      `author_first_name` VARCHAR(255),
      `author_last_name` VARCHAR(255),
      `format` VARCHAR(255) NOT NULL,
      `chapters` INTEGER NOT NULL
    );",
];
