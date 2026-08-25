-- Professional Tournament and Challenges Schema for Proworldz
-- Stores 1v1 and 3v3 coding battles, entry fees, and status

CREATE TABLE IF NOT EXISTS coding_battles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('1v1', '3v3') NOT NULL,
    challenger_id INT NOT NULL, -- User ID (1v1) or Team ID (3v3)
    challenged_id INT NOT NULL, -- User ID (1v1) or Team ID (3v3)
    entry_fee DECIMAL(10,2) DEFAULT 50.00,
    status ENUM('pending', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'pending',
    winner_id INT DEFAULT NULL, -- User ID or Team ID
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    challenge_notes TEXT
);

-- Table to track money transactions for battles
CREATE TABLE IF NOT EXISTS battle_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    battle_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('entry_fee', 'prize_payout', 'refund') NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (battle_id) REFERENCES coding_battles(id) ON DELETE CASCADE
);

-- Index for performance
CREATE INDEX idx_battle_status ON coding_battles(status);
CREATE INDEX idx_battle_type ON coding_battles(type);
