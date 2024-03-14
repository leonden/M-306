CREATE DATABASE `taskmaster_prod`;

USE `taskmaster_prod`;

CREATE TABLE `project` (
  `project_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `project_lead` varchar(100) NOT NULL,
  PRIMARY KEY (`project_id`)
)

CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  PRIMARY KEY (`user_id`)
)

insert into `user` (`username`, `firstname`, `lastname`, `password`) values ('admin', 'admin', 'admin', 'admin');
insert into `user` (`username`, `firstname`, `lastname`, `password`) values ('test', 'test', 'test', 'test');
