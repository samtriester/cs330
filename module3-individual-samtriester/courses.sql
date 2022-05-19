CREATE TABLE `courses` (
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL,
  `dept_id` tinyint(3) unsigned NOT NULL,
  `course_code` char(5) NOT NULL,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`dept_id`,`course_code`),
  KEY `dept_id` (`dept_id`,`school_code`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`dept_id`, `school_code`) REFERENCES `departments` (`dept_id`, `school_code`)
)