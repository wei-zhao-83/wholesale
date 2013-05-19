ALTER TABLE `products` DROP `no_discount`;
ALTER TABLE  `products` ADD  `discount` DECIMAL( 10, 2 ) NOT NULL AFTER  `cash_and_carry`;