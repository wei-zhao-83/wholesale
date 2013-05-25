ALTER TABLE  `transaction_item` ADD  `picked` INT(11) NOT NULL DEFAULT 0 AFTER  `qty`;
ALTER TABLE  `transaction_item` ADD  `shipped` INT(11) NOT NULL DEFAULT 0 AFTER  `picked`;