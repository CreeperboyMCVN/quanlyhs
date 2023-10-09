<?php

$db = db_connect();

$db->query('CREATE TABLE IF NOT EXISTS `qlhs_users` '
        . '(`id` INT NOT NULL AUTO_INCREMENT,'
        . '`username` VARCHAR(256) NOT NULL,'
        . '`password` VARCHAR(256) NOT NULL,'
        . '`secert` VARCHAR(256) NOT NULL,'
        . '`permission` TEXT NOT NULL,'
        . 'PRIMARY KEY (`id`))');

$pw = generatePassword('abc123!@#');
$secert = 'a';
$db->query(
        "INSERT INTO `qlhs_users` (`username`, `password`, `secert`, `permission`) VALUES ('admin', \"$pw\", \"$secert\", '*')" 
);

