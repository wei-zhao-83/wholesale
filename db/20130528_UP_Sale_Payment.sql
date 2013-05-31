CREATE TABLE `sale_payments` (
    id INT AUTO_INCREMENT NOT NULL,
    sale_id INT DEFAULT NULL,
    payment_type VARCHAR(255),
    status VARCHAR(255),
    amount DECIMAL(10, 2),
    comment VARCHAR(255),
    created_at DATETIME DEFAULT NULL,
    PRIMARY KEY(id)
) ENGINE = InnoDB;
ALTER TABLE `sale_payments` ADD FOREIGN KEY (sale_id) REFERENCES sales(id);