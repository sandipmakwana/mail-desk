-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2019 at 08:15 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maildesk_jan21`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddErrorLog` (IN `logTypeid` TINYINT, IN `logFileName` VARCHAR(250), IN `logDescription` VARCHAR(8000), IN `logIPAddress` VARCHAR(50), IN `logSource` VARCHAR(8000), IN `logStackTrace` VARCHAR(8000), IN `logDate` DATETIME)  BEGIN
	INSERT INTO `ErrorLog`(logTypeID,
			logFileName, 
			logDescription, 
			logIPAddress, 
			logSource, 
			logStackTrace, 
			logDate)
	VALUES(logTypeID, 
			logFileName, 
			logDescription, 
			logIPAddress, 
			logSource, 
			logStackTrace, 
			logDate);
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_esc_hr_log` (INOUT `_esc_hr_logid` INT, IN `_esc_for_reqid` INT, IN `_esc_subject` VARCHAR(100), IN `_esc_to_emailids` VARCHAR(400), IN `_esc_cc_emailids` VARCHAR(400), IN `_esc_no` TINYINT, IN `_esc_triggereddate` DATETIME, IN `_esc_body_text` VARCHAR(8000), IN `_esc_is_sent` BIT, IN `_esc_sent_date` DATETIME)  BEGIN
IF (_esc_hr_logid = 0) THEN
	INSERT INTO `vs_esc_hr_log`
	(`esc_for_reqid`, `esc_subject`, `esc_to_emailids`, `esc_cc_emailids`, `esc_no`, `esc_triggereddate`)
	VALUES
	(_esc_for_reqid, _esc_subject, _esc_to_emailids, _esc_cc_emailids, _esc_no, _esc_triggereddate);
	
	SET _esc_hr_logid = LAST_INSERT_ID();
	
ELSE
	UPDATE `vs_esc_hr_log`
	SET `esc_body_text` = _esc_body_text
		, `esc_is_sent` = _esc_is_sent
		, `esc_sent_date` = _esc_sent_date
	WHERE `esc_hr_logid` = _esc_hr_logid;
	
	
	IF ( (_esc_is_sent = 1) AND 
		( EXISTS(SELECT 1 FROM `vs_request` 
			WHERE `req_id` = _esc_for_reqid 
				AND `req_first_hr_mail_sent` IS NULL) ) ) THEN
		UPDATE `vs_request`
		SET `req_first_hr_mail_sent` = _esc_triggereddate
		WHERE `req_id` = _esc_for_reqid;
	END IF;
end IF;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_esc_vendor_log` (INOUT `_esc_vendor_logid` INT, IN `_esc_for_reqid` INT, IN `_esc_subject` VARCHAR(100), IN `_esc_to_emailids` VARCHAR(400), IN `_esc_cc_emailids` VARCHAR(400), IN `_esc_no` TINYINT, IN `_esc_triggereddate` DATETIME, IN `_esc_body_text` VARCHAR(8000), IN `_esc_is_sent` BIT, IN `_esc_sent_date` DATETIME)  BEGIN
IF (_esc_vendor_logid = 0) THEN
	INSERT INTO `vs_esc_vendor_log`
	(`esc_for_reqid`, `esc_subject`, `esc_to_emailids`, `esc_cc_emailids`, `esc_no`, `esc_triggereddate`)
	VALUES
	(_esc_for_reqid, _esc_subject, _esc_to_emailids, _esc_cc_emailids, _esc_no, _esc_triggereddate);
	
	SET _esc_vendor_logid = LAST_INSERT_ID();
	
ELSE
	UPDATE `vs_esc_vendor_log`
	SET `esc_body_text` = _esc_body_text
		, `esc_is_sent` = _esc_is_sent
		, `esc_sent_date` = _esc_sent_date
	WHERE `esc_vendor_logid` = _esc_vendor_logid;
	
	IF ( (_esc_is_sent = 1) AND 
		( EXISTS(SELECT 1 FROM `vs_request` 
			WHERE `req_id` = _esc_for_reqid
				AND `req_first_vendor_mail_sent` IS NULL) ) ) THEN
		UPDATE `vs_request`
		SET `req_first_vendor_mail_sent` = _esc_triggereddate
		WHERE `req_id` = _esc_for_reqid;
	END IF;
end IF;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEscalation_HR` (IN `LastMailSendMaxTimes` INT)  BEGIN
	DECLARE _curDate DATE;
	SET _curDate = CONVERT(NOW(), DATE);
	
	SELECT
		`req_id`, `req_emp_id`
		, (CASE WHEN `esc_no` = 1 
			-- To : HR 
			THEN `req_emp_hrmgr_name`
			-- To : HR's L1 Manager
			ELSE `req_emp_sr_hrmgr_name`
			END) AS `dear_hr_name`
			
		, (CASE WHEN `esc_no` = 1 
			-- To : HR 
			THEN 'you'
			-- To : HR's L1 Manager
			ELSE `req_emp_hrmgr_name`
			END) AS `hr_name`
		, `req_emp_name`
		, `req_emp_token`
		, `req_emp_new_desig`
		, `req_emp_new_dept`
		, `req_emp_new_address`
		, `req_emp_new_landline`
		, `req_emp_new_mobile`
		, `req_emp_email`
		, `req_emp_new_costcenter`
		, `req_emp_wbs`
		
		, CONVERT(`req_submitteddate`, DATE) AS `req_submitteddate`
		
		, `days_diff`
		, `esc_no`
		
		, (CASE WHEN `esc_no` = 1 
			-- To : HR 
			THEN `req_emp_hrmgr_email`
			-- To : HR's L1 Manager
			ELSE `req_emp_sr_hrmgr_email`
			END) AS `esc_to_emailids`
			
		, (CASE WHEN `esc_no` = 1 
			-- CC: HR' L1 Manager, Employee Manager
			THEN CONCAT(`req_emp_email`, ',', `req_emp_sr_hrmgr_email`, ',', `req_emp_mgr_email`)
			 -- CC: HR, Employee Manager, HR Head, Nikhil Gama
			ELSE CONCAT(`req_emp_email`, ',', `req_emp_hrmgr_email`, ',', `req_emp_mgr_email`, ',', `central_hr`)
			END) AS `esc_cc_emailids`
	FROM (
		SELECT
			-- `req_id`, `req_emp_id`, `req_emp_token`, `req_emp_name`, CONVERT(`req_submitteddate`, DATE) `req_submitteddate`
			-- , `req_emp_email`
			-- , `req_emp_mgr_email`
			-- , `req_emp_hrmgr_email`, `req_emp_sr_hrmgr_email`
			*
									-- Have to show days dif so will be less acual checking days as per weekday logic
			, ( DATEDIFF(NOW(), `req_submitteddate`) - (CASE WHEN WEEKDAY(`req_submitteddate`) >= 4 THEN 3 ELSE 1 END)) AS `days_diff`
			, (SELECT GROUP_CONCAT(DISTINCT `vs_esc_empemail` ORDER BY `vs_esc_empemail` ASC SEPARATOR ',') 
				FROM `vs_escalation_mst` 
				WHERE `vs_esc_role` = 'Central HR') AS `central_hr`
			, (CASE WHEN NOT EXISTS(SELECT 1 FROM `vs_esc_hr_log` 
						WHERE `vs_esc_hr_log`.`esc_for_reqid` = `vs_request`.`req_id` 
							AND `vs_esc_hr_log`.`esc_no` = 1) THEN 1 ELSE 2 END) AS `esc_no`
		FROM `vs_request`
											-- WEEKDAY 4 = Friday
		WHERE _curDate >= CONVERT( ADDDATE(`req_submitteddate`,(CASE WHEN WEEKDAY(`req_submitteddate`) >= 4 THEN 4 ELSE 2 END) ), DATE)
			AND `req_status` = 'Pending Approval from HR'
			AND ADDDATE(IFNULL(`req_first_hr_mail_sent`, NOW()), (2 + LastMailSendMaxTimes) ) >= _curDate
	) AS `tbl_dtl`;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEscalation_VENDOR` (IN `LastMailSendMaxTimes` INT)  BEGIN
	DECLARE _curDate DATE;
	SET _curDate = CONVERT(NOW(), DATE);
	
	SELECT
		`req_id`, `req_emp_id`
		, (CASE WHEN `esc_no` = 1
			-- To : Vendor
			THEN CONCAT('Dear <b style="color: #e31837;">', `vendor_name`, '</b>')
			-- To : Admin & SSU
			ELSE 'Hi <b style="color: #e31837;">All</b>'
			END) AS `dear_name`
			
		, `req_emp_name`
		, `req_emp_token`
		, `req_emp_new_desig`
		, `req_emp_new_dept`
		, `req_emp_new_address`
		, `req_emp_new_landline`
		, `req_emp_new_mobile`
		, `req_emp_email`
		, `req_emp_new_costcenter`
		, `req_emp_wbs`
		
		, CONVERT(`req_submitteddate`, DATE) AS `req_submitteddate`
		, IFNULL(`req_hr_actiondate`, `req_submitteddate`) AS `mail_date`
		
		, `days_diff`
		, `esc_no`
		
		, (CASE WHEN `esc_no` = 1 
			-- To : Vendor
			THEN `vendor_email`
			-- To : Admin & SSU
			ELSE CONCAT(`admin_hr`, ',', `ssu_hr`)
			END) AS `esc_to_emailids`
			
		, (CASE WHEN `esc_no` = 1 
			-- CC: Admin & SSU
			THEN CONCAT(`admin_hr`, ',', `ssu_hr`)
			WHEN `esc_no` = 2
			-- CC: Vendor & Employee
			THEN CONCAT(`vendor_email`, ',', `req_emp_email`)
			WHEN `esc_no` = 3
			-- CC: Vendor, Employee, HR, HR Head
			THEN CONCAT(`vendor_email`, ',', `req_emp_email`, ',', `req_emp_hrmgr_email`, ',', `req_emp_sr_hrmgr_email`)
			-- CC: Vendor, Employee, HR, HR Head, Central HR
			ELSE CONCAT(`vendor_email`, ',', `req_emp_email`, ',', `req_emp_hrmgr_email`, ',', `req_emp_sr_hrmgr_email`, ',', `central_hr`)
			END) AS `esc_cc_emailids`
	FROM (
		SELECT
			-- `req_id`, `req_emp_id`, `req_emp_token`, `req_emp_name`, CONVERT(`req_submitteddate`, DATE) `req_submitteddate`
			-- , `req_emp_email`
			-- , `req_emp_mgr_email`
			-- , `req_emp_hrmgr_email`, `req_emp_sr_hrmgr_email`
			*
									-- Have to show days dif so will be less acual checking days as per weekday logic
			, ( DATEDIFF(NOW(), IFNULL(`req_hr_actiondate`, `req_submitteddate`)) - (CASE WHEN WEEKDAY(IFNULL(`req_hr_actiondate`, `req_submitteddate`)) = 4 THEN 5 ELSE 3 END)) AS `days_diff`
			
			, (SELECT GROUP_CONCAT(DISTINCT `vs_esc_empemail` ORDER BY `vs_esc_empemail` ASC SEPARATOR ',') 
				FROM `vs_escalation_mst` 
				WHERE `vs_esc_role` = 'Admin'
					AND CONCAT(',', `vs_esc_locids`, ',') LIKE CONCAT('%,', `vs_request`.`req_emp_location_id`, ',%') ) AS `admin_hr`
			, (SELECT GROUP_CONCAT(DISTINCT `vs_esc_empemail` ORDER BY `vs_esc_empemail` ASC SEPARATOR ',') 
				FROM `vs_escalation_mst` 
				WHERE `vs_esc_role` = 'SSU') AS `ssu_hr`
			, (SELECT GROUP_CONCAT(DISTINCT `vs_esc_empemail` ORDER BY `vs_esc_empemail` ASC SEPARATOR ',') 
				FROM `vs_escalation_mst` 
				WHERE `vs_esc_role` = 'Central HR') AS `central_hr`
			, (CASE WHEN NOT EXISTS(SELECT 1 FROM `vs_esc_vendor_log`
						WHERE `vs_esc_vendor_log`.`esc_for_reqid` = `vs_request`.`req_id` 
							AND `vs_esc_vendor_log`.`esc_no` = 1) 
				THEN 1 
				WHEN NOT EXISTS(SELECT 1 FROM `vs_esc_vendor_log`
						WHERE `vs_esc_vendor_log`.`esc_for_reqid` = `vs_request`.`req_id` 
							AND `vs_esc_vendor_log`.`esc_no` = 2) 
				THEN 2 
				WHEN NOT EXISTS(SELECT 1 FROM `vs_esc_vendor_log`
						WHERE `vs_esc_vendor_log`.`esc_for_reqid` = `vs_request`.`req_id` 
							AND `vs_esc_vendor_log`.`esc_no` = 3) 
				THEN 3 
				ELSE 4 END) AS `esc_no`
		FROM `vs_request`
		INNER JOIN `vs_vendor`
			ON `vs_vendor`.`vendor_id` = `vs_request`.`req_vendor_id` 
																				-- WEEKDAY 4 = Friday
		WHERE _curDate >= CONVERT( ADDDATE(IFNULL(`req_hr_actiondate`, `req_submitteddate`),(CASE WHEN WEEKDAY(IFNULL(`req_hr_actiondate`, `req_submitteddate`)) >= 4 THEN 10 ELSE 8 END) ), DATE)
			AND `req_status` = 'Request Send to Vendor'
			AND IFNULL(`req_hr_rejectremark`, '') = ''
			AND ADDDATE(IFNULL(`req_first_vendor_mail_sent`, NOW()), (3 + LastMailSendMaxTimes) ) >= _curDate
	) AS `tbl_dtl`;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRequestReport` (IN `_locationName` VARCHAR(100), IN `_fromDate` DATETIME, IN `_toDate` DATETIME)  BEGIN
SET _fromDate = CONVERT(_fromDate, DATE), _toDate = CONVERT(_toDate, DATE);
SELECT
	`req_emp_token` AS `RequesterToken`
	, `req_emp_name` AS `DisplayName`
	, IFNULL(`req_emp_new_desig`,'') AS `Designation`
	, IFNULL(`req_emp_new_dept`,'') AS `Department_Division`
	, IFNULL(`req_emp_new_buss_unit`,'') AS `Business_Unit`
	, IFNULL(`req_emp_address`,'') AS `Address`
	, '' AS `Admin`
	, IFNULL(`req_emp_stdcode`,'') AS `ISDCode`
	, IFNULL(`req_emp_landline`,'') AS `Telephone`
	, IFNULL(`req_emp_mobile`,'') AS `Mobile`
	, IFNULL(`req_emp_email`,'') AS `Official_Email`
	, IFNULL(`req_emp_costcenter`,'') AS `Cost_Center`
	, IFNULL(`req_emp_wbs`,'') AS `WBS`
	
	, IFNULL(`vs_vendor`.`vendor_name`,'') AS `Vendor_Name`
	
	, IFNULL(DATE_FORMAT(`req_submitteddate`, '%d/%m/%Y'),'') AS `Created_Date`
	
	, IFNULL(DATE_FORMAT(IFNULL((CASE WHEN IFNULL(`req_hr_rejectremark`, '') = '' THEN `req_hr_actiondate` END), `req_submitteddate`), '%d/%m/%Y %r'),'') AS `HR_Approve_Date`
	, IFNULL(`req_status`,'') AS `Current_Status`
	
	, IFNULL(`vs_esc_hr_log_first`.`esc_to_emailids`,'') AS `HR_First_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_hr_log_first`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `HR_First_Escalation_Date`
	
	, IFNULL(`vs_esc_hr_log_second`.`esc_to_emailids`,'') AS `HR_Second_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_hr_log_second`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `HR_Second_Escalation_Date`
	, IFNULL(DATE_FORMAT(`req_first_vendor_mail_sent`, '%d/%m/%Y %r'),'') AS `Request_received_by_Vendor`
	, IFNULL(DATE_FORMAT(`req_vendor_dispatchdate`, '%d/%m/%Y %r'),'') AS `Dispatched_Date`
	, IFNULL(DATE_FORMAT(`req_completeddate`, '%d/%m/%Y'),'') AS `Close_Date`
	
	, IFNULL(`req_vendor_remark`,'') AS `Courie_details`
	
	, IFNULL(`vs_esc_vendor_log_first`.`esc_to_emailids`,'') AS `VENDOR_First_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_vendor_log_first`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `VENDOR_First_Escalation_Date`
	
	, IFNULL(`vs_esc_vendor_log_second`.`esc_to_emailids`,'') AS `VENDOR_Second_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_vendor_log_second`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `VENDOR_Second_Escalation_Date`
	
	, IFNULL(`vs_esc_vendor_log_third`.`esc_to_emailids`,'') AS `VENDOR_Third_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_vendor_log_third`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `VENDOR_Third_Escalation_Date`
	
	, IFNULL(`vs_esc_vendor_log_fourth`.`esc_to_emailids`,'') AS `VENDOR_Fourth_Escalation`
	, IFNULL(DATE_FORMAT(`vs_esc_vendor_log_fourth`.`esc_sent_date`, '%d/%m/%Y %r'),'') AS `VENDOR_Third_Fourth_Date`
FROM `vs_request`
LEFT OUTER JOIN `vs_vendor`
	ON `vs_vendor`.`vendor_id` = `vs_request`.`req_vendor_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_hr_log` 
	WHERE `esc_no` = 1
		AND `esc_hr_logid` IN (
			SELECT
				MAX(`esc_hr_logid`)
			FROM `vs_esc_hr_log` 
			WHERE `esc_no` = 1
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_hr_log_first`
	ON `vs_esc_hr_log_first`.`esc_for_reqid` = `vs_request`.`req_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_hr_log` 
	WHERE `esc_no` = 2
		AND `esc_hr_logid` IN (
			SELECT
				MAX(`esc_hr_logid`)
			FROM `vs_esc_hr_log` 
			WHERE `esc_no` = 2
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_hr_log_second`
	ON `vs_esc_hr_log_second`.`esc_for_reqid` = `vs_request`.`req_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_vendor_log` 
	WHERE `esc_no` = 1
		AND `esc_vendor_logid` IN (
			SELECT
				MAX(`esc_vendor_logid`)
			FROM `vs_esc_vendor_log` 
			WHERE `esc_no` = 1
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_vendor_log_first`
	ON `vs_esc_vendor_log_first`.`esc_for_reqid` = `vs_request`.`req_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_vendor_log` 
	WHERE `esc_no` = 2
		AND `esc_vendor_logid` IN (
			SELECT
				MAX(`esc_vendor_logid`)
			FROM `vs_esc_vendor_log` 
			WHERE `esc_no` = 2
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_vendor_log_second`
	ON `vs_esc_vendor_log_second`.`esc_for_reqid` = `vs_request`.`req_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_vendor_log` 
	WHERE `esc_no` = 3
		AND `esc_vendor_logid` IN (
			SELECT
				MAX(`esc_vendor_logid`)
			FROM `vs_esc_vendor_log` 
			WHERE `esc_no` = 3
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_vendor_log_third`
	ON `vs_esc_vendor_log_third`.`esc_for_reqid` = `vs_request`.`req_id`
LEFT OUTER JOIN (
	SELECT
		`esc_for_reqid`
		, `esc_to_emailids`
		, `esc_sent_date`
	FROM `vs_esc_vendor_log` 
	WHERE `esc_no` = 4
		AND `esc_vendor_logid` IN (
			SELECT
				MAX(`esc_vendor_logid`)
			FROM `vs_esc_vendor_log` 
			WHERE `esc_no` = 4
			GROUP BY `esc_for_reqid`
		)
) AS `vs_esc_vendor_log_fourth`
	ON `vs_esc_vendor_log_fourth`.`esc_for_reqid` = `vs_request`.`req_id`
WHERE CONVERT(`req_submitteddate`, DATE) BETWEEN _fromDate AND _toDate
	AND `req_emp_new_location_name` LIKE CONCAT('%', _locationName, '%')
ORDER BY `req_submitteddate`;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `balance_table`
--

CREATE TABLE `balance_table` (
  `locationid` int(11) NOT NULL,
  `balance` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `errorlog`
--

CREATE TABLE `errorlog` (
  `logID` bigint(20) NOT NULL,
  `logTypeID` tinyint(4) DEFAULT NULL,
  `logFileName` varchar(250) DEFAULT NULL,
  `logDescription` varchar(8000) DEFAULT NULL,
  `logIPAddress` varchar(50) DEFAULT NULL,
  `logSource` varchar(8000) DEFAULT NULL,
  `logStackTrace` varchar(8000) DEFAULT NULL,
  `logDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `financial_year`
--

CREATE TABLE `financial_year` (
  `finacialid` int(11) NOT NULL,
  `financial_year` varchar(5) NOT NULL,
  `finacial_year_caption` varchar(200) NOT NULL,
  `finacial_startdate` datetime NOT NULL,
  `finacial_enddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `financial_year`
--

INSERT INTO `financial_year` (`finacialid`, `financial_year`, `finacial_year_caption`, `finacial_startdate`, `finacial_enddate`) VALUES
(1, '', 'FY 2017-18', '2018-04-01 00:00:00', '2019-03-31 00:00:00'),
(2, '', 'FY 2018-19', '2019-04-01 00:00:00', '2020-04-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `test_empmst`
--

CREATE TABLE `test_empmst` (
  `test_empid` int(11) NOT NULL,
  `emp_token` varchar(15) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `emp_desig` varchar(100) NOT NULL,
  `emp_dept` varchar(100) DEFAULT NULL,
  `emp_buss_unit` varchar(100) DEFAULT NULL,
  `emp_location_name` varchar(100) DEFAULT NULL,
  `emp_stdcode` varchar(10) DEFAULT NULL,
  `emp_landline` varchar(30) DEFAULT NULL,
  `emp_fax` varchar(30) DEFAULT NULL,
  `emp_mobile` varchar(40) DEFAULT NULL,
  `emp_email` varchar(75) NOT NULL,
  `emp_costcenter` varchar(100) NOT NULL,
  `emp_wbs` varchar(20) DEFAULT NULL,
  `emp_mgr_token` varchar(15) NOT NULL,
  `emp_mgr_name` varchar(100) NOT NULL,
  `emp_mgr_email` varchar(75) NOT NULL,
  `emp_hrmgr_token` varchar(15) NOT NULL,
  `emp_hrmgr_name` varchar(100) NOT NULL,
  `emp_hrmgr_email` varchar(75) NOT NULL,
  `emp_sr_hrmgr_token` varchar(15) NOT NULL,
  `emp_sr_hrmgr_name` varchar(100) NOT NULL,
  `emp_sr_hrmgr_email` varchar(75) NOT NULL,
  `createddate` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_empmst`
--

INSERT INTO `test_empmst` (`test_empid`, `emp_token`, `emp_name`, `emp_desig`, `emp_dept`, `emp_buss_unit`, `emp_location_name`, `emp_stdcode`, `emp_landline`, `emp_fax`, `emp_mobile`, `emp_email`, `emp_costcenter`, `emp_wbs`, `emp_mgr_token`, `emp_mgr_name`, `emp_mgr_email`, `emp_hrmgr_token`, `emp_hrmgr_name`, `emp_hrmgr_email`, `emp_sr_hrmgr_token`, `emp_sr_hrmgr_name`, `emp_sr_hrmgr_email`, `createddate`) VALUES
(1, '10188', 'Umesh Agashe', 'Deputy General Manager', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9890663235', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Roy Gyanendra', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(2, '10835', 'Vinay Ambekar', 'Assistant Manager', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'Pune', '+91', '', '', '9970511723', 'apoorva.ajmera@intellectsoftsol.com', 'IT-PUNE', '', '23197853', 'Venkatakrishnan B', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(3, '12092', 'Lucy Varghese', 'Executive -Sales Planning', 'SALES', 'TWO WHEELERS', 'Pune', '91', '', '', '9923794286', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-PUNE', '', '23141859', 'T Vijaya Ragavan', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(4, '12237', 'Swapnil Chaudhari', 'Sr.Manager - MTWL (CDMM)', 'CDMM', 'AUTOMOTIVE DIVISION', 'MTW-Pune office', '91', '', '', '9881468140', 'apoorva.ajmera@intellectsoftsol.com', 'CDMM-Classic - CLPL', '', '23141859', 'Nagbhidkar Manish Vasantrao', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(5, '12267', 'Sanjeev APPANNA Yaligar', 'Deputy Manager', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9890548000', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Agashe Umesh', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(6, '12274', 'Suhas Kulkarni', 'Engineer', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9850348599', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Ranade Kedar SUBHASH', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(7, '12352', 'Arvind Mhatre', 'Deputy Manager', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9822206323', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Govindarajan R.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(8, '12365', 'Balasaheb Sayal', 'Officer', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9226445593', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Ranade Kedar SUBHASH', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(9, '12369', 'Kedar SUBHASH Ranade', 'Senior Manager', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9822338901', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Roy Gyanendra', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(10, '12395', 'P Shino', 'Engineer', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9922455161', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Bhivase Avinash', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(11, '12404', 'Awadesh Singh', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Lucknow', '91', '', '', '8601333371', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-LUCKNOW', '', '23141859', 'Choudhary Manoj', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(12, '12414', 'Manoj Kumar', 'Deputy Manager - Service Planning', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pune', '91', '', '', '9835241523', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-PUNE', '', '23141859', 'Hariharan Venugopal', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(13, '12437', 'Priyanka Ghume', 'Assistant Manager - Administration', 'ADMINISTRATION', 'TWO WHEELERS', 'Pune', '91', '', '', '7720032494', 'apoorva.ajmera@intellectsoftsol.com', 'EMPLY CARE & ADMIN-P', '', '23141859', 'Gunjal Pravin Namdev', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(14, '12521', 'Vineet Joshi', 'Cluster Business Manager', 'SALES', 'TWO WHEELERS', 'Kolkata', '91', '', '', '7766908447', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-BHUBANESHWAR', '', '23141859', 'Not assigned', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(15, '12527', 'Vijay KAUTIK Mahajan', 'Executive', 'FINANCE & ACCOUNTS', 'TWO WHEELERS', 'Pune', '91', '', '', '9284090432', 'apoorva.ajmera@intellectsoftsol.com', 'FINANCE & ACNTS-PUNE', '', '23191843', 'Suthar Ramesh', 'contact@intellectsoftsol.com', '23191843', 'Katekari Shraddha', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(16, '12537', 'K Vijaykumar', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Cochin', '91', '1111-2222', '3333-4444', '9995378120', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-COCHIN', 'WBS-37', '23141859', 'Anilkumar T.V.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(17, '12540', 'Manoj Choudhary', 'State Head - Rajasthan & UP East', 'SALES', 'TWO WHEELERS', 'Jaipur', '91', '', '', '8370039919', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-JAIPUR', '', '23141859', 'Malhotra Naveen', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(18, '12593', 'Harshad Mahajan', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pune', '91', '', '', '9960668831', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-KOLHAPUR', '', '23141859', 'Kulkarni Chandrashekar S.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(19, '12597', 'Sarfaraj Shaikh', 'Executive - SS - BIW & Paints', 'STRATEGIC SOURCING', 'COMMON SERVICES - PURCHASING', 'Pune-AD', '91', '', '', '8888845358', 'apoorva.ajmera@intellectsoftsol.com', 'Sourcing & Pricing - BIW, Paints & Chemi', '', '25001056', 'Wadhera Sunil KUMAR', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(20, '12611', 'Brijendra Shukla', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Bhopal', '91', '', '', '96300102555', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-BHOPAL', '', '23141859', 'Kulkarni Chandrashekar S.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(21, '12660', 'Shrikant MORESHWAR Kulkarni', 'Manager - Product Monitoring Group', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pune', '91', '', '', '9881109745', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-PUNE', '', '23141859', 'Lakhe Gaurav', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(22, '12682', 'Romalin Pinto', 'Deputy Manager-Administration', 'ADMINISTRATION', 'TWO WHEELERS', 'Pune', '91', '', '', '9822297553', 'apoorva.ajmera@intellectsoftsol.com', 'EMPLY CARE & ADMIN-P', '', '23141859', 'Gunjal Pravin Namdev', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(23, '12729', 'Rajesh Agarwal', 'Territory Manager - Sales', 'SALES', 'TWO WHEELERS', 'Ahmedabad', '91', '', '', '9725008009', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-AHEMDABAD', '', '23141859', 'Kulkarni Chandrashekar S.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(24, '12742', 'Sujit Ghosh', 'Territory Manager - Sales', 'SALES', 'TWO WHEELERS', 'Kolkata', '91', '', '', '7070097947', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-KOLKATA', '', '23141859', 'Not assigned', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(25, '12754', 'Jayanta Das', 'Territory Manager-Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Kolkata', '91', '', '', '9437076310', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-KOLKATA', '', '23141859', 'Joshi Vineet', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(26, '12757', 'Balmiki Pandey', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Patna', '91', '', '', '9304809619', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-MUZAFFARPUR', '', '23141859', 'Joshi Vineet', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(27, '12770', 'Nagraj Naik', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Hubli', '91', '', '', '7353112800', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-BANGALORE', '', '23141859', 'Rai Rakshit', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(28, '12777', 'Ravindra Joshi', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Nagpur', '91', '', '', '8600990337', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-KOLHAPUR', '', '23141859', 'Kulkarni Chandrashekar S.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(29, '12779', 'Girija Patro', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Bhubaneshwar', '91', '', '', '9437063801', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-BHUBANESHWAR', '', '23141859', 'Joshi Vineet', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(30, '12780', 'Pinaky Mohanty', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Kolkata', '91', '', '', '9437024551', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-KOLKATA', '', '23141859', 'Joshi Vineet', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(31, '12796', 'Pradeep Gupta', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Meerut', '91', '', '', '7500984888', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-LUCKNOW', '', '23141859', 'Choudhary Manoj', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(32, '12801', 'Mukund Joshi', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pune', '91', '', '', '9422277412', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-KOLHAPUR', '', '23141859', 'Kulkarni Chandrashekar S.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(33, '12826', 'Basheer Inamdar', 'Deputy Manager - Spares Sourcing', 'STRATEGIC SOURCING', 'COMMON SERVICES - PURCHASING', 'Pune-AD', '91', '', '', '9881907681', 'apoorva.ajmera@intellectsoftsol.com', 'TW-Spares Sourcing', '', '25001056', 'Dange Satish', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(34, '12891', 'Surekha Jadhav', 'Dy Manager - Finance & Accounts - MTWL', 'FINANCE & ACCOUNTS', 'TWO WHEELERS', 'Pune', '91', '', '', '7509068668', 'apoorva.ajmera@intellectsoftsol.com', 'FINANCE & ACNTS-PUNE', '', '23191843', 'Not assigned', 'contact@intellectsoftsol.com', '23191843', 'Katekari Shraddha', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(35, '12898', 'Rajendra DATTARAM Gurav', 'Asst Manager - Finance & Accounts - MTWL', 'FINANCE & ACCOUNTS', 'TWO WHEELERS', 'Pune', '91', '', '', '9975197222', 'apoorva.ajmera@intellectsoftsol.com', 'FINANCE & ACNTS-PUNE', '', '23191843', 'Jha Murli Manohar', 'contact@intellectsoftsol.com', '23191843', 'Katekari Shraddha', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(36, '12988', 'P Gopikannan', 'Territory Manager - Customer Care', 'CUSTOMER CARE', 'TWO WHEELERS', 'Chennai', '91', '', '', '9791116491', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-CHENNAI', '', '23141859', 'Anilkumar T.V.', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(37, '12999', 'Gopal Shinde', 'Assistant Manager - Vehicle Design', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '7798999177', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Agashe Umesh', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(38, '13002', 'Subhash Chaudhari', 'Executive', 'SALES', 'TWO WHEELERS', 'Chennai', '91', '', '', '9975214039', 'apoorva.ajmera@intellectsoftsol.com', 'SALES - CHENNAI', '', '23141859', 'Das Joydeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(39, '13017', 'Yuvraj VISHWANATHRAO Bhure', 'Deputy Manager', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', 'Pune', '91', '', '', '9823102611', 'apoorva.ajmera@intellectsoftsol.com', 'RESEARCH & DEVP-PUNE', '', '23141859', 'Dangi Rahul', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(40, '13020', 'Ashok Khanapure', 'Assistant Manager- Asset Management', 'ASSET MANAGEMENT', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(AD)', '91', '', '', '7385636945', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli- Capital Purchases', '', '25001056', 'Deuskar Jeetrendra', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(41, '13031', 'Satendra Pal', 'MANAGER - IT', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'Pune', '+91', '', '', '8888813631', 'apoorva.ajmera@intellectsoftsol.com', 'AUTO SRCTOR IT (AFS)', '', '23197853', 'Patrikar Rachana', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(42, '13082', 'Rajeev Manchanda', 'GM Sales - Region 3', 'SALES', 'SWARAJ DIVISION', 'Noida', '91', '', '', '9990008411', 'apoorva.ajmera@intellectsoftsol.com', 'Haryana', '', '23147534', 'Rellan Rajeev', 'contact@intellectsoftsol.com', '23147534', 'Sushil Mahima', 'jobs@intellectsoftsol.com', '209184', 'Kini Yeshwanth KUMAR', 'alliance@intellectsoftsol.com', ''),
(43, '13091', 'T.V. Anilkumar', 'State Head', 'SALES', 'TWO WHEELERS', 'Cochin', '91', '', '', '9900284477', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-ZONE1', '', '23141859', 'Malhotra Naveen', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(44, '13092', 'Mukesh Kulshrestha', 'Manager - Spare Parts', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pune', '91', '', '', '8412938007', 'apoorva.ajmera@intellectsoftsol.com', 'SPARE  DESPATCH', '', '23141859', 'Malhotra Naveen', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(45, '13101', 'Abhinav Mathur', 'Territory Manager- Bazaar Sales Pune', 'BAZAAR SALES', 'SPARES BUSINESS', 'Pune (FD)', '91', '', '', '8017888584', 'apoorva.ajmera@intellectsoftsol.com', 'SBU - Sales & Marketing West 1', '', '23195238', 'Kamat Meghan', 'contact@intellectsoftsol.com', '23195238', 'Ahire Mangesh', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(46, '20520', 'Sudhir Sharma', 'Deputy General Manager', 'EMPLOYEE RELATIONS', 'TWO WHEELERS', 'Pithampur', '91', '', '', '8085953113', 'apoorva.ajmera@intellectsoftsol.com', 'ADMIN-PITH', '', '23141859', 'Tuli Vijay', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(47, '20619', 'Bharat K.Pandey', 'Deputy Manager', 'MANUFACTURING(MFG)', 'TWO WHEELERS', 'Pithampur', '91', '', '', '8085954114', 'apoorva.ajmera@intellectsoftsol.com', 'CONSUMBLE STR-PITH', '', '23141859', 'Goel Sandeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(48, '20862', 'Vijay Singh', 'Assistant Manager', 'MANUFACTURING(MFG)', 'TWO WHEELERS', 'Pithampur', '91', '', '', '7509064348', 'apoorva.ajmera@intellectsoftsol.com', 'CONSUMBLE STR-PITH', '', '23141859', 'Goel Sandeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(49, '20865', 'Narendra Solunke', 'Assistant Manager - Warranty', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pithampur', '91', '', '', '8109179426', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-PUNE', '', '23141859', 'Devasthali Vishwas', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(51, '20884', 'Rajendran B.', 'Deputy Manager-Employee Relations', 'EMPLOYEE RELATIONS', 'TWO WHEELERS', 'Pithampur', '91', '', '', '8085954120', 'apoorva.ajmera@intellectsoftsol.com', 'ADMIN-PITH', '', '23141859', 'Sharma Sudhir', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(52, '25274', 'Chaitanya Khambete', 'Deputy Manager - Warranty', 'CUSTOMER CARE', 'TWO WHEELERS', 'Pithampur', '91', '', '', '7509064332', 'apoorva.ajmera@intellectsoftsol.com', 'SERVICE-PUNE', '', '23141859', 'Devasthali Vishwas', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(53, '25285', 'Dharamveer Singh', 'Executive -Assembly', 'MANUFACTURING(MFG)', 'TWO WHEELERS', 'Pithampur', '91', '', '', '9039398705', 'apoorva.ajmera@intellectsoftsol.com', 'FRAME ASSEMBLY-PITH', '', '23141859', 'Singh Bhoopendra', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(54, '25487', 'Murlidhar Solanki', 'Executive', 'MANUFACTURING(MFG)', 'TWO WHEELERS', 'Pithampur', '91', '', '', '7509068636', 'apoorva.ajmera@intellectsoftsol.com', 'SPARE PARTS-PITH', '', '23141859', 'Goel Sandeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(55, '25510', 'Pradeep Mishra', 'Assistant Manager', 'SALES', 'TWO WHEELERS', 'Chennai', '91', '', '', '8085973956', 'apoorva.ajmera@intellectsoftsol.com', 'SALES - CHENNAI', '', '23141859', 'Das Joydeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(56, '25512', 'Bappaditya Das', 'Manager', 'MANUFACTURING(MFG)', 'TWO WHEELERS', 'Pithampur', '91', '', '', '8085958993', 'apoorva.ajmera@intellectsoftsol.com', 'MED', '', '23141859', 'B. G Suresh', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(57, '25514', 'Surendra KUMAR Yadav', 'Executive-Production Tool Room', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'Nashik Plant II', '91', '', '', '9981208985', 'apoorva.ajmera@intellectsoftsol.com', 'Tool Room', '', '207965', 'Tambe Somnath', 'contact@intellectsoftsol.com', '207965', 'Deva Kumar K', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(58, '25527', 'Vinay Saxena', 'Deputy Manager', 'SALES', 'TWO WHEELERS', 'Guwahati', '91', '', '', '8085912049', 'apoorva.ajmera@intellectsoftsol.com', 'SALES-ZONE7', '', '23141859', 'Shah Yogesh', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(59, '25536', 'Vinay Kondejkar', 'Deputy Manager - Exports', 'EXPORTS', 'TWO WHEELERS', 'Pithampur', '91', '', '', '7509064333', 'apoorva.ajmera@intellectsoftsol.com', 'EXPORT-PUNE', '', '23141859', 'Goel Sandeep', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(60, '200255', 'Hiraman DEORAM Aher', 'V.P. - Operations (Nashik & Igatpuri)', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'Nashik Plant I', '91', '', '', '9822613515', 'apoorva.ajmera@intellectsoftsol.com', 'Plant Head\'s Office', '', '23140916', 'Kalra Vijay', 'contact@intellectsoftsol.com', '23140916', 'Sohale Ashutosh Anil', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(61, '200306', 'R Sadasivan', 'Sr. General Manager - CPPC', 'CPPC', 'AUTOMOTIVE DIVISION', 'MUM-KND-AFS(AD)', '91', '', '', '9920112288', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli-Central P P C', '', '200823', 'Kalra Vijay', 'contact@intellectsoftsol.com', '200823', 'Joshi Atul', 'jobs@intellectsoftsol.com', '207971', 'Wadhera Rajan', 'alliance@intellectsoftsol.com', ''),
(62, '200499', 'Girish Bhobe', 'GM - Production Planning & Sourcing', 'STRATEGIC SOURCING', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(AD)', '91', '', '', '9892248473', 'apoorva.ajmera@intellectsoftsol.com', 'Spares Sourcing', '', '25001056', 'Dhond Sandip GOVIND', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(63, '200501', 'Balkrishna SHARADCHANDRA Patil', 'G.M. - Head Powertrain CME', 'PTD CME', 'AUTOMOTIVE DIVISION', 'Chakan-AD', '91', '', '', '9987023651', 'apoorva.ajmera@intellectsoftsol.com', 'AS-CME (General)-ckn', '', '23192416', 'Yang Jee Woong', 'contact@intellectsoftsol.com', '23192416', 'Dalvi Vikram', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(64, '200507', 'M B Pathare', 'General Manager-Asset Management', 'ASSET MANAGEMENT', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(AD)', '91', '', '', '9892532482', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli- Capital Purchases', '', '25001056', 'Prabhu R U', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(65, '200514', 'Sunil Kale', 'Deputy General Manager - CME', 'PTD CME', 'AUTOMOTIVE DIVISION', 'Chakan-AD', '91', '', '', '9892329382', 'apoorva.ajmera@intellectsoftsol.com', 'AS-CME (General)-ckn', '', '23192416', 'Patil Balkrishna SHARADCHANDRA', 'contact@intellectsoftsol.com', '23192416', 'Dalvi Vikram', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(66, '200516', 'Madhav Apte', 'DGM Product Cost Management', 'PRODUCT COST MANAGEMENT', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(FD)', '91', '', '', '9004099497', 'apoorva.ajmera@intellectsoftsol.com', 'PRODUCT COST MANAGEMENT', '', '25001056', 'Karkhanis Atul GAJANAN', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(67, '200517', 'Pradip Deshmukh', 'Vice President - Operations', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'Nashik Plant I', '91', '', '', '9850901595', 'apoorva.ajmera@intellectsoftsol.com', 'Plant Head\'s Office', '', '200823', 'Kalra Vijay', 'contact@intellectsoftsol.com', '200823', 'Joshi Atul', 'jobs@intellectsoftsol.com', '207971', 'Wadhera Rajan', 'alliance@intellectsoftsol.com', ''),
(68, '200527', 'Nandakumar Walve', 'DGM - QUALITY PLANNING', 'QUALITY', 'CONSTRUCTION EQUIPMENT', 'Pune', '91', '', '', '9820424387', 'apoorva.ajmera@intellectsoftsol.com', 'Program Management', '', '23158807', 'Bajpai Ajai Kumar', 'contact@intellectsoftsol.com', '23158807', 'Singh Soma', 'jobs@intellectsoftsol.com', '23107787', 'Sharma Disha', 'alliance@intellectsoftsol.com', ''),
(69, '200543', 'Satish D Namjoshi', 'General Manager - IT Delivery - Core SAP', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'MUM-KND-AFS(FD)', '+91', '', '', '9967323393', 'apoorva.ajmera@intellectsoftsol.com', 'AUTO SRCTOR IT (AFS)', '', '23197853', 'Chavan Sanjay', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(70, '200544', 'Dhiren Doshi', 'Sr. Manager- SQA', 'QUALITY', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9987026866', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli - Supplier Quality', '', '25001415', 'Matodkar R N', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(71, '200549', 'Dinesh THAKU Naik', 'DGM-Learning Management Systems', 'CUSTOMER CARE', 'AUTOMOTIVE DIVISION', 'MUM-KND-AFS(AD)', '91', '', '', '9224226728', 'apoorva.ajmera@intellectsoftsol.com', 'Customer care Technica', '', '23171713', 'Kingsley Samuel', 'contact@intellectsoftsol.com', '23171713', 'Banerjee Pritha', 'jobs@intellectsoftsol.com', '208924', 'Gandhi Yogesh', 'alliance@intellectsoftsol.com', ''),
(72, '200550', 'D R Shastri', 'DGM - AFS SUSTAINABILITYCSR & ETHICS', '--', 'SUSTAINABILITY', 'MUM-KND-AFS(AD)', '91', '', '', '9987962662', 'apoorva.ajmera@intellectsoftsol.com', 'Sustainability, CSR & Ethics', '', '23153055', 'Lambhate Abhay', 'contact@intellectsoftsol.com', '23153055', 'Raghuvanshi Vaibhav', 'jobs@intellectsoftsol.com', '211435', 'Mathew Smita PHILIPS', 'alliance@intellectsoftsol.com', ''),
(73, '200554', 'Samuel Thomas', 'Deputy General Manager - Internal Audit', 'STRATEGIC SOURCING', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(AD)', '91', '', '', '9892530074', 'apoorva.ajmera@intellectsoftsol.com', 'SSU-SR.V.P\'S OFFIC', '', '25001056', 'Pandey Rajesh KUMAR', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(74, '200564', 'Nitin PUNDALIK Choudhari', 'Sr. Manager- Maintenance, Foundry PU', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '', '9833924956', 'apoorva.ajmera@intellectsoftsol.com', 'Foundry P U - Foundry Maintainance', '', '23197855', 'Patil Amar BABASAHEB', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(75, '200567', 'K B Merulingkar', 'DGM - Strategic Sourcing', 'STRATEGIC SOURCING', 'COMMON SERVICES - PURCHASING', 'MUM-KND-AFS(AD)', '91', '', '', '9820518962', 'apoorva.ajmera@intellectsoftsol.com', 'Sourcing & Pricing - CASTING - Power tra', '', '25001056', 'Patil Bhupesh R', 'contact@intellectsoftsol.com', '25001056', 'Jain Varun', 'jobs@intellectsoftsol.com', '211339', 'Patil Akshaya', 'alliance@intellectsoftsol.com', ''),
(76, '200568', 'Sandeep Rege', 'DGM- Axle PU and Heat Treatment', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9967530196', 'apoorva.ajmera@intellectsoftsol.com', 'Axle P U - Axle Maintainance', '', '25001415', 'Chavan Pandurang', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(77, '200575', 'M O Ramos', 'Manager - IT Helpdesk Services', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'Mumbai Worli-AD', '+91', '', '0', '9867183493', 'apoorva.ajmera@intellectsoftsol.com', 'AS IT-NON SAP(PERSONNEL COST)', '0', '23197853', 'Pokle Deven', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(78, '200610', 'Deepak NANUBHAI Tailor', 'Head - CQA, Kandivali AD', 'QUALITY', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '', '9833491381', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli-Central Quality Assurance', '0', '23171152', 'Matodkar R N', 'contact@intellectsoftsol.com', '23171152', 'Azad Jaya', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(79, '200611', 'U S Joshi', 'General Manager - Sustainability', '--', 'SUSTAINABILITY', 'MUM-KND-AFS(AD)', '91', '', '', '9987512344', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli - Energy Management', '', '23190698', 'Kalra Vijay', 'contact@intellectsoftsol.com', '23190698', 'Singh Sunil Nirpati', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(80, '200616', 'Pratap DATTATRAYA Pandit', 'Sr. General Manager', 'MANUFACTURING (MFG)', 'FARM DIVISION', 'MUMBAI-KND-FD', '91', '', '', '9867159979', 'apoorva.ajmera@intellectsoftsol.com', 'VP- MFG. Kandivali', '', '23080476', 'Shenoy K G', 'contact@intellectsoftsol.com', '23080476', 'Basu Snigdha', 'jobs@intellectsoftsol.com', '210778', 'Dalvi Riya', 'alliance@intellectsoftsol.com', ''),
(81, '200628', 'Sunil Kulkarni', 'VP -Manufacturing Operations(Knd)', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9892527857', 'apoorva.ajmera@intellectsoftsol.com', 'Vice President Operations - Kandivli', '', '200823', 'Kalra Vijay', 'contact@intellectsoftsol.com', '200823', 'Joshi Atul', 'jobs@intellectsoftsol.com', '207971', 'Wadhera Rajan', 'alliance@intellectsoftsol.com', ''),
(82, '200630', 'A N Kelkar', 'Senior Manager- PIPM', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9820673023', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli Poka Yoke', '', '23197855', 'Rokade Vijay SITARAM', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(83, '200631', 'Girish DIGAMBAR Sane', 'Sr. Manager - Pokayoke PIPM', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '', '9820793706', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli Poka Yoke', '', '23197855', 'Rokade Vijay SITARAM', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(84, '200647', 'M M Kelkar', 'Dy.General Manager-Supply Chain Mang.', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9820380825', 'apoorva.ajmera@intellectsoftsol.com', 'Supply Chain Mgt - AS Kandivli', '', '25001415', 'Kulkarni Sunil', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(85, '200650', 'Haresh Parasramani', 'DGM - IMCR', 'ENGINEERING & COMPONENT DEVELOPMENT', 'POWER TRAIN DIVISION', 'MUM-KND-AFS(AD)', '91', '', '', '9920037093', 'apoorva.ajmera@intellectsoftsol.com', 'PTD-IMCR', '', '23163630', 'R Padmanabhan', 'contact@intellectsoftsol.com', '23163630', 'Iyer Vignesh Subramanian', 'jobs@intellectsoftsol.com', '23061132', 'Wahab Ashraf ABDUL', 'alliance@intellectsoftsol.com', ''),
(86, '200652', 'SANDIP DEODATTA PRIOLKAR', 'DGM- CMD', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9892218774', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli-Maintainance Central', '', '23197855', 'Patil Amar BABASAHEB', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(87, '200660', 'Pratap Parab', 'GM- Special Process DAC Axle (AD CDMM)', 'CDMM', 'AUTOMOTIVE DIVISION', 'MUM-KND-AFS(AD)', '91', '', '', '9004087563', 'apoorva.ajmera@intellectsoftsol.com', 'CDMM-Special Process', '', '25001550', 'Verma Lalit', 'contact@intellectsoftsol.com', '25001550', 'Mishra Vinay Rakesh', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(88, '200663', 'R N Matodkar', 'Head – Product Quality, Kandivali AD', 'QUALITY', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '0', '9967068215', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli - Supplier Quality', '0', '23171152', 'Kulkarni Abhay WAMANRAO', 'contact@intellectsoftsol.com', '23171152', 'Azad Jaya', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(89, '200664', 'Uday Kale', 'Sr. Manager - CQA', 'QUALITY', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9892543577', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli-Central Quality Assurance', '', '25001415', 'Tailor Deepak NANUBHAI', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(90, '200666', 'J W Vaity', 'Manager- Export Dispatch, Vehicle PU', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '0', '9969540150', 'apoorva.ajmera@intellectsoftsol.com', 'Vehicle P U -Vehicle PDI & Rectification', '0', '25001415', 'Brahmadande Shashank JINADATTA', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(91, '200671', 'Gajanan BABAN Vichare', 'Sr. Manager - Canteen ER', 'EMPLOYEE RELATIONS', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '91', '', '', '9967591063', 'apoorva.ajmera@intellectsoftsol.com', 'Kandivli-Canteen', '', '23197855', 'Likhite Abhay', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(92, '200672', 'Deepak Bhise', 'General Manager', 'SUPPLY CHAIN MANAGEMENT', 'TWO WHEELERS', 'Pithampur', '91', '', '', '7509064320', 'apoorva.ajmera@intellectsoftsol.com', 'MAHINDRA TWO WHEELER (MTWL)', '', '23141859', 'Tuli Vijay', 'contact@intellectsoftsol.com', '23141859', 'Utkarsh', 'jobs@intellectsoftsol.com', '205064', 'Mohanty Luna', 'alliance@intellectsoftsol.com', ''),
(93, '200673', 'Vijay Mewada', 'Sr. Mgr- Improvement & IE, Foundry PU\"', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '0', '9967596745', 'apoorva.ajmera@intellectsoftsol.com', 'Foundry P U - Foundry Engineering', '0', '23197855', 'Patil Amar BABASAHEB', 'contact@intellectsoftsol.com', '23197855', 'Bharti Nimisha', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(94, '200674', 'Ganesh Sant', 'Sr. Manager- Buying , SCM', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '0', '9867695818', 'apoorva.ajmera@intellectsoftsol.com', 'Supply Chain Mgt - AS Kandivli', '0', '25001415', 'Kelkar M M', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(95, '200675', 'Shashank Kangutkar', 'Senior Manager -Business Excellence - AD', 'BE-AUTO BUSINESS', 'BUSINESS EXCELLENCE', 'MUM-KND-AFS(AD)', '91', '', '', '9892679153', 'apoorva.ajmera@intellectsoftsol.com', 'Mahindra Quality Systems ..(A.S)', '', '23192566', 'Pitre Nitin RAMAKRISHNA', 'contact@intellectsoftsol.com', '23192566', 'Telange Devyani', 'jobs@intellectsoftsol.com', '201850', 'Dravid Somesh', 'alliance@intellectsoftsol.com', ''),
(96, '200676', 'Shriniwas VISHWANATH Sapar', 'Sr. Manager-DE-Special Process', 'CDMM', 'AUTOMOTIVE DIVISION', 'MUM-KND-AFS(AD)', '91', '', '', '9869060485', 'apoorva.ajmera@intellectsoftsol.com', 'CDMM-Special Process', '', '25001550', 'Parab Pratap', 'contact@intellectsoftsol.com', '25001550', 'Mishra Vinay Rakesh', 'jobs@intellectsoftsol.com', '23061948', 'Sardesai Jayant VASANT', 'alliance@intellectsoftsol.com', ''),
(97, '200679', 'Suhas DINANATH Bhat', 'Sr. Manager- Logistics, MDC- SCM', 'MANUFACTURING(MFG)', 'AUTOMOTIVE DIVISION', 'MUMBAI-KND-AD', '+91', '', '0', '9867599087', 'apoorva.ajmera@intellectsoftsol.com', 'Supply Chain Mgt - AS Kandivli', '0', '25001415', 'Kelkar M M', 'contact@intellectsoftsol.com', '25001415', 'Mishra Pranay', 'jobs@intellectsoftsol.com', '23171152', 'Azad Jaya', 'alliance@intellectsoftsol.com', ''),
(98, '200680', 'J Rajaraman', 'Sr. Engineer -Electrical & Electronics', 'PRODUCT DEVELOPMENT', 'AUTOMOTIVE DIVISION', 'Chennai-MRV-AD', '91', '', '', '9840182479', 'apoorva.ajmera@intellectsoftsol.com', 'Electrical & Electronics (Incl Hybrid)', '', '25001417', 'KR Pruthiviraj', 'contact@intellectsoftsol.com', '25001417', 'Das Samar Jyoti', 'jobs@intellectsoftsol.com', '23102842', 'R Srikumar', 'alliance@intellectsoftsol.com', ''),
(99, '13069', 'Ramchandra SHANKAR Date', 'Executive IT Delivery - Core SAP', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'Pune', '+91', '', '0', '9975843064', 'apoorva.ajmera@intellectsoftsol.com', 'AUTO SRCTOR IT (AFS)', '0', '23197853', 'Chavan Sanjay', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', ''),
(100, '13072', 'Girish Paliwal', 'Dy Manager IT Delivery - Core SAP', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', 'Pune', '+91', '', '0', '9226873399', 'apoorva.ajmera@intellectsoftsol.com', 'AUTO SRCTOR IT (AFS)', '0', '23197853', 'Chavan Sanjay', 'contact@intellectsoftsol.com', '23197853', 'Mishra Neelkamal', 'jobs@intellectsoftsol.com', '23096343', 'Kalamkar Chetan', 'alliance@intellectsoftsol.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `vs_business`
--

CREATE TABLE `vs_business` (
  `business_id` int(10) NOT NULL,
  `department_code` varchar(15) DEFAULT NULL,
  `department_name` varchar(75) NOT NULL,
  `cost_center` varchar(500) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_business`
--

INSERT INTO `vs_business` (`business_id`, `department_code`, `department_name`, `cost_center`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(1, '111', 'DBC', 'Cost Center 1', 99, '2019-01-07 16:50:53', NULL, NULL, 1),
(3, 'HR', 'HR Department', '23233', 99, '2019-01-15 06:34:25', NULL, NULL, 1),
(4, 'ADMN', 'Admin Department', '1111122222', 99, '2019-01-16 08:01:35', 99, '2019-01-16 08:01:56', 1),
(5, 'CE', 'Computer Enginerring', 'CEDEPT', 99, '2019-01-23 15:53:48', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_businessunit`
--

CREATE TABLE `vs_businessunit` (
  `bu_id` int(10) NOT NULL,
  `bu_code` varchar(15) NOT NULL,
  `bu_name` varchar(75) NOT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_businessunit`
--

INSERT INTO `vs_businessunit` (`bu_id`, `bu_code`, `bu_name`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(1, 'AD1', 'Automotive Division New', 1, '2018-11-16 12:29:22', 99, '2018-11-21 02:11:33', 1),
(2, 'FD', 'Farm Division', 1, '2018-11-16 12:29:39', NULL, NULL, 1),
(17, 'ABC', 'ABC Company', 99, '2018-11-21 01:49:15', NULL, NULL, 1),
(18, 'Buz', 'Business 1', 99, '2019-01-22 18:00:34', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_charge`
--

CREATE TABLE `vs_charge` (
  `charge_id` int(11) UNSIGNED NOT NULL,
  `charge_code` varchar(255) NOT NULL,
  `charge_name` varchar(255) NOT NULL,
  `agency_code` int(11) UNSIGNED NOT NULL,
  `charge_value` float(4,2) NOT NULL DEFAULT '0.00',
  `charge_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not Active; 1 = Active',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_charge`
--

INSERT INTO `vs_charge` (`charge_id`, `charge_code`, `charge_name`, `agency_code`, `charge_value`, `charge_status`, `updated_at`, `created_at`) VALUES
(1, 'C11', 'Charge 11', 1, 10.01, 1, '2019-01-07 18:03:05', '2018-12-15 09:04:51'),
(2, 'MO07', 'Monday', 0, 7.00, 1, '2019-01-07 12:18:40', '2019-01-07 12:18:40'),
(3, 'ARM', '0 to 250 Grams', 0, 12.00, 0, '2019-01-16 03:04:42', '2019-01-16 03:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `vs_ci_sessions`
--

CREATE TABLE `vs_ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_ci_sessions`
--

INSERT INTO `vs_ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('jkgckf7ci39i4dkb7q38ets1ebj192qb', '::1', 1547879413, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373837393430383b),
('fdpnsurefasasdg8rlb3q4mtf45e3gs8', '::1', 1547879531, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373837393533313b),
('0c9jvg71rjp8bvfj09glv3evemk495bk', '::1', 1547879798, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373837393739383b),
('51bqs9pii3mnsr7iosd4d8r2vlub9qcp', '::1', 1547880255, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838303234363b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838303235353b),
('apovush57v8p6ldqgdouqetd9ros2elu', '::1', 1547880746, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838303734323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838303734363b),
('opeq90827fqkqr06ut18pr8e9lr9mn6a', '::1', 1547881095, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838313037343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838313039353b),
('okt4mh2tflaohgl6j5dnqc8q5c1ik4d9', '::1', 1547882052, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838313933393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838323035323b),
('6f0gm54hqqru04pb33ve35j5flqcd87q', '::1', 1547882308, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838323238353b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838323330383b),
('f83mc102dd6qolrf10jdd3nbumsc3knd', '::1', 1547883160, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838333136303b),
('ni44rosp4vifj66od56a1d5assagkgpm', '::1', 1547883601, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838333533393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339313b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838333630313b),
('gauoi0h7kdv9d5q8ju40jk3gob1fo6hd', '::1', 1547884054, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838343035343b),
('npsl1hmdl4pssthsrcjk42vddjtlse1c', '::1', 1547884083, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838343035343b755f646174617c613a323a7b733a333a22756964223b733a333a22313336223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a333a22313336223b733a31323a22656d705f757365726e616d65223b733a383a226d61696c6465736b223b733a393a22656d705f656d61696c223b733a31383a226d61696c6465736b40676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a343a224d61696c223b733a31323a22656d705f6c6173746e616d65223b733a343a224465736b223b733a343a22726f6c65223b733a383a224465736b55736572223b733a363a226c6f675f6964223b693a3339323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838343038333b),
('57upt5vm1ukigk2dv5r2m37oa8bb38a5', '::1', 1547884985, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373838343737353b755f646174617c613a323a7b733a333a22756964223b733a333a22313336223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a333a22313336223b733a31323a22656d705f757365726e616d65223b733a383a226d61696c6465736b223b733a393a22656d705f656d61696c223b733a31383a226d61696c6465736b40676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a343a224d61696c223b733a31323a22656d705f6c6173746e616d65223b733a343a224465736b223b733a343a22726f6c65223b733a383a224465736b55736572223b733a363a226c6f675f6964223b693a3339323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373838343938353b),
('avaqj387ljo03fbegpp00qjfadam2a6o', '::1', 1547912891, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373931323839313b),
('hlog3bj904qjobo6khtqm68fn8jse5cr', '::1', 1547913294, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373931333139333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339333b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373931333239343b),
('f96gu8eacbqtnsm93apjrf9iq2kuofvl', '::1', 1547942243, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934323234333b),
('pteb3kh32folgh78ph8s8tb1rav0g098', '::1', 1547942750, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934323735303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934323735303b),
('bfkr7ali6rpg5msqda7epo6uod3c3fnb', '::1', 1547943338, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934333331333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934333333383b),
('bljebtlrkpe8tpgamkckdhc4ieoimn1d', '::1', 1547943801, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934333634353b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934333830313b),
('q9jbg2oqf2bb7uhlfus0q7jrfst70giq', '::1', 1547944327, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934343037303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934343332373b657863657074696f6e7c733a31363a22706c656173652074727920616761696e223b5f5f63695f766172737c613a313a7b733a393a22657863657074696f6e223b733a333a226f6c64223b7d),
('6ue50qhsjri67jcafd57oue6u1m2p1b8', '::1', 1547944555, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934343431343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934343535353b6d6573736167657c733a31373a2273617665207375636365737366756c6c79223b5f5f63695f766172737c613a313a7b733a373a226d657373616765223b733a333a226f6c64223b7d),
('j7gmuo45edo05nk602rl2col8jsgcm5c', '::1', 1547945380, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934353130323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934353338303b),
('59rciph67bj0e4gmv23ee1jl5ogvf64j', '::1', 1547945685, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373934353437323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373934353638353b),
('go3aoiqciu7smrvuah3kkb11njt147hg', '::1', 1547964814, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936343831333b),
('lamn2p83h4n94hq0rfuic24vuueee21u', '::1', 1547965402, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936353132383b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936353430323b),
('8vd59k7ona76g9r5kfom3r7lffe2a39g', '::1', 1547965473, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936353433363b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936353437333b),
('apma6p83c6cnpo9r2nghdpg8mg15kgun', '::1', 1547966175, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936363033343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936363137353b),
('l6jthkvini7658kul4d7hbqdek8u5rl8', '::1', 1547966518, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936363530333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936363531383b),
('khp7he273r9i4jvu67ei49n4b0i9d5cl', '::1', 1547967569, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936373332393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936373536393b6d6573736167657c733a31373a2273617665207375636365737366756c6c79223b5f5f63695f766172737c613a313a7b733a373a226d657373616765223b733a333a226f6c64223b7d),
('aatshr4k0anv7c4bgud6bljj0r00fcek', '::1', 1547967665, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936373633323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936373636353b),
('ldgf4845gqm6md83erjkadlkeajceedc', '::1', 1547968039, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936383032383b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936383033353b),
('eimlcvn5sevo2m36e1sjs478f4glphf5', '::1', 1547968536, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936383532363b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936383533363b),
('r4dbgclnm207fpqp1omq9q2ih9o581af', '::1', 1547968889, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936383838393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936383838393b),
('lopkspiegm7usrbvscm3tkvk45ap4vpr', '::1', 1547969458, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373936393435343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339353b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373936393435343b),
('t4qjcalpj3tps269nn6q8l6i3ujog8dd', '::1', 1547971788, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373937313738383b),
('ld040g4hmg6b5dlld9qll54fmeb60rmd', '::1', 1547972823, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373937323637343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3339363b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534373937323832333b),
('oa34s14mp0ie3f28mq3vevjs6vfmohvr', '::1', 1547975104, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373937353130343b),
('hru13n7i2kmcm6ta0ulq5l86as25tq07', '::1', 1547978336, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534373937383333363b),
('t4tqr3cq8ceu4dk2641jptqjf5mlcu0h', '::1', 1548041373, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034313337333b),
('kqhvnecm9l7geh9v2qle5e9uc2n4feer', '::1', 1548044919, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034343931383b),
('h3s61u901aqhtn7lcv11eosoerv34mln', '::1', 1548046084, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034363038343b),
('6uedj02vsgi4csd9ip2hqum0p85d2opi', '::1', 1548046994, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034363831323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034363939343b),
('9b46jo6fvrmau4r51hfsf29f40d0abkr', '::1', 1548047395, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034373230303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034373339353b),
('2eucrsk3b0cj2obh6b5qlcqmsllrj3rh', '::1', 1548047860, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034373730353b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034373836303b),
('m1s7msdo4vrm4491k8nb2po2odo88mhs', '::1', 1548048335, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034383133323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034383333353b),
('308p5l24umei2ji00pi4i06db0d8i38h', '::1', 1548048490, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034383439303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034383439303b),
('c6v4ahu8mlqdquog6a3ckgc4fjka96p2', '::1', 1548049236, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034393032383b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034393233363b),
('7cv5i7srpmo5eif03v7tgfgn57ekqdai', '::1', 1548049632, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034393335303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034393633323b),
('s560k2gr86uhvu86fee3ge5nkjlilnlc', '::1', 1548049990, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383034393730323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383034393835323b),
('84si1eem0ft1mp6l4t60j0edjvo0i3gc', '::1', 1548050266, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035303131363b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383035303236363b),
('80qrct276knap5tegldjot2ncstnhmvk', '::1', 1548050585, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035303538353b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383035303538353b),
('krr211aboavin3tv6coecqnkmd102g1a', '::1', 1548051214, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035313036393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383035313231343b),
('scstodb4pi2rs5q6pmup8458nr96oig7', '::1', 1548051641, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035313530343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430303b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383035313634313b),
('fhpmq1v49ieiug1gtttc7i6p09p0p2q1', '::1', 1548051946, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035313934363b),
('cprbg0aq9mk4aj879e59ijnirjaip9ol', '::1', 1548055938, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035353933373b),
('9vncg0teaa0nh8pvlcnca2h8lpf5grav', '::1', 1548056677, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383035363637373b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383035363637373b),
('1e0o5g827sadb5ok2k92ftvjb1vgpsrv', '::1', 1548068571, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383036383334363b),
('76mm1o3ik8lq9qo2cmef69201jkfaos8', '::1', 1548069904, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383036393930343b),
('obgqhel80agu5s17fctjo74jtuabodn3', '::1', 1548070554, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383037303239343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383037303535343b),
('eb63iphgu12t8g8f42b4d8c28ue39mgq', '::1', 1548070805, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383037303830353b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3430343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383037303830353b),
('vh7g947asnk5brrf8ski8p2dntbpbfmc', '::1', 1548083918, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383038333931383b),
('pbreorohj7qcfc5qir916anterdcc5u3', '::1', 1548092954, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383039323930303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('sdht6ll1g7g2lo6kou2fnn82glbgcu71', '::1', 1548093298, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383039333239313b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('1dt9as56b5s8mtpgoq5oqovkfmoboecv', '::1', 1548094417, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383039343431333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('l5ng0vi55epvotfpqee33rk3dt579ful', '::1', 1548175899, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383137353731393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('j89o6m93qv8n3itc150fk3kg5no3j873', '::1', 1548178380, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383137383337373b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('64u6a674vpeo69mmnbnc6dfadnrsddb7', '::1', 1548180013, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383138303031303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('iei369tnqkfuq5v7kab719kb88klthno', '::1', 1548207652, ''),
('sdpkoav5hh2v25330800bh1imt2g8kv5', '::1', 1548257840, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383235373833393b),
('36687seekhaugqr7e662j4sd818pho6k', '::1', 1548257844, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383235373834303b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('4h4ra9qp80e9359b1ecos4ib4quafqc6', '::1', 1548259139, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383235393133393b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383235383833373b),
('u54028qm49548o97qahsqn4h6ntmdqal', '::1', 1548260228, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236303232383b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383235393339313b),
('c0ipicab623sv2rat887ou3aimldlbk7', '::1', 1548264436, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236343433363b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431323b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383236303232383b6d6573736167657c733a31393a22757064617465207375636365737366756c6c79223b5f5f63695f766172737c613a313a7b733a373a226d657373616765223b733a333a226f6c64223b7d),
('9mfhfsb34ri2h14kstlpco1vsdsea7dc', '::1', 1548264443, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236343433373b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d);
INSERT INTO `vs_ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('h38lqqb93akkfde74sekph8i109562qd', '::1', 1548265561, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236353536313b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431333b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383236353139323b),
('ahcqk06g6tn50lkqblm121imo1nv6maq', '::1', 1548265914, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236353931343b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431333b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383236353835303b),
('lq7qca1blmg6qu4rv22hnklrig3hobjq', '::1', 1548268872, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236383837323b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431333b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383236353933383b6d6573736167657c733a31393a22757064617465207375636365737366756c6c79223b5f5f63695f766172737c613a313a7b733a373a226d657373616765223b733a333a226f6c64223b7d),
('j4ti7i5kk6mtq6papnc5b37gkppne822', '::1', 1548268877, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236383837333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d),
('9e7cdmnoo9pci0jh13vhr1aitp4iqf1d', '::1', 1548269134, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534383236383837333b755f646174617c613a323a7b733a333a22756964223b733a323a223939223b733a31323a2269735f6c6f676765645f696e223b623a313b7d76735f63695f73657373696f6e7c623a313b6c6f676765645f696e7c613a31323a7b733a31313a22656d705f757365725f6964223b733a323a223939223b733a31323a22656d705f757365726e616d65223b733a393a22696e74656c6c656374223b733a393a22656d705f656d61696c223b733a32333a2261646d696e6973747261746f7240676d61696c2e636f6d223b733a31333a22656d705f66697273746e616d65223b733a353a225375706572223b733a31323a22656d705f6c6173746e616d65223b733a353a2241646d696e223b733a343a22726f6c65223b733a353a2241646d696e223b733a363a226c6f675f6964223b693a3431343b733a383a2276656e646f726964223b4e3b733a373a2268726c6f67696e223b693a303b733a373a2269735f6c646170223b733a313a2230223b733a343a2264657074223b733a303a22223b733a393a22657874656e73696f6e223b4e3b7d74696d657374616d707c693a313534383236393133343b);

-- --------------------------------------------------------

--
-- Table structure for table `vs_courier`
--

CREATE TABLE `vs_courier` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `to_type` varchar(255) NOT NULL,
  `to_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_courier`
--

INSERT INTO `vs_courier` (`id`, `from_name`, `to_type`, `to_name`, `created_at`) VALUES
(1, 'intellect', 'External', 'asas', '2018-12-24 16:41:39'),
(2, 'intellect', 'Internal', 'test', '2018-12-24 16:42:35'),
(3, 'intellect', 'Internal', 'sasa', '2018-12-24 16:43:38'),
(4, 'intellect', 'Internal', 'radhe', '2018-12-24 16:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `vs_courier_agency`
--

CREATE TABLE `vs_courier_agency` (
  `agency_id` int(11) UNSIGNED NOT NULL,
  `agency_code` varchar(100) NOT NULL,
  `agency_sap_code` varchar(255) DEFAULT NULL,
  `agency_name` varchar(255) NOT NULL,
  `agency_address` varchar(255) NOT NULL,
  `agency_person_name` varchar(255) NOT NULL,
  `agency_mobile_number` varchar(15) NOT NULL,
  `agency_email_address` varchar(100) NOT NULL,
  `agency_delivery_locations` varchar(255) DEFAULT NULL,
  `agency_tracking_url` varchar(255) DEFAULT NULL,
  `agency_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not Active; 1 = Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_courier_agency`
--

INSERT INTO `vs_courier_agency` (`agency_id`, `agency_code`, `agency_sap_code`, `agency_name`, `agency_address`, `agency_person_name`, `agency_mobile_number`, `agency_email_address`, `agency_delivery_locations`, `agency_tracking_url`, `agency_status`, `created_at`) VALUES
(1, 'DTDC', '343434', 'DTDC Courier Service - XYZ', 'gfhfghjgjgj 334343, 343434 ,fdsfffds , sdfdsfdsf ,dfdsfdsf ', 'Agency Person1', '105410515050', 'himanshu@gmail.com', 'Loc 12', 'http://localhost/mail_desk/Request/index', 1, '2019-01-08 19:08:13'),
(2, 'DTDC', '343434', 'DTDC', 'gdfgdg', 'Agency Person1', '7575007475', 'himanshuvarun@gmail.com', 'Loc 12', NULL, 1, '2019-01-07 12:44:42'),
(3, 'MUTI', '54564', 'Maruti Courier', 'Testing', 'Agency Person1', '07575 007475', 'himanshuvarun@gmail.com', 'Loc 12', 'https://google.co.in', 1, '2019-01-08 19:10:03'),
(4, 'FE109', 'FFD4560', 'FedEx', 'Testing', 'Fe1', '4645465', 'fedexsupport@fedex.com', 'Loc 1', 'https://google.co.in', 1, '2019-01-08 13:41:03'),
(5, 'ARM', '23232323', 'Aramex India Pvt. Ltd.', '2nd Floor, Solitaire Business Park, Opp. Mirador Hotel, Chakala, J.B. Nagar, Andheri (E), Mumbai 4000069, Maharashtra, India', 'Biju Thomas', '9322047077', 'apoorvaa@gmail.com', '12', 'https://www.aramex.com/tracking.aspx', 1, '2019-01-16 08:07:41'),
(6, 'test', '', 'test agenct', 'fdsfdsf sdf sfsddfsdfs', 'sfsdfsdf', 'sdfdsfsdfs', 'fdsfsdfsfsd', '', 'dsfdsfdsfs', 1, '2019-01-16 02:38:31'),
(7, 'abc', 'abc', 'abc', 'abc', 'abc', '9970014417', 'abc@gmail.com', '', 'abc.com', 1, '2019-01-19 19:22:56'),
(8, 'Maruti-MH', 'MRTI-SAP', 'Shri Maruti Courier Pvt Ltd.', 'Kalwa Chowk, Near Surbhi complex, Ahmedabad', 'Manishbhai', '7778889990', 'manish@maruti.com', '', 'https://www.shreemaruticourier.com/track-your-shipment/', 1, '2019-01-23 10:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `vs_courier_request`
--

CREATE TABLE `vs_courier_request` (
  `req_id` int(11) NOT NULL,
  `tracking_code` varchar(25) NOT NULL,
  `mail_type` enum('Inward','Outward') DEFAULT NULL,
  `from_id` int(11) NOT NULL,
  `req_emp_id` int(10) NOT NULL,
  `req_emp_token` varchar(15) NOT NULL,
  `req_mod_of_delivery` varchar(15) NOT NULL,
  `req_courier` varchar(15) DEFAULT NULL,
  `req_emp_name` varchar(100) NOT NULL,
  `req_emp_dept` varchar(100) DEFAULT NULL,
  `req_emp_extension` varchar(50) DEFAULT NULL,
  `req_emp_costcenter` varchar(15) DEFAULT NULL,
  `req_emp_locationid` int(11) DEFAULT NULL,
  `req_emp_location` varchar(100) DEFAULT NULL,
  `req_emp_address` text NOT NULL,
  `req_emp_pincode` varchar(50) NOT NULL,
  `req_emp_type` varchar(50) DEFAULT NULL,
  `req_emp_telephone` varchar(50) NOT NULL,
  `req_emp_remarks` text NOT NULL,
  `req_receiever_type` varchar(50) NOT NULL,
  `req_receiever_emp_token` varchar(15) NOT NULL,
  `req_receiever_emp_name` varchar(100) NOT NULL,
  `req_receiever_emp_companyname` varchar(100) DEFAULT NULL,
  `req_receiever_emp_address` varchar(100) NOT NULL,
  `req_receiever_emp_city` varchar(50) DEFAULT NULL,
  `req_receiever_emp_pincode` varchar(50) NOT NULL,
  `req_receiever_telephone` varchar(50) NOT NULL,
  `req_receiever_remarks` varchar(500) DEFAULT NULL,
  `req_status` varchar(25) DEFAULT NULL,
  `req_unit` varchar(25) DEFAULT NULL,
  `req_weight` varchar(25) DEFAULT NULL,
  `req_agency` varchar(25) DEFAULT NULL,
  `req_fee` varchar(25) DEFAULT NULL,
  `req_awb_no` varchar(25) DEFAULT NULL,
  `req_datetime` varchar(50) DEFAULT NULL,
  `req_createdby` int(10) DEFAULT NULL,
  `req_createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `req_modifiedby` int(10) DEFAULT NULL,
  `req_modifieddate` datetime DEFAULT NULL,
  `req_submittedby` int(10) DEFAULT NULL,
  `req_submitteddate` datetime DEFAULT NULL,
  `req_maildesk_out_actionby` int(10) DEFAULT NULL,
  `req_maildesk_out_actiondate` datetime DEFAULT NULL,
  `req_maildesk_out_submittedby` int(10) DEFAULT NULL,
  `req_maildesk_out_submitteddate` datetime DEFAULT NULL,
  `req_maildesk_out_remark` varchar(100) DEFAULT NULL,
  `req_maildesk_in_actionby` int(10) DEFAULT NULL,
  `req_maildesk_in_actiondate` datetime DEFAULT NULL,
  `req_maildesk_in_submittedby` int(10) DEFAULT NULL,
  `req_maildesk_in_submitteddate` datetime DEFAULT NULL,
  `req_maildesk_in_remark` varchar(100) DEFAULT NULL,
  `req_maildesk_in_rec_datetime` datetime DEFAULT NULL,
  `req_delivery_takenby` int(10) DEFAULT NULL,
  `req_delivery_takenby_token` varchar(25) DEFAULT NULL,
  `req_delivery_takenby_name` varchar(100) DEFAULT NULL,
  `req_delivery_datetime` datetime DEFAULT NULL,
  `req_delivery_remark` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_courier_request`
--

INSERT INTO `vs_courier_request` (`req_id`, `tracking_code`, `mail_type`, `from_id`, `req_emp_id`, `req_emp_token`, `req_mod_of_delivery`, `req_courier`, `req_emp_name`, `req_emp_dept`, `req_emp_extension`, `req_emp_costcenter`, `req_emp_locationid`, `req_emp_location`, `req_emp_address`, `req_emp_pincode`, `req_emp_type`, `req_emp_telephone`, `req_emp_remarks`, `req_receiever_type`, `req_receiever_emp_token`, `req_receiever_emp_name`, `req_receiever_emp_companyname`, `req_receiever_emp_address`, `req_receiever_emp_city`, `req_receiever_emp_pincode`, `req_receiever_telephone`, `req_receiever_remarks`, `req_status`, `req_unit`, `req_weight`, `req_agency`, `req_fee`, `req_awb_no`, `req_datetime`, `req_createdby`, `req_createddate`, `req_modifiedby`, `req_modifieddate`, `req_submittedby`, `req_submitteddate`, `req_maildesk_out_actionby`, `req_maildesk_out_actiondate`, `req_maildesk_out_submittedby`, `req_maildesk_out_submitteddate`, `req_maildesk_out_remark`, `req_maildesk_in_actionby`, `req_maildesk_in_actiondate`, `req_maildesk_in_submittedby`, `req_maildesk_in_submitteddate`, `req_maildesk_in_remark`, `req_maildesk_in_rec_datetime`, `req_delivery_takenby`, `req_delivery_takenby_token`, `req_delivery_takenby_name`, `req_delivery_datetime`, `req_delivery_remark`) VALUES
(9, '', NULL, 99, 1, '10188', 'C', 'International', 'Umesh Agashe', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', NULL, NULL, NULL, '', '', '', '', '', 'Internal', '', 'Manas', NULL, '34 wall street block', NULL, 'M7D578', '9890663235', 'test', 'Submitted', 'kg', '5', '1', '300', NULL, '2019-01-01 22:18:10', NULL, '2019-01-21 13:45:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '', NULL, 99, 41, '13031', 'U', NULL, 'Satendra Pal', 'ENTERPRISE, MFG & CORP FNS', 'INFORMATION TECHNOLOGY', NULL, NULL, NULL, '', '', '', '', '', 'Internal', '', 'Manas', NULL, '34 wall street block', NULL, 'M7D578', '9890663235', 'test', 'Submitted', 'gram', '5', NULL, '500', NULL, '2019-01-02 04:17:52', NULL, '2019-01-21 13:45:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '', NULL, 99, 1, '10188', 'S', NULL, 'Umesh Agashe', 'RESEARCH & DEVELOPMENT', 'TWO WHEELERS', NULL, NULL, NULL, '', '', '', '', '', 'Internal', '10835', 'Vinay Ambekar', NULL, '', NULL, '', '9970511723', '', 'Submitted', 'gram', '10', NULL, '55', NULL, '2019-01-07 19:02:57', NULL, '2019-01-21 13:45:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '19000001', NULL, 99, 0, '', 'R', NULL, 'Sanjeev APPANNA Yaligar', 'RESEARCH & DEVELOPMENT', '', NULL, NULL, NULL, '', '', NULL, '', '', 'Internal', '', 'Apoorva Ajmera', NULL, '204, 2nd Floor, Mewad Industrial Premises, Patanwala Compound, LBS Road, Ghatkopar (W), Mumbai', NULL, '400086', '9322047077', 'Test remark', 'Submitted', 'gram', '50', NULL, '800', NULL, '2019-01-15 06:44:09', NULL, '2019-01-21 13:45:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'HRKAN199000002', NULL, 139, 0, '', 'O', NULL, 'Apoorva', '', '', NULL, NULL, NULL, '', '', NULL, '', '', 'External', '', 'Devang Ajmera', NULL, '204, Mewad, Patanwala Compound', NULL, '232323', '2323232323', 'Test', 'Submitted', NULL, NULL, NULL, NULL, NULL, '2019-01-15 07:16:41', NULL, '2019-01-21 13:45:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vs_emp_details`
--

CREATE TABLE `vs_emp_details` (
  `emp_id` int(10) UNSIGNED NOT NULL,
  `emp_username` varchar(100) NOT NULL,
  `emp_password` varchar(200) DEFAULT NULL,
  `emp_firstname` varchar(50) NOT NULL,
  `emp_lastname` varchar(50) NOT NULL,
  `emp_locationid` varchar(50) NOT NULL,
  `emp_departmentid` varchar(250) NOT NULL,
  `costcenter` varchar(250) DEFAULT NULL,
  `emp_email` varchar(100) NOT NULL,
  `emp_role` varchar(50) NOT NULL,
  `vendorid` int(11) DEFAULT NULL,
  `emp_area` varchar(100) DEFAULT NULL,
  `emp_status` enum('Active','Inactive') NOT NULL,
  `is_ldap` enum('0','1') NOT NULL,
  `is_logedin` enum('0','1') NOT NULL DEFAULT '0',
  `emp_otp` varchar(8) DEFAULT NULL,
  `emp_verify` int(11) NOT NULL DEFAULT '0',
  `emp_dept` varchar(250) DEFAULT NULL,
  `emp_mobile` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_emp_details`
--

INSERT INTO `vs_emp_details` (`emp_id`, `emp_username`, `emp_password`, `emp_firstname`, `emp_lastname`, `emp_locationid`, `emp_departmentid`, `costcenter`, `emp_email`, `emp_role`, `vendorid`, `emp_area`, `emp_status`, `is_ldap`, `is_logedin`, `emp_otp`, `emp_verify`, `emp_dept`, `emp_mobile`) VALUES
(99, 'intellect', '$2y$10$eBrkgzypwAlN9kn6pBWN2eDTcZWEUtvqAnm5QxeYvk.wh2b16c26i', 'Super', 'Admin', '', '', NULL, 'administrator@gmail.com', 'Admin', NULL, '', 'Active', '0', '0', '778893', 1, '1', NULL),
(135, '12660', '$2y$10$6YnrO3bMpKqiZDQrNwICOe6g9EMwMMXkj48zWR.avOW.Mu3fpFOPq', 'Shrikant', 'Kulkarni', '', '', NULL, 'employee@gmail.com', 'Employee', NULL, NULL, 'Active', '0', '0', NULL, 1, NULL, NULL),
(136, 'maildesk', '$2y$10$eBrkgzypwAlN9kn6pBWN2eDTcZWEUtvqAnm5QxeYvk.wh2b16c26i', 'Mail', 'Desk', '', '', NULL, 'maildesk@gmail.com', 'DeskUser', NULL, '', 'Active', '0', '0', '778893', 1, NULL, NULL),
(137, 'maildeskboy', '$2y$10$LV6Ps5zKH1/quu/DKmBlju3G96qQ74N9ZNV2hpBkqGg7MGjXjxMHu', 'Superman', 'Oh', '', '', NULL, 'deskboy@gmail.com', 'DeskUser', NULL, NULL, 'Active', '0', '0', '332925', 1, NULL, NULL),
(138, '123456', '$2y$10$AJryzOq5hkqFYKxHrIeiv.fnNyfV/QzQ/hDq2EmKb08Bre67foeyq', 'himanshu', 'upadhyay', '', '', NULL, 'himanshu@gmail.com', 'Employee', NULL, NULL, 'Active', '0', '0', '625691', 1, NULL, NULL),
(139, '222222', '$2y$10$eBrkgzypwAlN9kn6pBWN2eDTcZWEUtvqAnm5QxeYvk.wh2b16c26i', 'Apoorva', 'Ajmera', '1', '3', NULL, 'apoorvaa@gmail.com', 'Employee', NULL, NULL, 'Active', '0', '0', '226921', 1, NULL, NULL),
(140, '12742', '$2y$10$eBrkgzypwAlN9kn6pBWN2eDTcZWEUtvqAnm5QxeYvk.wh2b16c26i', 'Sujit', 'Ghosh', '20', '4', '23232', 'sujittest@yahoo.com', 'Employee', NULL, NULL, 'Active', '0', '0', NULL, 1, NULL, NULL),
(141, '123456789', '$2y$10$rXFPW1LkfkM3WOir7ycNReWuvjlXdx6M73WBP/ujbuxH2omDf.7yy', 'Sagar', 'Kawade', '20', '3', 'abc', 'sagar.kawade@intellectsoftsol.com', 'Employee', NULL, NULL, 'Active', '0', '0', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vs_escalation_mst`
--

CREATE TABLE `vs_escalation_mst` (
  `vs_escmstid` int(10) NOT NULL,
  `vs_esc_empid` int(10) DEFAULT NULL,
  `vs_esc_emptoken` varchar(15) NOT NULL,
  `vs_esc_empname` varchar(100) NOT NULL,
  `vs_esc_empemail` varchar(100) NOT NULL,
  `vs_esc_role` enum('Central HR','SSU','Admin') NOT NULL,
  `vs_esc_locids` varchar(100) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_esc_hr_log`
--

CREATE TABLE `vs_esc_hr_log` (
  `esc_hr_logid` int(11) NOT NULL,
  `esc_for_reqid` int(11) NOT NULL,
  `esc_subject` varchar(100) DEFAULT NULL,
  `esc_to_emailids` varchar(400) NOT NULL,
  `esc_cc_emailids` varchar(400) DEFAULT NULL,
  `esc_no` tinyint(4) NOT NULL,
  `esc_triggereddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `esc_body_text` varchar(8000) DEFAULT NULL,
  `esc_is_sent` bit(1) DEFAULT NULL,
  `esc_sent_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_esc_vendor_log`
--

CREATE TABLE `vs_esc_vendor_log` (
  `esc_vendor_logid` int(11) NOT NULL,
  `esc_for_reqid` int(11) DEFAULT NULL,
  `esc_subject` varchar(100) DEFAULT NULL,
  `esc_to_emailids` varchar(500) DEFAULT NULL,
  `esc_cc_emailids` varchar(500) DEFAULT NULL,
  `esc_no` tinyint(4) DEFAULT NULL,
  `esc_triggereddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `esc_body_text` varchar(8000) DEFAULT NULL,
  `esc_is_sent` bit(1) DEFAULT NULL,
  `esc_sent_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_franking`
--

CREATE TABLE `vs_franking` (
  `franking_id` int(10) NOT NULL,
  `finacial_id` int(11) NOT NULL,
  `f_month` varchar(15) DEFAULT NULL,
  `f_year` varchar(75) DEFAULT NULL,
  `f_value` varchar(500) DEFAULT NULL,
  `f_locationids` varchar(500) DEFAULT NULL,
  `transaction_dt` datetime DEFAULT NULL,
  `reference_no` varchar(200) DEFAULT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_franking`
--

INSERT INTO `vs_franking` (`franking_id`, `finacial_id`, `f_month`, `f_year`, `f_value`, `f_locationids`, `transaction_dt`, `reference_no`, `remark`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(1, 0, '4', '2019', '10000', '9', NULL, NULL, NULL, 99, '2019-01-07 16:51:25', NULL, NULL, 1),
(2, 0, '5', '2019', '196', '2', NULL, NULL, NULL, 99, '2019-01-07 18:28:00', NULL, NULL, 1),
(3, 0, '2', '2019', '50000', NULL, NULL, NULL, NULL, 99, '2019-01-16 08:51:22', NULL, NULL, 1),
(4, 0, '2', '2019', '100000', NULL, NULL, NULL, NULL, 99, '2019-01-16 08:51:51', 99, '2019-01-16 08:52:07', 1),
(5, 0, '1', '2019', '100000', '12', NULL, NULL, NULL, 99, '2019-01-16 13:17:33', NULL, NULL, 1),
(6, 0, '1', '2019', '50000', '20', NULL, NULL, NULL, 99, '2019-01-16 13:38:54', NULL, NULL, 1),
(7, 0, NULL, NULL, '200', NULL, '0000-00-00 00:00:00', '123456', 'jfdsklj jfklsdjkljfsadkj', 99, '2019-01-20 09:06:53', NULL, NULL, 1),
(8, 0, NULL, NULL, '59900', NULL, '0000-00-00 00:00:00', '33dfsdfdsf', 'Test remark for new deposit', 99, '2019-01-21 03:30:32', NULL, NULL, 1),
(9, 0, NULL, NULL, '19610', '3', '2019-01-23 00:00:00', 'Year-18_23', 'gdfgdfh...', 99, '2019-01-23 16:00:11', 99, '2019-01-23 17:52:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_ldap_settings`
--

CREATE TABLE `vs_ldap_settings` (
  `id` int(11) NOT NULL,
  `is_active` enum('0','1') NOT NULL,
  `server` varchar(50) NOT NULL,
  `tree` varchar(100) NOT NULL,
  `search` varchar(100) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_ldap_settings`
--

INSERT INTO `vs_ldap_settings` (`id`, `is_active`, `server`, `tree`, `search`, `modified_date`) VALUES
(1, '1', 'ldap://10.175.10.133', 'DC=corp,DC=agropharma,DC=com', '', '2018-03-27 07:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `vs_location`
--

CREATE TABLE `vs_location` (
  `location_id` int(10) NOT NULL,
  `business_id` int(11) NOT NULL,
  `location_code` varchar(15) DEFAULT NULL,
  `location_type` enum('Area Office','Corporate','Plant') NOT NULL,
  `location_name` varchar(75) NOT NULL,
  `location_address` varchar(500) DEFAULT NULL,
  `location_city` varchar(150) DEFAULT NULL,
  `location_pincode` varchar(10) DEFAULT NULL,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_location`
--

INSERT INTO `vs_location` (`location_id`, `business_id`, `location_code`, `location_type`, `location_name`, `location_address`, `location_city`, `location_pincode`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(1, 2, 'KAN', 'Plant', 'Kandivali', 'MnM, Akruli Road, Kandivali (East), Mumbai 400091', NULL, NULL, 14, '2018-11-16 08:05:56', 99, '2019-01-16 07:58:58', 1),
(2, 1, 'NAG', 'Area Office', 'Nagpur', 'Agro Pharma Ltd., Automotive Sector, 1st Floor, Poonam Plaza, Nagpur, 440001, Maharashtra', NULL, NULL, 1, '2018-11-16 00:00:00', NULL, NULL, 1),
(3, 1, 'NSK', 'Plant', 'Nashik Plant', 'Agro Pharma Ltd., Nashik Plant, Maharashtra', NULL, NULL, 1, '2018-11-16 12:17:12', NULL, NULL, 1),
(9, 2, NULL, 'Corporate', 'Mumbai - Ghatkopar', 'Agro Pharma Ltd., Industrial Premises Co-operative Society, Patanwala Compound, L.B.S. Road, Opp. Shreyas Cinema, Ghatkopar (W), Mumbai 400086, Maharashtra, India', NULL, NULL, 99, '2018-11-20 18:28:20', NULL, NULL, 1),
(10, 1, NULL, 'Plant', 'Pune', 'Pune Plant, Pune 410010, Maharashtra', NULL, NULL, 99, '2018-11-21 01:49:48', 99, '2018-11-21 02:12:36', 1),
(11, 1, NULL, 'Plant', 'Haridwar', 'Agro Pharma Ltd., Haridwar Plant, Uttarakhand, India', NULL, NULL, 139, '2018-11-26 08:45:52', NULL, NULL, 1),
(12, 1, 'GHAT', 'Area Office', 'Ghatkopar', '204, Mewad, Patanwala Compound, LBS Road, Ghatkopar(W), Mumbai 400086', NULL, NULL, 99, '2019-01-15 06:34:00', NULL, NULL, 1),
(13, 2, 'NSK', 'Area Office', 'Nashik Office', 'Nashik Location, 100 Dhatrak Phata, Opp. Water Tank, Nashik 400053, Maharashtra, India', NULL, NULL, 99, '2019-01-16 07:52:32', 99, '2019-01-16 07:53:01', 1),
(15, 2, 'sdfsdf', 'Area Office', 'sfdsfsd fdfdsf', 'dsfsfsdf', NULL, NULL, 99, '2019-01-16 07:57:29', NULL, NULL, 1),
(16, 2, 'sdff fsd', 'Area Office', 'dsfsf sdfsdf', 'sddfsdfdsf', NULL, NULL, 99, '2019-01-16 07:57:40', NULL, NULL, 1),
(17, 2, 'dfsdfs', 'Area Office', 'dsfds sfsdfds fdsfs', 'dsfsdfdsf ', NULL, NULL, 99, '2019-01-16 07:57:48', NULL, NULL, 1),
(18, 1, 'sdfsdfds', 'Area Office', 'fsdfds fsdfdsf', 'dsfdsfsdfsfdsf', NULL, NULL, 99, '2019-01-16 07:57:56', NULL, NULL, 1),
(19, 1, 'test', 'Area Office', 'Test', 'test address', NULL, NULL, 99, '2019-01-16 07:58:10', NULL, NULL, 1),
(20, 2, 'KOL', 'Area Office', 'Kolkata', 'Kolkata, West Bengal, India', NULL, NULL, 99, '2019-01-16 13:38:03', NULL, NULL, 1),
(21, 2, 'AC', 'Corporate', 'abc', 'fdsfdsfsa', 'PUne', '411047', 99, '2019-01-20 00:35:55', 99, '2019-01-20 00:48:41', 1),
(22, 2, 'CodeJan19', 'Area Office', 'Jan23', 'Near Chanakyapuri road, Ghatlodia, Ahmedabad', 'Ahmedaba', '380061', 99, '2019-01-23 15:39:44', 99, '2019-01-23 15:40:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_log`
--

CREATE TABLE `vs_log` (
  `log_id` int(11) NOT NULL,
  `log_empid` int(11) NOT NULL,
  `log_intime` datetime NOT NULL,
  `log_outtime` datetime NOT NULL,
  `log_session` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_log`
--

INSERT INTO `vs_log` (`log_id`, `log_empid`, `log_intime`, `log_outtime`, `log_session`) VALUES
(265, 99, '2018-11-21 07:18:59', '2018-11-21 07:19:57', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(266, 99, '2018-11-21 07:20:54', '2018-11-21 07:37:10', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(267, 99, '2018-11-21 07:37:26', '2018-11-21 10:07:50', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(268, 99, '2018-11-21 10:08:03', '2018-11-21 10:10:07', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(269, 139, '2018-11-21 10:11:28', '2018-11-21 10:14:19', '8lcgts6uauerb18if4vja5oqa0fgske3'),
(270, 140, '2018-11-21 10:19:42', '2018-11-21 10:22:55', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(271, 139, '2018-11-21 10:23:33', '2018-11-21 10:29:26', '8lcgts6uauerb18if4vja5oqa0fgske3'),
(272, 138, '2018-11-21 10:32:11', '2018-11-21 10:37:10', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(273, 99, '2018-11-21 10:37:21', '2018-11-21 10:38:26', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(274, 140, '2018-11-21 10:38:58', '2018-11-21 10:47:29', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(275, 135, '2018-11-21 10:47:52', '2018-11-21 10:50:59', 'komcrkm8jlq8up6rqdnb6nd7qf7l4260'),
(276, 138, '2018-11-21 10:51:26', '2018-11-21 10:51:45', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(277, 135, '2018-11-21 10:52:22', '2018-11-21 11:18:10', 'komcrkm8jlq8up6rqdnb6nd7qf7l4260'),
(278, 99, '2018-11-21 11:18:21', '2018-11-21 11:42:49', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(279, 99, '2018-11-21 11:45:55', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(280, 99, '2018-11-21 11:45:55', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(281, 99, '2018-11-21 11:45:56', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(282, 99, '2018-11-21 11:45:56', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(283, 99, '2018-11-21 11:45:57', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(284, 99, '2018-11-21 11:45:58', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(285, 99, '2018-11-21 11:45:59', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(286, 99, '2018-11-21 11:50:03', '2018-11-21 11:52:23', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(287, 141, '2018-11-21 11:53:43', '2018-11-21 12:34:43', '5eo4stua3nekn5vn9j27ah9d8ssc282t'),
(288, 99, '2018-11-21 12:35:08', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(289, 99, '2018-11-21 14:58:30', '2018-11-21 16:13:01', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(290, 99, '2018-11-21 16:14:19', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(291, 99, '2018-11-21 20:44:38', '2018-11-21 20:44:41', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(292, 99, '2018-11-21 20:58:39', '2018-11-21 20:58:53', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(293, 99, '2018-11-24 20:54:06', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(294, 139, '2018-11-26 14:14:54', '2018-11-26 15:41:27', '8lcgts6uauerb18if4vja5oqa0fgske3'),
(295, 138, '2018-11-26 20:59:01', '2018-11-26 21:50:25', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(296, 138, '2018-11-26 21:52:23', '2018-11-26 21:54:28', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(297, 138, '2018-11-26 21:54:52', '2018-11-26 21:55:22', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(298, 138, '2018-11-26 21:55:45', '2018-11-26 21:55:59', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(299, 138, '2018-11-26 21:56:07', '2018-11-26 21:56:15', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(300, 140, '2018-11-26 21:57:00', '2018-11-26 21:58:50', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(301, 140, '2018-11-26 21:59:23', '2018-11-26 21:59:30', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(302, 144, '2018-11-26 22:00:53', '2018-11-26 22:03:30', 'sad6938oogmsbc2qubuj4t52g02ali1p'),
(303, 138, '2018-11-26 22:05:20', '2018-11-26 23:45:56', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(304, 138, '2018-11-26 23:46:41', '2018-11-26 23:53:37', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(305, 135, '2018-11-26 23:53:53', '2018-11-27 00:01:19', 'komcrkm8jlq8up6rqdnb6nd7qf7l4260'),
(306, 144, '2018-11-27 00:01:59', '2018-11-27 00:03:26', 'sad6938oogmsbc2qubuj4t52g02ali1p'),
(307, 144, '2018-11-27 00:24:47', '2018-11-27 00:26:09', 'sad6938oogmsbc2qubuj4t52g02ali1p'),
(308, 144, '2018-11-27 00:26:40', '0000-00-00 00:00:00', 'sad6938oogmsbc2qubuj4t52g02ali1p'),
(309, 140, '2018-11-27 08:34:04', '2018-11-27 08:34:22', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(310, 140, '2018-11-27 08:35:57', '2018-11-27 08:36:14', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(311, 140, '2018-11-27 08:37:13', '2018-11-27 08:45:02', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(312, 140, '2018-11-27 08:45:23', '2018-11-27 08:45:30', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(313, 140, '2018-11-27 09:51:39', '2018-11-27 09:52:04', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(314, 144, '2018-11-27 09:52:24', '0000-00-00 00:00:00', 'sad6938oogmsbc2qubuj4t52g02ali1p'),
(315, 138, '2018-11-27 11:21:59', '2018-11-27 11:43:03', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(316, 138, '2018-11-27 11:43:10', '2018-11-27 11:59:47', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(317, 138, '2018-11-27 12:00:00', '2018-11-27 12:17:02', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(318, 138, '2018-11-27 12:17:08', '2018-11-27 12:24:04', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(319, 140, '2018-11-27 12:24:21', '2018-11-27 12:47:17', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(320, 139, '2018-11-27 12:49:09', '0000-00-00 00:00:00', '8lcgts6uauerb18if4vja5oqa0fgske3'),
(321, 138, '2018-11-27 13:06:25', '2018-11-27 13:06:29', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(322, 141, '2018-11-27 13:06:44', '2018-11-27 13:08:26', '1'),
(323, 144, '2018-11-27 13:08:36', '2018-11-27 13:12:36', '1'),
(324, 139, '2018-11-27 13:12:47', '0000-00-00 00:00:00', '8lcgts6uauerb18if4vja5oqa0fgske3'),
(325, 135, '2018-11-28 23:39:46', '0000-00-00 00:00:00', '1'),
(326, 99, '2018-12-12 00:25:31', '2018-12-12 01:47:14', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(327, 99, '2018-12-13 08:26:12', '2018-12-13 08:32:13', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(328, 138, '2018-12-13 08:35:01', '2018-12-13 08:38:51', '1dif32gb7oa5hmn0vvodon82ktupnvbe'),
(329, 99, '2018-12-13 22:17:59', '2018-12-13 23:04:07', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(330, 99, '2018-12-13 23:04:12', '2018-12-13 23:27:11', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(331, 99, '2018-12-13 23:27:15', '2018-12-13 23:47:47', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(332, 99, '2018-12-13 23:47:52', '2018-12-14 00:03:17', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(333, 99, '2018-12-14 00:03:21', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(334, 99, '2018-12-14 21:11:52', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(335, 99, '2018-12-15 11:37:54', '2018-12-15 12:14:07', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(336, 99, '2018-12-15 12:14:12', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(337, 99, '2018-12-15 19:43:50', '2018-12-15 21:39:11', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(338, 99, '2018-12-15 21:39:15', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(339, 99, '2018-12-17 23:10:40', '2018-12-17 23:45:54', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(340, 99, '2018-12-17 23:45:58', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(341, 99, '2018-12-22 10:21:02', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(342, 99, '2018-12-31 10:40:15', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(343, 99, '2019-01-04 00:10:06', '2019-01-04 00:32:09', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(344, 99, '2019-01-04 00:39:08', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(345, 99, '2019-01-05 00:36:47', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(346, 99, '2019-01-07 22:20:15', '2019-01-07 23:14:28', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(347, 99, '2019-01-07 23:14:32', '2019-01-08 00:23:29', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(348, 99, '2019-01-08 00:24:02', '2019-01-08 00:24:39', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(349, 138, '2019-01-08 00:26:06', '2019-01-08 00:31:25', '1'),
(350, 99, '2019-01-08 00:31:31', '2019-01-08 01:20:46', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(351, 99, '2019-01-08 23:11:59', '2019-01-08 23:43:37', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(352, 99, '2019-01-08 23:43:48', '2019-01-09 00:31:51', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(353, 99, '2019-01-09 00:37:38', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(354, 99, '2019-01-09 08:51:47', '2019-01-09 08:52:05', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(355, 99, '2019-01-09 22:13:05', '2019-01-09 22:52:47', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(356, 99, '2019-01-09 22:52:51', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(357, 99, '2019-01-10 23:11:10', '2019-01-10 23:32:21', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(358, 99, '2019-01-10 23:32:25', '2019-01-10 23:34:43', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(359, 99, '2019-01-10 23:34:49', '2019-01-10 23:35:09', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(360, 99, '2019-01-10 23:36:20', '2019-01-10 23:37:56', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(361, 99, '2019-01-10 23:37:59', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(362, 99, '2019-01-15 12:02:59', '2019-01-15 12:35:15', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(363, 139, '2019-01-15 12:40:14', '0000-00-00 00:00:00', '1'),
(364, 99, '2019-01-15 21:05:57', '2019-01-15 21:22:40', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(365, 99, '2019-01-15 21:22:52', '2019-01-15 21:37:11', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(366, 136, '2019-01-15 21:37:21', '2019-01-15 21:39:29', 'npsl1hmdl4pssthsrcjk42vddjtlse1c'),
(367, 137, '2019-01-15 21:40:28', '2019-01-15 21:51:44', '1'),
(368, 99, '2019-01-15 21:52:35', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(369, 99, '2019-01-16 12:57:07', '2019-01-16 13:16:19', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(370, 99, '2019-01-16 13:16:27', '2019-01-16 14:03:59', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(371, 99, '2019-01-16 14:04:11', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(372, 99, '2019-01-16 17:20:17', '2019-01-16 17:56:33', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(373, 99, '2019-01-16 17:56:43', '2019-01-16 18:14:58', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(374, 99, '2019-01-16 18:15:07', '2019-01-16 18:24:20', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(375, 99, '2019-01-16 18:30:49', '2019-01-16 18:32:10', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(376, 140, '2019-01-16 18:32:17', '2019-01-16 18:46:45', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(377, 99, '2019-01-16 18:46:59', '2019-01-16 18:48:42', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(378, 140, '2019-01-16 18:49:05', '2019-01-16 19:07:06', 'hbf6v1ek7ho8tjvqu6mbaa4k80dsnstv'),
(379, 99, '2019-01-16 19:07:18', '2019-01-16 19:09:55', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(380, 140, '2019-01-16 19:10:05', '2019-01-16 19:36:05', '1'),
(381, 99, '2019-01-17 10:31:27', '2019-01-17 11:03:45', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(382, 99, '2019-01-17 11:03:55', '2019-01-17 11:20:02', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(383, 99, '2019-01-17 11:31:14', '2019-01-17 12:34:58', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(384, 99, '2019-01-17 12:35:12', '2019-01-17 12:37:36', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(385, 136, '2019-01-17 12:37:46', '0000-00-00 00:00:00', 'npsl1hmdl4pssthsrcjk42vddjtlse1c'),
(386, 99, '2019-01-17 22:03:16', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(387, 99, '2019-01-17 22:12:34', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(388, 99, '2019-01-19 12:00:21', '2019-01-19 12:02:11', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(389, 99, '2019-01-19 12:02:20', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(390, 99, '2019-01-19 12:06:46', '2019-01-19 13:02:40', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(391, 99, '2019-01-19 13:03:20', '2019-01-19 13:17:33', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(392, 136, '2019-01-19 13:17:41', '0000-00-00 00:00:00', '1'),
(393, 99, '2019-01-19 21:18:21', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(394, 99, '2019-01-20 05:27:35', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(395, 99, '2019-01-20 11:48:48', '2019-01-20 13:39:47', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(396, 99, '2019-01-20 13:39:55', '2019-01-20 14:35:04', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(397, 99, '2019-01-20 14:35:12', '2019-01-20 15:28:56', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(398, 99, '2019-01-21 08:59:42', '2019-01-21 09:58:38', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(399, 99, '2019-01-21 09:58:48', '2019-01-21 10:18:03', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(400, 99, '2019-01-21 10:18:15', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(401, 99, '2019-01-21 11:55:54', '2019-01-21 13:02:17', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(402, 99, '2019-01-21 13:02:27', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(403, 99, '2019-01-21 16:32:58', '2019-01-21 16:55:04', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(404, 99, '2019-01-21 16:55:14', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(405, 99, '2019-01-21 20:49:03', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(406, 99, '2019-01-21 23:19:14', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(407, 99, '2019-01-21 23:24:58', '2019-01-21 23:43:33', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(408, 99, '2019-01-21 23:43:37', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(409, 99, '2019-01-22 22:21:39', '2019-01-22 23:02:56', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(410, 99, '2019-01-22 23:03:00', '2019-01-22 23:30:10', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(411, 99, '2019-01-22 23:30:13', '0000-00-00 00:00:00', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(412, 99, '2019-01-23 21:07:24', '2019-01-23 22:57:17', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(413, 99, '2019-01-23 22:57:23', '2019-01-24 00:11:13', '9e7cdmnoo9pci0jh13vhr1aitp4iqf1d'),
(414, 99, '2019-01-24 00:11:17', '0000-00-00 00:00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `vs_login_attempt`
--

CREATE TABLE `vs_login_attempt` (
  `attmpt_id` int(11) NOT NULL,
  `attmpt_empid` int(10) DEFAULT NULL,
  `attmpt_status` enum('Yes','No') NOT NULL,
  `attmpt_session` varchar(50) DEFAULT NULL,
  `attmpt_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `attmpt_ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_login_attempt`
--

INSERT INTO `vs_login_attempt` (`attmpt_id`, `attmpt_empid`, `attmpt_status`, `attmpt_session`, `attmpt_time`, `attmpt_ip`) VALUES
(441, 99, 'Yes', NULL, '2018-11-21 01:48:59', '::1'),
(442, 99, 'Yes', NULL, '2018-11-21 01:50:54', '::1'),
(443, 99, 'Yes', NULL, '2018-11-21 02:07:25', '::1'),
(444, 99, 'Yes', NULL, '2018-11-21 04:38:03', '::1'),
(445, 139, 'Yes', NULL, '2018-11-21 04:40:23', '::1'),
(446, 139, 'Yes', NULL, '2018-11-21 04:41:28', '::1'),
(447, 140, 'Yes', NULL, '2018-11-21 04:44:26', '::1'),
(448, 140, 'Yes', NULL, '2018-11-21 04:49:42', '::1'),
(449, 139, 'No', NULL, '2018-11-21 04:53:22', '::1'),
(450, 139, 'Yes', NULL, '2018-11-21 04:53:33', '::1'),
(451, 135, 'Yes', NULL, '2018-11-21 04:59:40', '::1'),
(452, 138, 'Yes', NULL, '2018-11-21 05:02:11', '::1'),
(453, 99, 'Yes', NULL, '2018-11-21 05:07:21', '::1'),
(454, 140, 'Yes', NULL, '2018-11-21 05:08:58', '::1'),
(455, 135, 'Yes', NULL, '2018-11-21 05:17:52', '::1'),
(456, 138, 'Yes', NULL, '2018-11-21 05:21:26', '::1'),
(457, 135, 'Yes', NULL, '2018-11-21 05:22:22', '::1'),
(458, 99, 'Yes', NULL, '2018-11-21 05:48:21', '::1'),
(459, 0, 'No', NULL, '2018-11-21 06:13:16', '::1'),
(460, 0, 'No', NULL, '2018-11-21 06:13:47', '::1'),
(461, 0, 'No', NULL, '2018-11-21 06:14:08', '::1'),
(462, 0, 'No', NULL, '2018-11-21 06:14:29', '::1'),
(463, 0, 'No', NULL, '2018-11-21 06:14:51', '::1'),
(464, 0, 'No', NULL, '2018-11-21 06:15:12', '::1'),
(465, 99, 'Yes', NULL, '2018-11-21 06:15:55', '::1'),
(466, 99, 'Yes', NULL, '2018-11-21 06:15:55', '::1'),
(467, 99, 'Yes', NULL, '2018-11-21 06:15:56', '::1'),
(468, 99, 'Yes', NULL, '2018-11-21 06:15:56', '::1'),
(469, 99, 'Yes', NULL, '2018-11-21 06:15:57', '::1'),
(470, 99, 'Yes', NULL, '2018-11-21 06:15:57', '::1'),
(471, 99, 'Yes', NULL, '2018-11-21 06:15:58', '::1'),
(472, 99, 'Yes', NULL, '2018-11-21 06:20:03', '::1'),
(473, 141, 'Yes', NULL, '2018-11-21 06:22:30', '::1'),
(474, 141, 'Yes', NULL, '2018-11-21 06:23:43', '::1'),
(475, 99, 'Yes', NULL, '2018-11-21 07:05:08', '::1'),
(476, 99, 'Yes', NULL, '2018-11-21 09:28:29', '::1'),
(477, 99, 'Yes', NULL, '2018-11-21 10:44:19', '::1'),
(478, 99, 'Yes', NULL, '2018-11-21 15:14:37', '::1'),
(479, 99, 'No', NULL, '2018-11-21 15:27:21', '::1'),
(480, 99, 'Yes', NULL, '2018-11-21 15:28:39', '::1'),
(481, 99, 'Yes', NULL, '2018-11-24 15:24:05', '::1'),
(482, 139, 'Yes', NULL, '2018-11-26 08:44:53', '::1'),
(483, 138, 'Yes', NULL, '2018-11-26 15:29:01', '::1'),
(484, 138, 'Yes', NULL, '2018-11-26 16:22:23', '::1'),
(485, 138, 'No', NULL, '2018-11-26 16:24:46', '::1'),
(486, 138, 'Yes', NULL, '2018-11-26 16:24:52', '::1'),
(487, 138, 'Yes', NULL, '2018-11-26 16:25:44', '::1'),
(488, 138, 'Yes', NULL, '2018-11-26 16:26:07', '::1'),
(489, 140, 'Yes', NULL, '2018-11-26 16:27:00', '::1'),
(490, 140, 'Yes', NULL, '2018-11-26 16:29:23', '::1'),
(491, 144, 'Yes', NULL, '2018-11-26 16:30:53', '::1'),
(492, 138, 'Yes', NULL, '2018-11-26 16:35:20', '::1'),
(493, 138, 'Yes', NULL, '2018-11-26 18:16:41', '::1'),
(494, 135, 'Yes', NULL, '2018-11-26 18:23:53', '::1'),
(495, 144, 'Yes', NULL, '2018-11-26 18:31:59', '::1'),
(496, 144, 'Yes', NULL, '2018-11-26 18:54:46', '::1'),
(497, 144, 'Yes', NULL, '2018-11-26 18:56:40', '::1'),
(498, 140, 'Yes', NULL, '2018-11-27 03:04:03', '::1'),
(499, 140, 'Yes', NULL, '2018-11-27 03:05:57', '::1'),
(500, 140, 'Yes', NULL, '2018-11-27 03:07:13', '::1'),
(501, 140, 'Yes', NULL, '2018-11-27 03:15:22', '::1'),
(502, 140, 'Yes', NULL, '2018-11-27 04:21:39', '::1'),
(503, 144, 'Yes', NULL, '2018-11-27 04:22:24', '::1'),
(504, 138, 'Yes', NULL, '2018-11-27 05:51:59', '::1'),
(505, 138, 'Yes', NULL, '2018-11-27 06:13:10', '::1'),
(506, 138, 'Yes', NULL, '2018-11-27 06:30:00', '::1'),
(507, 138, 'Yes', NULL, '2018-11-27 06:47:08', '::1'),
(508, 140, 'Yes', NULL, '2018-11-27 06:54:21', '::1'),
(509, 139, 'Yes', NULL, '2018-11-27 07:19:08', '::1'),
(510, 138, 'Yes', NULL, '2018-11-27 07:36:24', '::1'),
(511, 141, 'Yes', NULL, '2018-11-27 07:36:44', '::1'),
(512, 144, 'Yes', NULL, '2018-11-27 07:38:36', '::1'),
(513, 139, 'Yes', NULL, '2018-11-27 07:42:47', '::1'),
(514, 135, 'Yes', NULL, '2018-11-28 18:09:46', '::1'),
(515, 99, 'Yes', NULL, '2018-12-11 18:55:31', '::1'),
(516, 99, 'Yes', NULL, '2018-12-13 02:56:11', '::1'),
(517, 138, 'No', NULL, '2018-12-13 03:04:04', '::1'),
(518, 138, 'No', NULL, '2018-12-13 03:04:21', '::1'),
(519, 138, 'Yes', NULL, '2018-12-13 03:05:01', '::1'),
(520, 99, 'Yes', NULL, '2018-12-13 16:47:59', '::1'),
(521, 99, 'Yes', NULL, '2018-12-13 17:34:12', '::1'),
(522, 99, 'Yes', NULL, '2018-12-13 17:57:15', '::1'),
(523, 99, 'Yes', NULL, '2018-12-13 18:17:52', '::1'),
(524, 99, 'Yes', NULL, '2018-12-13 18:33:21', '::1'),
(525, 99, 'Yes', NULL, '2018-12-14 15:41:52', '::1'),
(526, 99, 'Yes', NULL, '2018-12-15 06:07:53', '::1'),
(527, 99, 'Yes', NULL, '2018-12-15 06:44:11', '::1'),
(528, 99, 'Yes', NULL, '2018-12-15 14:13:49', '::1'),
(529, 99, 'Yes', NULL, '2018-12-15 16:09:15', '::1'),
(530, 99, 'Yes', NULL, '2018-12-17 17:40:40', '::1'),
(531, 99, 'Yes', NULL, '2018-12-17 18:15:57', '::1'),
(532, 99, 'Yes', NULL, '2018-12-22 04:51:02', '::1'),
(533, 99, 'Yes', NULL, '2018-12-31 05:10:15', '::1'),
(534, 99, 'Yes', NULL, '2019-01-03 18:40:06', '::1'),
(535, 99, 'Yes', NULL, '2019-01-03 19:09:08', '::1'),
(536, 99, 'Yes', NULL, '2019-01-04 19:06:47', '::1'),
(537, 99, 'Yes', NULL, '2019-01-07 16:50:14', '::1'),
(538, 99, 'Yes', NULL, '2019-01-07 17:44:32', '::1'),
(539, 135, 'No', NULL, '2019-01-07 18:53:39', '::1'),
(540, 135, 'No', NULL, '2019-01-07 18:53:53', '::1'),
(541, 99, 'Yes', NULL, '2019-01-07 18:54:02', '::1'),
(542, 138, 'Yes', NULL, '2019-01-07 18:54:49', '::1'),
(543, 138, 'Yes', NULL, '2019-01-07 18:54:53', '::1'),
(544, 138, 'Yes', NULL, '2019-01-07 18:56:05', '::1'),
(545, 99, 'Yes', NULL, '2019-01-07 19:01:31', '::1'),
(546, 99, 'Yes', NULL, '2019-01-08 17:41:59', '::1'),
(547, 99, 'Yes', NULL, '2019-01-08 18:13:47', '::1'),
(548, 99, 'Yes', NULL, '2019-01-08 19:07:37', '::1'),
(549, 99, 'Yes', NULL, '2019-01-09 03:21:47', '::1'),
(550, 99, 'Yes', NULL, '2019-01-09 16:43:04', '::1'),
(551, 99, 'Yes', NULL, '2019-01-09 17:22:51', '::1'),
(552, 99, 'Yes', NULL, '2019-01-10 17:41:10', '::1'),
(553, 99, 'Yes', NULL, '2019-01-10 18:02:25', '::1'),
(554, 99, 'Yes', NULL, '2019-01-10 18:04:49', '::1'),
(555, 99, 'Yes', NULL, '2019-01-10 18:06:19', '::1'),
(556, 99, 'Yes', NULL, '2019-01-10 18:07:59', '::1'),
(557, 99, 'Yes', NULL, '2019-01-15 06:32:59', '::1'),
(558, 139, 'Yes', NULL, '2019-01-15 07:05:25', '::1'),
(559, 139, 'Yes', NULL, '2019-01-15 07:10:14', '::1'),
(560, 99, 'Yes', NULL, '2019-01-15 15:35:56', '::1'),
(561, 99, 'Yes', NULL, '2019-01-15 15:52:52', '::1'),
(562, 136, 'Yes', NULL, '2019-01-15 16:07:21', '::1'),
(563, 137, 'Yes', NULL, '2019-01-15 16:09:35', '::1'),
(564, 137, 'Yes', NULL, '2019-01-15 16:09:40', '::1'),
(565, 137, 'Yes', NULL, '2019-01-15 16:09:44', '::1'),
(566, 137, 'Yes', NULL, '2019-01-15 16:10:28', '::1'),
(567, 99, 'Yes', NULL, '2019-01-15 16:22:34', '::1'),
(568, 99, 'Yes', NULL, '2019-01-16 07:27:05', '::1'),
(569, 99, 'Yes', NULL, '2019-01-16 07:46:27', '::1'),
(570, 99, 'Yes', NULL, '2019-01-16 08:34:11', '::1'),
(571, 99, 'Yes', NULL, '2019-01-16 11:50:17', '::1'),
(572, 99, 'Yes', NULL, '2019-01-16 12:26:42', '::1'),
(573, 99, 'Yes', NULL, '2019-01-16 12:45:07', '::1'),
(574, 99, 'Yes', NULL, '2019-01-16 13:00:49', '::1'),
(575, 140, 'Yes', NULL, '2019-01-16 13:02:17', '::1'),
(576, 99, 'Yes', NULL, '2019-01-16 13:16:59', '::1'),
(577, 140, 'Yes', NULL, '2019-01-16 13:19:04', '::1'),
(578, 99, 'Yes', NULL, '2019-01-16 13:37:18', '::1'),
(579, 140, 'Yes', NULL, '2019-01-16 13:40:05', '::1'),
(580, 99, 'Yes', NULL, '2019-01-17 05:01:27', '::1'),
(581, 99, 'Yes', NULL, '2019-01-17 05:33:55', '::1'),
(582, 99, 'Yes', NULL, '2019-01-17 06:01:14', '::1'),
(583, 99, 'Yes', NULL, '2019-01-17 07:05:12', '::1'),
(584, 136, 'Yes', NULL, '2019-01-17 07:07:45', '::1'),
(585, 99, 'Yes', NULL, '2019-01-17 16:33:16', '::1'),
(586, 99, 'No', NULL, '2019-01-17 16:42:06', '::1'),
(587, 99, 'No', NULL, '2019-01-17 16:42:16', '::1'),
(588, 99, 'Yes', NULL, '2019-01-17 16:42:34', '::1'),
(589, 99, 'Yes', NULL, '2019-01-19 06:30:21', '::1'),
(590, 99, 'Yes', NULL, '2019-01-19 06:32:20', '::1'),
(591, 99, 'Yes', NULL, '2019-01-19 06:36:46', '::1'),
(592, 99, 'Yes', NULL, '2019-01-19 07:33:19', '::1'),
(593, 136, 'Yes', NULL, '2019-01-19 07:47:41', '::1'),
(594, 99, 'Yes', NULL, '2019-01-19 15:48:21', '::1'),
(595, 99, 'Yes', NULL, '2019-01-19 23:57:35', '::1'),
(596, 99, 'Yes', NULL, '2019-01-20 06:18:48', '::1'),
(597, 99, 'Yes', NULL, '2019-01-20 08:09:55', '::1'),
(598, 99, 'Yes', NULL, '2019-01-20 09:05:12', '::1'),
(599, 99, 'Yes', NULL, '2019-01-21 03:29:42', '::1'),
(600, 99, 'Yes', NULL, '2019-01-21 04:28:48', '::1'),
(601, 99, 'Yes', NULL, '2019-01-21 04:48:15', '::1'),
(602, 99, 'Yes', NULL, '2019-01-21 06:25:54', '::1'),
(603, 99, 'Yes', NULL, '2019-01-21 07:32:27', '::1'),
(604, 99, 'Yes', NULL, '2019-01-21 11:02:58', '::1'),
(605, 99, 'Yes', NULL, '2019-01-21 11:25:14', '::1'),
(606, 99, 'Yes', NULL, '2019-01-21 15:19:03', '::1'),
(607, 99, 'Yes', NULL, '2019-01-21 17:49:14', '::1'),
(608, 99, 'Yes', NULL, '2019-01-21 17:54:58', '::1'),
(609, 99, 'Yes', NULL, '2019-01-21 18:13:37', '::1'),
(610, 99, 'Yes', NULL, '2019-01-22 16:51:39', '::1'),
(611, 99, 'Yes', NULL, '2019-01-22 17:33:00', '::1'),
(612, 99, 'Yes', NULL, '2019-01-22 18:00:13', '::1'),
(613, 99, 'Yes', NULL, '2019-01-23 15:37:24', '::1'),
(614, 99, 'Yes', NULL, '2019-01-23 17:27:22', '::1'),
(615, 99, 'Yes', NULL, '2019-01-23 18:41:17', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `vs_pickuppoint`
--

CREATE TABLE `vs_pickuppoint` (
  `pickup_id` int(10) NOT NULL,
  `p_locationid` varchar(500) DEFAULT NULL,
  `p_pickuppoint` varchar(500) DEFAULT NULL,
  `p_desc` longtext,
  `createdby` int(10) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(10) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_pickuppoint`
--

INSERT INTO `vs_pickuppoint` (`pickup_id`, `p_locationid`, `p_pickuppoint`, `p_desc`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(1, '11', 'Watch man cabin', 'This is test', 99, '2019-01-07 18:33:12', 99, '2019-01-07 18:33:30', 1),
(2, '12', '204 Office', '2nd Floor', 99, '2019-01-16 08:56:23', NULL, NULL, 1),
(3, '12', '208 Office', '2nd Floor', 99, '2019-01-16 08:56:42', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_request`
--

CREATE TABLE `vs_request` (
  `req_id` int(11) NOT NULL,
  `req_emp_id` int(10) NOT NULL,
  `req_emp_token` varchar(15) NOT NULL,
  `req_emp_name` varchar(100) NOT NULL,
  `req_emp_org_desig` varchar(100) NOT NULL,
  `req_emp_new_desig` varchar(100) DEFAULT NULL,
  `req_emp_org_dept` varchar(100) DEFAULT NULL,
  `req_emp_new_dept` varchar(100) DEFAULT NULL,
  `req_emp_org_buss_unit` varchar(100) DEFAULT NULL,
  `req_emp_new_buss_unit` varchar(100) DEFAULT NULL,
  `req_emp_location_id` int(11) DEFAULT NULL,
  `req_emp_location_name` varchar(100) DEFAULT NULL,
  `req_emp_new_location_name` varchar(100) NOT NULL,
  `req_emp_address` varchar(500) DEFAULT NULL,
  `req_emp_new_address` varchar(500) NOT NULL,
  `req_emp_stdcode` varchar(10) DEFAULT NULL,
  `req_emp_landline` varchar(30) DEFAULT NULL,
  `req_emp_new_landline` varchar(30) NOT NULL,
  `req_emp_fax` varchar(30) DEFAULT NULL,
  `req_emp_mobile` varchar(40) DEFAULT NULL,
  `req_emp_new_mobile` varchar(40) NOT NULL,
  `req_emp_email` varchar(75) NOT NULL,
  `req_emp_costcenter` varchar(100) NOT NULL,
  `req_emp_new_costcenter` varchar(100) NOT NULL,
  `req_emp_wbs` varchar(100) DEFAULT NULL,
  `req_vendor_id` int(10) NOT NULL,
  `req_emp_mgr_token` varchar(15) NOT NULL,
  `req_emp_mgr_name` varchar(100) NOT NULL,
  `req_emp_mgr_email` varchar(75) NOT NULL,
  `req_emp_hrmgr_token` varchar(15) NOT NULL,
  `req_emp_hrmgr_name` varchar(100) NOT NULL,
  `req_emp_hrmgr_email` varchar(75) NOT NULL,
  `req_emp_sr_hrmgr_token` varchar(15) NOT NULL,
  `req_emp_sr_hrmgr_name` varchar(100) NOT NULL,
  `req_emp_sr_hrmgr_email` varchar(75) NOT NULL,
  `req_status` enum('Dispatched','In Proof Reading','Request Send to Vendor','Declined by HR','Pending Approval from HR','Draft') NOT NULL DEFAULT 'Draft',
  `req_createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `req_createdby` int(10) NOT NULL,
  `req_modifiedby` int(10) DEFAULT NULL,
  `req_modifieddate` datetime DEFAULT NULL,
  `req_submittedby` int(10) DEFAULT NULL,
  `req_submitteddate` datetime DEFAULT NULL,
  `req_hr_actionby` varchar(50) DEFAULT NULL,
  `req_hr_actiondate` datetime DEFAULT NULL,
  `req_hr_rejectremark` varchar(300) DEFAULT NULL,
  `req_vendor_proofread_actiondate` datetime DEFAULT NULL,
  `req_vendor_dispatched_actiondate` datetime DEFAULT NULL,
  `req_vendor_deliveryby` enum('By Hand','By Courier') DEFAULT NULL,
  `req_vendor_dispatchdate` date DEFAULT NULL,
  `req_vendor_expdeliverydate` date DEFAULT NULL,
  `req_vendor_dispatch_courier_cmpname` varchar(75) DEFAULT NULL,
  `req_vendor_dispatch_courierawbno` varchar(20) DEFAULT NULL,
  `req_vendor_dispatch_pod` varchar(75) DEFAULT NULL,
  `req_vendor_remark` varchar(150) DEFAULT NULL,
  `req_completeddate` datetime DEFAULT NULL,
  `req_completedby` int(10) DEFAULT NULL,
  `req_first_hr_mail_sent` datetime DEFAULT NULL,
  `req_first_vendor_mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_role`
--

CREATE TABLE `vs_role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(15) NOT NULL,
  `role_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_role`
--

INSERT INTO `vs_role` (`role_id`, `role_name`, `role_status`) VALUES
(1, 'Admin', 1),
(2, 'Employee', 1),
(3, 'DeskUser', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vs_vendor`
--

CREATE TABLE `vs_vendor` (
  `vendor_id` int(10) NOT NULL,
  `vendor_code` varchar(15) DEFAULT NULL,
  `vendor_sapcode` varchar(15) DEFAULT NULL,
  `vendor_cmpname` varchar(150) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `vendor_mobile` varchar(15) DEFAULT NULL,
  `vendor_email` varchar(100) NOT NULL,
  `vendor_address` varchar(400) DEFAULT NULL,
  `vendor_gst` varchar(15) DEFAULT NULL,
  `vendor_pan` varchar(10) DEFAULT NULL,
  `location_id` varchar(200) DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` int(11) DEFAULT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_vendor`
--

INSERT INTO `vs_vendor` (`vendor_id`, `vendor_code`, `vendor_sapcode`, `vendor_cmpname`, `vendor_name`, `vendor_mobile`, `vendor_email`, `vendor_address`, `vendor_gst`, `vendor_pan`, `location_id`, `createdby`, `createddate`, `modifiedby`, `modifieddate`, `isactive`) VALUES
(7, NULL, NULL, 'ABC Printer', 'ABC Printer', '9999900000', 'sales@intellectsoftsol.com', 'Office Number 204, 2nd Floor, Mewad Industrial Premises Co-operative Society Ltd., E. S. Patanwala Compound, Opposite Shreyas Cinema, L.B.S. Road, Ghatkopar (West), Mumbai 400 086, Maharashtra, India', '27ABCDE2121A3X1', 'ABCDE2121A', 'Mumbai', 99, '2018-11-21 02:08:17', NULL, NULL, 1),
(8, NULL, NULL, 'XYZ Printer', 'XYZ Printer', '8888888888', 'apoorvaa@gmail.com', 'Office Number 204, 2nd Floor, Mewad Industrial Premises Co-operative Society Ltd., E. S. Patanwala Compound, Opposite Shreyas Cinema, L.B.S. Road, Ghatkopar (West), Mumbai 400 086, Maharashtra, India', '27ABCDE2121A3X1', 'ABCDE2121A', 'Mumbai', 99, '2018-11-21 02:09:36', NULL, NULL, 1),
(9, NULL, NULL, 'Joy Printing', 'Jinesh Mehta', '9999988888', 'apoorvaa@yahoo.com', '100 M. G. Road, NCR Region, Gurugram', '18DFDDD3434D2A3', 'DFDDD3434D', 'Delhi', 99, '2018-11-21 02:11:00', NULL, NULL, 1),
(10, NULL, NULL, 'A to Z Printing', 'Rajesh Sharma', '9989999912', 'apoorvaa@gmail.com', '100 Mahatma Gandhi Road, Haridwar, Uttarakhand, India', 'GST123232323231', 'ABBD343434', 'Haridwar', 139, '2018-11-26 08:47:55', 139, 2018, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `errorlog`
--
ALTER TABLE `errorlog`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `financial_year`
--
ALTER TABLE `financial_year`
  ADD PRIMARY KEY (`finacialid`);

--
-- Indexes for table `test_empmst`
--
ALTER TABLE `test_empmst`
  ADD PRIMARY KEY (`test_empid`);

--
-- Indexes for table `vs_business`
--
ALTER TABLE `vs_business`
  ADD PRIMARY KEY (`business_id`);

--
-- Indexes for table `vs_businessunit`
--
ALTER TABLE `vs_businessunit`
  ADD PRIMARY KEY (`bu_id`);

--
-- Indexes for table `vs_charge`
--
ALTER TABLE `vs_charge`
  ADD PRIMARY KEY (`charge_id`),
  ADD KEY `agency_code` (`agency_code`);

--
-- Indexes for table `vs_ci_sessions`
--
ALTER TABLE `vs_ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `vs_courier`
--
ALTER TABLE `vs_courier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vs_courier_agency`
--
ALTER TABLE `vs_courier_agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- Indexes for table `vs_courier_request`
--
ALTER TABLE `vs_courier_request`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `emp_id` (`req_emp_id`);

--
-- Indexes for table `vs_emp_details`
--
ALTER TABLE `vs_emp_details`
  ADD PRIMARY KEY (`emp_id`),
  ADD KEY `vendorid` (`vendorid`);

--
-- Indexes for table `vs_escalation_mst`
--
ALTER TABLE `vs_escalation_mst`
  ADD PRIMARY KEY (`vs_escmstid`);

--
-- Indexes for table `vs_esc_hr_log`
--
ALTER TABLE `vs_esc_hr_log`
  ADD PRIMARY KEY (`esc_hr_logid`);

--
-- Indexes for table `vs_esc_vendor_log`
--
ALTER TABLE `vs_esc_vendor_log`
  ADD PRIMARY KEY (`esc_vendor_logid`);

--
-- Indexes for table `vs_franking`
--
ALTER TABLE `vs_franking`
  ADD PRIMARY KEY (`franking_id`);

--
-- Indexes for table `vs_ldap_settings`
--
ALTER TABLE `vs_ldap_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vs_location`
--
ALTER TABLE `vs_location`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `location_typeid` (`location_type`);

--
-- Indexes for table `vs_log`
--
ALTER TABLE `vs_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `vs_login_attempt`
--
ALTER TABLE `vs_login_attempt`
  ADD PRIMARY KEY (`attmpt_id`);

--
-- Indexes for table `vs_pickuppoint`
--
ALTER TABLE `vs_pickuppoint`
  ADD PRIMARY KEY (`pickup_id`);

--
-- Indexes for table `vs_request`
--
ALTER TABLE `vs_request`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `emp_id` (`req_emp_id`),
  ADD KEY `location_id` (`req_emp_location_id`),
  ADD KEY `vendor_id` (`req_vendor_id`);

--
-- Indexes for table `vs_role`
--
ALTER TABLE `vs_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `vs_vendor`
--
ALTER TABLE `vs_vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `location_id` (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `errorlog`
--
ALTER TABLE `errorlog`
  MODIFY `logID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_year`
--
ALTER TABLE `financial_year`
  MODIFY `finacialid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `test_empmst`
--
ALTER TABLE `test_empmst`
  MODIFY `test_empid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `vs_business`
--
ALTER TABLE `vs_business`
  MODIFY `business_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vs_businessunit`
--
ALTER TABLE `vs_businessunit`
  MODIFY `bu_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vs_charge`
--
ALTER TABLE `vs_charge`
  MODIFY `charge_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vs_courier`
--
ALTER TABLE `vs_courier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vs_courier_agency`
--
ALTER TABLE `vs_courier_agency`
  MODIFY `agency_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vs_courier_request`
--
ALTER TABLE `vs_courier_request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vs_emp_details`
--
ALTER TABLE `vs_emp_details`
  MODIFY `emp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `vs_escalation_mst`
--
ALTER TABLE `vs_escalation_mst`
  MODIFY `vs_escmstid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_esc_hr_log`
--
ALTER TABLE `vs_esc_hr_log`
  MODIFY `esc_hr_logid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_esc_vendor_log`
--
ALTER TABLE `vs_esc_vendor_log`
  MODIFY `esc_vendor_logid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vs_franking`
--
ALTER TABLE `vs_franking`
  MODIFY `franking_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vs_ldap_settings`
--
ALTER TABLE `vs_ldap_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vs_location`
--
ALTER TABLE `vs_location`
  MODIFY `location_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vs_log`
--
ALTER TABLE `vs_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=415;

--
-- AUTO_INCREMENT for table `vs_login_attempt`
--
ALTER TABLE `vs_login_attempt`
  MODIFY `attmpt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=616;

--
-- AUTO_INCREMENT for table `vs_pickuppoint`
--
ALTER TABLE `vs_pickuppoint`
  MODIFY `pickup_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vs_request`
--
ALTER TABLE `vs_request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_vendor`
--
ALTER TABLE `vs_vendor`
  MODIFY `vendor_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vs_emp_details`
--
ALTER TABLE `vs_emp_details`
  ADD CONSTRAINT `emp_vendorid` FOREIGN KEY (`vendorid`) REFERENCES `vs_vendor` (`vendor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
