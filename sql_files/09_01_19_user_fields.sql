ALTER TABLE `vs_emp_details`
ADD `emp_locationid` varchar(50) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `emp_lastname`;
ALTER TABLE `vs_emp_details`
ADD `emp_departmentid` varchar(250) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `emp_locationid`; 