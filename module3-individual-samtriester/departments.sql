CREATE TABLE `departments` (
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL,
  `dept_id` tinyint(3) unsigned NOT NULL,
  `abbreviation` varchar(9) DEFAULT NULL,
  `dept_name` varchar(200) NOT NULL,
  PRIMARY KEY (`dept_id`,`school_code`)
)