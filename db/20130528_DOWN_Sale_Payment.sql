ALTER TABLE `transaction_payments` DROP FOREIGN KEY `transaction_payments_ibfk_1`;
ALTER TABLE `transaction_payments` DROP KEY `transaction_id`;
DROP TABLE `transaction_payments`;