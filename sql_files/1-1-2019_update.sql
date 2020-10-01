UPDATE `vs_role` SET
`role_id` = '3',
`role_name` = 'DeskUser',
`role_status` = '1'
WHERE `role_id` = '3';


UPDATE `vs_emp_details` SET
`emp_role` = 'DeskUser'
WHERE `emp_role` = 'Vendor';
