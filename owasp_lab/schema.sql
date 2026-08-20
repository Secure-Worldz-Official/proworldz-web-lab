-- NEXORA SECURITY LABS MASTER SCHEMA (MySQL VERSION)
-- Consolidated database structure for all OWASP modules

-- [A07 / A01] Authentication and Access Control Users
CREATE TABLE IF NOT EXISTS users_owasp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role VARCHAR(50) DEFAULT 'USER',
    status VARCHAR(50) DEFAULT 'ACTIVE',
    bio TEXT,
    secret_token VARCHAR(255),
    mfa_pin VARCHAR(10),
    secret_vault TEXT,
    secret_flag VARCHAR(255),
    reset_token VARCHAR(255),
    session_id VARCHAR(255)
);

-- Seed data for Authentication Modules
INSERT INTO users_owasp (username, password, email, role, secret_flag, mfa_pin, bio, secret_token) VALUES ('admin', 'NexoAdmin_2024_!@', 'admin@nexora.internal', 'ADMIN', 'FLAG{a07_auth_chain_admin}', '8821', 'System administrator of Nexora.', 'TOKEN_MASTER_8821');
INSERT INTO users_owasp (username, password, email, role, secret_vault, bio, secret_token) VALUES ('jdoe', 'password123', 'jdoe@nexora.internal', 'USER', 'FLAG{a07_user_enum_02}', 'Just a regular analyst.', 'TOKEN_JDOE_9921');
INSERT INTO users_owasp (username, password, role, status) VALUES ('locked_user', 'secret', 'USER', 'LOCKED');
INSERT INTO users_owasp (username, password, email, role, secret_flag) VALUES ('staff_viewer', 'staff_pass', 'staff@nexora.internal', 'STAFF', 'FLAG{a07_login_bypass_01}');
INSERT INTO users_owasp (username, password, role) VALUES ('user1', 'pass123', 'USER');
INSERT INTO users_owasp (username, password, email, role, secret_flag) VALUES ('rootadmin', 'root@nexora', 'root@nexora.internal', 'superadmin', 'FLAG{a07_chain_bypass_06}');
INSERT INTO users_owasp (username, password, email, role, secret_flag) VALUES ('guest_acc', 'guest_acc', 'guest@nexora.internal', 'GUEST', 'FLAG{a07_weak_session_03}');

-- [A05] Injection - Employee Intelligence
CREATE TABLE IF NOT EXISTS employees_owasp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    department VARCHAR(255),
    salary VARCHAR(50),
    secret_internal_code VARCHAR(255)
);

-- Seed data for Injection Modules
INSERT INTO employees_owasp (name, position, department, salary, secret_internal_code) VALUES ('Alice Nexora', 'Chief Architect', 'Engineering', '185000', 'FLAG{a05_sql_inject_01}');
INSERT INTO employees_owasp (name, position, department, salary, secret_internal_code) VALUES ('Bob Security', 'Security Analyst', 'Ops', '95000', 'STAFF_8802');
INSERT INTO employees_owasp (name, position, department, salary, secret_internal_code) VALUES ('Charlie Root', 'SysAdmin', 'Ops', '105000', 'SHADOW_001');

-- [A05] Injection - Internal Reports
CREATE TABLE IF NOT EXISTS reports_owasp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    department VARCHAR(255),
    access_level VARCHAR(50),
    user_id INT,
    secret_key VARCHAR(255)
);

INSERT INTO reports_owasp (title, content, department, access_level, secret_key, user_id) VALUES ('Staff Vulnerability Report', 'Identity check status: All employees verified.', 'HR', 'confidential', 'FLAG{a05_report_inject_02}', 1);
INSERT INTO reports_owasp (title, content, department, access_level, secret_key, user_id) VALUES ('Financial Forecast', 'Growth indicators look positive.', 'Finance', 'internal', 'KEY_9921', 2);
INSERT INTO reports_owasp (title, content, department, access_level, secret_key, user_id) VALUES ('Private Intelligence Leak', 'FLAG{a01_horizontal_leak_06}', 'Ops', 'TopSecret', 'SECRET_LEAK_001', 3);

-- [A01] Broken Access - Documents
CREATE TABLE IF NOT EXISTS documents_owasp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255),
    owner_id INT,
    content TEXT,
    is_private INT DEFAULT 1
);

INSERT INTO documents_owasp (title, filename, owner_id, content, is_private) VALUES ('Public Notice', 'notice.txt', 1, 'Welcome to Nexora.', 0);
INSERT INTO documents_owasp (title, filename, owner_id, content, is_private) VALUES ('Private Note', 'private_note.txt', 2, 'FLAG{a01_idor_bypass_01}', 1);


-- [A01] Broken Access - API Keys and Logs
CREATE TABLE IF NOT EXISTS platform_logs_owasp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event VARCHAR(255),
    ip_address VARCHAR(255),
    user_agent VARCHAR(255),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);
