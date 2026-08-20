-- SQL Schema for Payment Verifications & OWASP 2026 Lab Access
CREATE TABLE IF NOT EXISTS `payment_verifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` VARCHAR(50) NOT NULL,
  `screenshot_path` VARCHAR(255) NOT NULL,
  `payment_method` VARCHAR(100) NOT NULL,
  `status` ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
  `decline_reason` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `reviewed_at` DATETIME NULL,
  `reviewed_by_admin_id` VARCHAR(100) NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
