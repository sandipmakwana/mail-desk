ALTER TABLE `vs_emp_details` ADD `emp_dept` VARCHAR(250) NULL AFTER `emp_verify`, ADD `emp_mobile` VARCHAR(50) NULL AFTER `emp_dept`;
ALTER TABLE `vs_courier_request` CHANGE `req_emp_dept` `req_emp_dept` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `vs_courier_request` CHANGE `req_emp_extension` `req_emp_extension` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `vs_courier_request` CHANGE `req_emp_type` `req_emp_type` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
