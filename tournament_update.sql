-- Tournament System Update SQL
-- Run these on your database

-- 1. Track online status: add last_active column to users table
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_active TIMESTAMP NULL DEFAULT NULL;

-- 2. Add admin approval to coding_battles (pending_admin = needs admin, approved = admin OK'd, rejected = admin denied)
ALTER TABLE coding_battles ADD COLUMN IF NOT EXISTS admin_status ENUM('pending_admin', 'approved', 'rejected') DEFAULT 'pending_admin';

-- 3. Notifications table for battle decisions
CREATE TABLE IF NOT EXISTS battle_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    battle_id INT NOT NULL,
    message TEXT NOT NULL,
    type ENUM('approved', 'rejected') NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (battle_id) REFERENCES coding_battles(id) ON DELETE CASCADE
);
