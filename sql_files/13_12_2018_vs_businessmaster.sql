CREATE TABLE `vs_business` (
  `business_id` int(10) NOT NULL AUTO_INCREMENT,
  `department_code` varchar(15) DEFAULT NULL,
  `department_name` varchar(75) NOT NULL,
  `cost_center` varchar(500) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
