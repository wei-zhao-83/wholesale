ALTER TABLE `products` DROP `discount`;
ALTER TABLE  `products` ADD  `no_discount` TINYINT(1) NOT NULL DEFAULT 0 AFTER  `active`;