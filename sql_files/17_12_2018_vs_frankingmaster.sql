CREATE TABLE `vs_franking` (
  `franking_id` int(10) NOT NULL AUTO_INCREMENT,
  `f_month` varchar(15) DEFAULT NULL,
  `f_year` varchar(75) DEFAULT NULL,
  `f_value` varchar(500) DEFAULT NULL,
  `f_locationids` varchar(500) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`franking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
