CREATE TABLE `vs_courier_request` (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_code` varchar(100) NOT NULL,
  `from_id` int(11) NOT NULL,
  `req_emp_id` int(10) NOT NULL,
  `req_emp_token` varchar(15) NOT NULL,
  `req_mod_of_delivery` varchar(15) NOT NULL,
  `req_courier` varchar(15) DEFAULT NULL,
  `req_emp_name` varchar(100) NOT NULL,
  `req_emp_dept` varchar(100) NOT NULL,
  `req_emp_extension` varchar(100) NOT NULL,
  `req_receiever_emp_token` varchar(15) NOT NULL,
  `req_receiever_emp_name` varchar(100) NOT NULL,
  `req_receiever_emp_address` varchar(100) NOT NULL,
  `req_receiever_emp_pincode` varchar(50) NOT NULL,
  `req_receiever_type` varchar(50) NOT NULL,
  `req_receiever_telephone` varchar(50) NOT NULL,
  `req_receiever_remarks` varchar(500) DEFAULT NULL,
  `req_status` varchar(25) DEFAULT NULL,
  `req_unit` varchar(25) DEFAULT NULL,
  `req_weight` varchar(25) DEFAULT NULL,
  `req_agency` varchar(25) DEFAULT NULL,
  `req_fee` varchar(25) DEFAULT NULL,
  `req_datetime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`req_id`),
  KEY `emp_id` (`req_emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `vs_courier_request` (`req_id`, `tracking_code`, `from_id`, `req_emp_id`, `req_emp_token`, `req_mod_of_delivery`, `req_courier`, `req_emp_name`, `req_emp_dept`, `req_emp_extension`, `req_receiever_emp_token`, `req_receiever_emp_name`, `req_receiever_emp_address`, `req_receiever_emp_pincode`, `req_receiever_type`, `req_receiever_telephone`, `req_receiever_remarks`, `req_status`, `req_unit`, `req_weight`, `req_agency`, `req_fee`, `req_datetime`) VALUES
(9,	'',	99,	1,	'10188',	'C',	'International',	'Umesh Agashe',	'RESEARCH & DEVELOPMENT',	'TWO WHEELERS',	'',	'Manas',	'34 wall street block',	'M7D578',	'Internal',	'9890663235',	'test',	'Submitted',	'kg',	'5',	'1',	'300',	'2019-01-01 22:18:10'),
(10,	'',	99,	41,	'13031',	'U',	NULL,	'Satendra Pal',	'ENTERPRISE, MFG & CORP FNS',	'INFORMATION TECHNOLOGY',	'',	'Manas',	'34 wall street block',	'M7D578',	'Internal',	'9890663235',	'test',	'Submitted',	'gram',	'5',	NULL,	'500',	'2019-01-02 04:17:52');
