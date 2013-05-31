ALTER TABLE `sale_payments` DROP FOREIGN KEY `sale_payments_ibfk_1`;
ALTER TABLE `sale_payments` DROP KEY `sale_id`;
DROP TABLE `sale_payments`;