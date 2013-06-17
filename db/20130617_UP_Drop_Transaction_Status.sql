ALTER TABLE `transactions` DROP FOREIGN KEY `FK_EAA81A4C6BF700BD`;
ALTER TABLE `transactions` DROP COLUMN `status_id`;
ALTER TABLE `transactions` ADD COLUMN `status` VARCHAR(125) DEFAULT NULL AFTER `id`;
DROP TABLE `transaction_status`;