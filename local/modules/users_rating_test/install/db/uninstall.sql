SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `i_results` DROP FOREIGN KEY `i_results_b_user_foreign`;
ALTER TABLE `i_results` DROP FOREIGN KEY `i_results_i_disciplines_foreign`;
DROP TABLE IF EXISTS `i_results`;
DROP TABLE IF EXISTS `i_disciplines`;
SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE `users` ADD FOREIGN KEY (`view_sex`) REFERENCES `guild_applies`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

