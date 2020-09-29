CREATE TABLE `appointment` (
  `appointmentid` int(9) NOT NULL AUTO_INCREMENT,
  `userid` int(9) NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `apptDate` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `response` datetime DEFAULT NULL,
  `max` int(6) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `responses` (
  `registrationid` int(9) NOT NULL  AUTO_INCREMENT,
  `userid` int(9) NOT NULL,
  `appointmentid` int(9) NOT NULL,
  `response` int(5) NOT NULL,
  `people` int(5) NOT NULL DEFAULT '1',
  `responsed` datetime NOT NULL,
  `changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `userid` int(9) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lang` varchar(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentid`);

ALTER TABLE `responses`
  ADD PRIMARY KEY (`registrationid`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);
