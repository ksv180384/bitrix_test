CREATE TABLE IF NOT EXISTS `i_disciplines` (
  `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `i_results` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` INT(18) NOT NULL,
  `DISCIPLINE_ID` INT(11) UNSIGNED NOT NULL,
  `SCORE` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

ALTER TABLE `i_results` ADD CONSTRAINT `i_results_b_user_foreign`
FOREIGN KEY (`USER_ID`) REFERENCES `b_user`(`ID`)
ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `i_results` ADD CONSTRAINT `i_results_i_disciplines_foreign`
FOREIGN KEY (`DISCIPLINE_ID`) REFERENCES `i_disciplines`(`ID`)
ON DELETE CASCADE ON UPDATE RESTRICT;



