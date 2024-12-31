DROP TABLE IF EXISTS `sp_ipn_request`;
CREATE TABLE `sp_ipn_request` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `request_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` bigint NOT NULL,
  `pay_mode_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'payment mode code(ex: bkash=AO1).save from payment callback',
  `trans_time` datetime NOT NULL,
  `ref_tran_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_tran_date_time` datetime DEFAULT NULL,
  `trans_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trans_amount` decimal(12,2) NOT NULL,
  `pay_amount` decimal(12,2) NOT NULL,
  `json_object` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipn_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_authorized_request` tinyint NOT NULL DEFAULT '0' COMMENT '0=unauthorized request, 1=authorized request',
  `is_archive` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_tran_no` (`ref_tran_no`),
  KEY `ref_tran_date_time` (`ref_tran_date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DELIMITER ;;

CREATE TRIGGER `sp_ipn_request_ai` AFTER INSERT ON `sp_ipn_request` FOR EACH ROW
BEGIN
INSERT INTO sp_ipn_request_history(
action_type, action_datetime, sp_ipn_request_id, request_ip, transaction_id, pay_mode_code,
trans_time, ref_tran_no, ref_tran_date_time, trans_status, trans_amount, pay_amount,
json_object, ipn_response, is_authorized_request, is_archive, created_by, created_at,
updated_at, updated_by)
VALUES('ONINSERT', NOW(), NEW.id, NEW.request_ip, NEW.transaction_id, NEW.pay_mode_code,
NEW.trans_time, NEW.ref_tran_no, NEW.ref_tran_date_time, NEW.trans_status,  NEW.trans_amount,
NEW.pay_amount, NEW.json_object, NEW.ipn_response, NEW.is_authorized_request, NEW.is_archive,
NEW.created_by, NEW.created_at, NEW.updated_at, NEW.updated_by);
END;;

CREATE TRIGGER `sp_ipn_request_au` AFTER UPDATE ON `sp_ipn_request` FOR EACH ROW
BEGIN
INSERT INTO sp_ipn_request_history(
action_type, action_datetime, sp_ipn_request_id, request_ip, transaction_id, pay_mode_code,
trans_time, ref_tran_no, ref_tran_date_time, trans_status, trans_amount, pay_amount,
json_object, ipn_response, is_authorized_request, is_archive, created_by, created_at,
updated_at, updated_by)
VALUES('ONUPDATE', NOW(), NEW.id, NEW.request_ip, NEW.transaction_id, NEW.pay_mode_code,
NEW.trans_time, NEW.ref_tran_no, NEW.ref_tran_date_time, NEW.trans_status,  NEW.trans_amount,
NEW.pay_amount, NEW.json_object, NEW.ipn_response, NEW.is_authorized_request, NEW.is_archive,
NEW.created_by, NEW.created_at, NEW.updated_at, NEW.updated_by);
END;;

DELIMITER ;

DROP TABLE IF EXISTS `sp_ipn_request_history`;
CREATE TABLE `sp_ipn_request_history` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `action_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_datetime` datetime DEFAULT NULL,
  `sp_ipn_request_id` bigint NOT NULL,
  `request_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` bigint NOT NULL,
  `pay_mode_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'payment mode code(ex: bkash=AO1).save from payment callback',
  `trans_time` datetime NOT NULL,
  `ref_tran_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_tran_date_time` datetime DEFAULT NULL,
  `trans_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trans_amount` decimal(12,2) NOT NULL,
  `pay_amount` decimal(12,2) NOT NULL,
  `json_object` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipn_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_authorized_request` tinyint NOT NULL DEFAULT '0' COMMENT '0=unauthorized request, 1=authorized request',
  `is_archive` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sp_pay_mode`;
CREATE TABLE `sp_pay_mode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pay_mode` varchar(90) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pay_mode_code` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_pay_code` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pay_mode_code` (`pay_mode_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `sp_pay_mode` (`id`, `pay_mode`, `pay_mode_code`, `sp_pay_code`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1,	'SB Counter Payment',	'A01',	'sbl_cash',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(2,	'SB Accounts Transfer',	'A02',	'sbl_account',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:12:30',	0),
(3,	'Sonali Pre-Paid Card',	'C01',	'sblcrcard',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(4,	'VISA Card',	'C02',	'other_visa',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(5,	'Master Card',	'C03',	'other_master',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(6,	'QCash',	'C04',	'other_qcash',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(7,	'DBBL Nexus',	'C07',	'dbbl_nexus',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(8,	'CITY VISA',	'C08',	'city_visa_gov',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:25:39',	0),
(9,	'CIRY MASTER',	'C09',	'city_master_gov',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:25:56',	0),
(10,	'CITY AMEX',	'C10',	'city_amex_gov',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(11,	'DBBL Rocket',	'M03',	'mfs_rocket',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(12,	'bKash',	'M04',	'mfs_bkash',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0),
(13,	'Ucash',	'M07',	'mfs_ucash',	1,	'2021-09-07 18:04:52',	0,	'2021-09-06 18:04:56',	0);


DROP TABLE IF EXISTS `sp_payment`;
CREATE TABLE `sp_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_config_id` int NOT NULL DEFAULT '0' COMMENT 'id from payment_configuration table',
  `app_id` int NOT NULL DEFAULT '0' COMMENT 'application id',
  `process_type_id` int NOT NULL DEFAULT '0' COMMENT 'process type id',
  `app_tracking_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Application Tracking Number',
  `payment_step_id` tinyint NOT NULL,
  `payment_category_id` int DEFAULT NULL COMMENT 'id from payment_category table',
  `pay_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'payment mode (ex: counter, bkash).save from payment callback',
  `pay_mode_code` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'payment mode code(ex: bkash=AO1).save from payment callback',
  `transaction_id` bigint DEFAULT NULL COMMENT 'Response transaction id.save from payment callback',
  `request_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Daily Unique request id',
  `payment_date` date DEFAULT NULL COMMENT 'Transaction payment date',
  `ref_tran_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reference Transaction Id',
  `ref_tran_date_time` datetime DEFAULT NULL COMMENT 'Response Transaction Date Time',
  `spg_trans_date_time` datetime DEFAULT NULL COMMENT 'Actual Transaction time of  Sonali Bank',
  `pay_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Exact amount for BAT or BSCIC',
  `transaction_charge_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Commission Charge for pay_amount.save from verification response',
  `vat_on_transaction_charge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'vat on only transection charge',
  `vat_on_pay_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'vat on only pay amount',
  `total_amount` decimal(12,2) DEFAULT '0.00' COMMENT 'sum of pay amount,transection charge ,vat on transection charge ,vat on pay amount',
  `receiver_ac_no` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Receiver bank account no',
  `sender_ac_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sender bank account no',
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Name',
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Email',
  `contact_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Number',
  `payer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id of this table',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Address',
  `sl_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'it will be always 1',
  `status_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=Pending, -1=In Progress, 1=Successful, -2=Exception, 3=Waiting for Payment Confirmation, -3=Payment Cancel',
  `is_verified` tinyint NOT NULL DEFAULT '0' COMMENT '0=Non-verified, 1 = Verified. save from verification response',
  `payment_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment request xml',
  `payment_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment request',
  `payment_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment response xml',
  `payment_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verification_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification request xml',
  `verification_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification request',
  `verification_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification response xml',
  `verification_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'verification response',
  `offline_verify_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline payment Verification request xml',
  `offline_verify_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline Verification request',
  `offline_verify_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline Verification response xml',
  `offline_verify_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'offline verification response',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_tran_no` (`ref_tran_no`(191)),
  KEY `app_id` (`app_id`),
  KEY `payment_config_id` (`payment_config_id`),
  KEY `process_type_id` (`process_type_id`),
  KEY `payment_status` (`payment_status`),
  KEY `is_verified` (`is_verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DELIMITER ;;

CREATE TRIGGER `sp_payment_ai` AFTER INSERT ON `sp_payment` FOR EACH ROW
BEGIN
INSERT INTO sp_payment_history(action_type, action_datetime, sp_payment_id, payment_config_id, app_id, 
process_type_id, app_tracking_no, payment_category_id, pay_mode, pay_mode_code, transaction_id, request_id, 
payment_date, ref_tran_no, ref_tran_date_time, pay_amount, transaction_charge_amount, vat_on_pay_amount,receiver_ac_no,spg_trans_date_time, 
sender_ac_no, contact_name, contact_email, contact_no, payer_id, address, sl_no, status_code, payment_status, is_verified, 
payment_request, payment_request_xml, payment_response, payment_response_xml, verification_request, verification_request_xml, 
verification_response, offline_verify_request_xml, offline_verify_request, offline_verify_response_xml, offline_verify_response, 
verification_response_xml, created_by, created_at, updated_at, updated_by)
VALUES('ONINSERT', NOW(),NEW.id, NEW.payment_config_id, NEW.app_id, 
NEW.process_type_id, NEW.app_tracking_no, NEW.payment_category_id, NEW.pay_mode, NEW.pay_mode_code, NEW.transaction_id, NEW.request_id,
NEW.payment_date, NEW.ref_tran_no, NEW.ref_tran_date_time, NEW.pay_amount, NEW.transaction_charge_amount, NEW.vat_on_pay_amount, NEW.receiver_ac_no,NEW.spg_trans_date_time, 
NEW.sender_ac_no, NEW.contact_name, NEW.contact_email, NEW.contact_no, NEW.payer_id, NEW.address, NEW.sl_no, NEW.status_code, NEW.payment_status, NEW.is_verified, 
NEW.payment_request, NEW.payment_request_xml, NEW.payment_response, NEW.payment_response_xml, NEW.verification_request, NEW.verification_request_xml, 
NEW.verification_response, NEW.offline_verify_request_xml, NEW.offline_verify_request, NEW.offline_verify_response_xml, NEW.offline_verify_response, 
NEW.verification_response_xml, NEW.created_by, NEW.created_at, NEW.updated_at, NEW.updated_by);
END;;

CREATE TRIGGER `sp_payment_au` AFTER UPDATE ON `sp_payment` FOR EACH ROW
BEGIN
INSERT INTO sp_payment_history(action_type, action_datetime, sp_payment_id, payment_config_id, app_id, 
process_type_id, app_tracking_no, payment_category_id, pay_mode, pay_mode_code, transaction_id, request_id, 
payment_date, ref_tran_no, ref_tran_date_time, pay_amount, transaction_charge_amount, vat_on_pay_amount,receiver_ac_no, spg_trans_date_time,
sender_ac_no, contact_name, contact_email, contact_no, payer_id, address, sl_no, status_code, payment_status, is_verified, 
payment_request, payment_request_xml, payment_response, payment_response_xml, verification_request, verification_request_xml, 
verification_response, offline_verify_request_xml, offline_verify_request, offline_verify_response_xml, offline_verify_response, 
verification_response_xml, created_by, created_at, updated_at, updated_by)
VALUES('ONUPDATE', NOW(),NEW.id, NEW.payment_config_id, NEW.app_id, 
NEW.process_type_id, NEW.app_tracking_no, NEW.payment_category_id, NEW.pay_mode, NEW.pay_mode_code, NEW.transaction_id, NEW.request_id,
NEW.payment_date, NEW.ref_tran_no, NEW.ref_tran_date_time, NEW.pay_amount, NEW.transaction_charge_amount, NEW.vat_on_pay_amount, NEW.receiver_ac_no, NEW.spg_trans_date_time,
NEW.sender_ac_no, NEW.contact_name, NEW.contact_email, NEW.contact_no, NEW.payer_id, NEW.address, NEW.sl_no, NEW.status_code, NEW.payment_status, NEW.is_verified, 
NEW.payment_request, NEW.payment_request_xml, NEW.payment_response, NEW.payment_response_xml, NEW.verification_request, NEW.verification_request_xml, 
NEW.verification_response, NEW.offline_verify_request_xml, NEW.offline_verify_request, NEW.offline_verify_response_xml, NEW.offline_verify_response, 
NEW.verification_response_xml, NEW.created_by, NEW.created_at, NEW.updated_at, NEW.updated_by);
END;;

DELIMITER ;

DROP TABLE IF EXISTS `sp_payment_configuration`;
CREATE TABLE `sp_payment_configuration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `process_type_id` int NOT NULL COMMENT 'id from process_type table',
  `payment_step_id` tinyint NOT NULL DEFAULT '0',
  `payment_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `calculation_type` enum('include','exclude') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'include' COMMENT 'if include then we calculate vat/tax , otherwise SBL calculate it',
  `amount` float NOT NULL COMMENT 'Exact Trans Amount',
  `vat_tax_percent` float NOT NULL COMMENT 'Vat Tax percentage',
  `trans_charge_percent` float NOT NULL COMMENT 'Trans charge percentage',
  `trans_charge_min_amount` float NOT NULL COMMENT 'Trans charge min amount',
  `trans_charge_max_amount` float NOT NULL COMMENT 'Trans charge max amount',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0=InActive, 1=Active',
  `is_archive` tinyint NOT NULL DEFAULT '0' COMMENT '1=archieved',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `process_type_id` (`process_type_id`),
  KEY `status` (`status`),
  KEY `is_archive` (`is_archive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sp_payment_details`;
CREATE TABLE `sp_payment_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sp_payment_id` int NOT NULL,
  `payment_distribution_id` int NOT NULL,
  `pay_amount` decimal(12,2) NOT NULL COMMENT 'Exact amount for BAT or BIDA',
  `receiver_ac_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  `purpose` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci DEFAULT NULL,
  `purpose_sbl` enum('TRN','CHL') CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TRN',
  `fix_status` tinyint NOT NULL DEFAULT '1',
  `distribution_type` smallint NOT NULL DEFAULT '0' COMMENT 'Distribution type id',
  `is_verified` tinyint NOT NULL DEFAULT '0' COMMENT '0=Non-verified, 1 = Verified. save from verification response',
  `verification_request` text CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Verification request',
  `verification_response` text CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  `confirm_amount_sbl` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sp_payment_id` (`sp_payment_id`),
  KEY `payment_distribution_id` (`payment_distribution_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sp_payment_distribution`;
CREATE TABLE `sp_payment_distribution` (
  `id` int NOT NULL AUTO_INCREMENT,
  `process_type_id` int NOT NULL COMMENT 'id from process_type table',
  `sp_pay_config_id` int NOT NULL COMMENT 'id from sp_payment_configuration table',
  `stakeholder_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stakeholder_ac_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stakeholder_ac_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose_sbl` enum('TRN','CHL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TRN',
  `pay_amount` float NOT NULL DEFAULT '0' COMMENT 'Exact trans amount',
  `distribution_type` smallint NOT NULL DEFAULT '0' COMMENT 'Distribution type id',
  `fix_status` tinyint NOT NULL DEFAULT '1' COMMENT '1=fixed, 0=unfixed',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0=InActive, 1=Active',
  `is_archive` tinyint NOT NULL DEFAULT '0' COMMENT '1=archieved',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sp_pay_config_id` (`sp_pay_config_id`),
  KEY `status` (`status`),
  KEY `is_archive` (`is_archive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sp_payment_distribution_type`;
CREATE TABLE `sp_payment_distribution_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `is_archive` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sp_payment_distribution_type` (`id`, `name`, `status`, `is_archive`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1,	'Vendor-Service-Fee',	1,	0,	NULL,	0,	NULL,	0),
(2,	'Govt-Service-Fee',	1,	0,	NULL,	0,	NULL,	0),
(3,	'Govt-Application-Fee',	1,	0,	NULL,	0,	NULL,	0),
(4,	'Vendor-Vat-Fee',	1,	0,	NULL,	0,	NULL,	0),
(5,	'Govt-Vat-Fee',	1,	0,	NULL,	0,	NULL,	0),
(6,	'Govt-Vendor-Vat-Fee',	1,	0,	NULL,	0,	NULL,	0);

DROP TABLE IF EXISTS `sp_payment_history`;
CREATE TABLE `sp_payment_history` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `action_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_datetime` datetime DEFAULT NULL,
  `sp_payment_id` bigint NOT NULL,
  `payment_config_id` int NOT NULL DEFAULT '0' COMMENT 'id from payment_configuration table',
  `app_id` int NOT NULL DEFAULT '0' COMMENT 'application id',
  `process_type_id` int NOT NULL DEFAULT '0' COMMENT 'process type id',
  `app_tracking_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Application Tracking Number',
  `payment_category_id` int DEFAULT '0' COMMENT 'id from payment_category table',
  `pay_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'payment mode (ex: bkash).save from payment callback',
  `pay_mode_code` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'payment mode code(ex: bkash=AO1).save from payment callback',
  `transaction_id` bigint DEFAULT NULL COMMENT 'Response transaction id.save from payment callback',
  `request_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Daily Unique request id',
  `payment_date` date DEFAULT NULL COMMENT 'Transaction payment date',
  `ref_tran_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reference Transaction Id',
  `ref_tran_date_time` datetime DEFAULT NULL COMMENT 'Response Transaction Date Time',
  `pay_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Exact amount for BAT or BSCIC',
  `transaction_charge_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Commission Charge for pay_amount.save from verification response',
  `vat_on_transaction_charge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'vat on only transection charge',
  `vat_on_pay_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'vat on only pay amount',
  `total_amount` decimal(12,2) DEFAULT '0.00' COMMENT 'sum of pay amount,transection charge ,vat on transection charge ,vat on pay amount',
  `spg_trans_date_time` datetime DEFAULT NULL COMMENT 'Actual Transaction time of  Sonali Bank',
  `receiver_ac_no` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Receiver bank account no',
  `sender_ac_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Sender bank account no',
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Name',
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Email',
  `contact_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Number',
  `payer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id of this table',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Party Contact Address',
  `sl_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'it will be always 1',
  `status_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=Pending, -1=In Progress, 1=Successful, -2=Exception',
  `is_verified` tinyint NOT NULL DEFAULT '0' COMMENT '0=Non-verified, 1 = Verified. save from verification response',
  `payment_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment request send JSON data',
  `payment_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment request xml',
  `payment_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Payment response xml',
  `verification_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification request',
  `verification_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification request xml',
  `verification_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `offline_verify_request_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline payment Verification request xml',
  `offline_verify_request` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline Verification request',
  `offline_verify_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Offline Verification response xml',
  `offline_verify_response` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'offline verification response',
  `verification_response_xml` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Verification response xml',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sp_payment_id` (`sp_payment_id`),
  KEY `payment_status` (`payment_status`),
  KEY `is_verified` (`is_verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sp_payment_steps`;
CREATE TABLE `sp_payment_steps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `is_archive` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `sp_payment_steps` (`id`, `name`, `status`, `is_archive`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1,	'First Payment',	1,	0,	NULL,	0,	NULL,	0),
(2,	'Second Payment',	1,	0,	NULL,	0,	NULL,	0),
(3,	'Third Payment',	1,	0,	NULL,	0,	NULL,	0);