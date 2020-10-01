CREATE TABLE `vs_pickuppoint` (
  `pickup_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_locationid` varchar(500) DEFAULT NULL,
  `p_pickuppoint` varchar(500) DEFAULT NULL,
  `p_desc` longtext DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pickup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;