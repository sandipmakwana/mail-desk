ALTER TABLE `vs_courier_request`
ADD `req_receiever_location` varchar(250) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `req_receiever_telephone`;