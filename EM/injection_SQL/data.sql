
CREATE DATABASE IF NOT EXISTS `db_blog`
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


use `db_blog` 

CREATE TABLE `users` (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(20),
    `password` CHAR(5),
    `email` VARCHAR(20),
    UNIQUE KEY (`email`)
);


INSERT INTO `users`
(`username`,`password` ,`email`)
VALUES
    ( 'alan', '12345', 'alan@alan.fr' ),
    ( 'alice', '12345', 'alice@alice.fr' )
;