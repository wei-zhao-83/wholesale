CREATE TABLE `transaction_payments` (
    id INT AUTO_INCREMENT NOT NULL,
    transaction_id INT DEFAULT NULL,
    payment_type VARCHAR(255),
    status VARCHAR(255),
    amount DECIMAL(10, 2),
    comment VARCHAR(255),
    created_at DATETIME DEFAULT NULL,
    PRIMARY KEY(id)
) ENGINE = InnoDB;
ALTER TABLE `transaction_payments` ADD FOREIGN KEY (transaction_id) REFERENCES transactions(id);