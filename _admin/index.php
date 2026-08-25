<?php
session_start();

$db_host = "sql204.infinityfree.com";
$db_user = "if0_40322633";
$db_pass = "HDm584vG4kZDnt";
$db_name = "if0_40322633_students";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generatePasswordLikePattern() {
    $uppercase = range('A', 'Z');
    $lowercase = range('a', 'z');
    $numbers = range(0, 9);
    $symbols = ['@', '#', '$', '%', '&', '!', '*', '?'];
    
    $password = '';
    $password .= $uppercase[array_rand($uppercase)];
    $password .= $lowercase[array_rand($lowercase)];
    $password .= $lowercase[array_rand($lowercase)];
    $password .= $symbols[array_rand($symbols)];
    $password .= $uppercase[array_rand($uppercase)];
    $password .= $lowercase[array_rand($lowercase)];
    $password .= $numbers[array_rand($numbers)];
    $password .= $symbols[array_rand($symbols)];
    
    return $password;
}

// Ensure admins table exists
$conn->query("CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$is_logged_in = isset($_SESSION['admin_username']);
$current_admin = null;
$admin_role = null;

if ($is_logged_in) {
    $username = $_SESSION['admin_username'];
    $query = "SELECT * FROM admins WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $current_admin = $result->fetch_assoc();
            $admin_role = $current_admin['role'] ?? 'admin';
        } else if ($username === 'mohamed') {
            $current_admin = ['username' => 'mohamed', 'role' => 'root'];
            $admin_role = 'root';
        } else {
            session_destroy();
            $is_logged_in = false;
        }
        $stmt->close();
    } else if ($username === 'mohamed') {
        $current_admin = ['username' => 'mohamed', 'role' => 'root'];
        $admin_role = 'root';
    }
}

$login_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
               (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1');
    
    $username = strtolower(trim($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $login_error = 'Username and password are required';
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $login_error]);
            exit;
        }
    } else {
        $auth_success = false;
        $admin_role_found = 'admin';
        
        if ($conn && !$conn->connect_error) {
            $raw_user = trim($_POST['username'] ?? '');
            $stmt = @$conn->prepare("SELECT username, password, role FROM admins WHERE LOWER(username) = LOWER(?) LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $raw_user);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $admin = $result->fetch_assoc();
                    error_log('Admin login lookup: matching admin row found.');
                    error_log('Admin login password storage: ' . (preg_match('/^\$2[aby]\$/', $admin['password']) ? 'bcrypt hash' : 'plain or non-bcrypt value') . '.');
                    if ($password == $admin['password']) {
                        $auth_success = true;
                        $admin_role_found = $admin['role'] ?? 'admin';
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['role'] = $admin_role_found;
                        error_log('Admin login: password matched and session was created.');
                    } else {
                        error_log('Admin login: password did not match the stored value.');
                    }
                } else {
                    error_log('Admin login lookup: no matching admin row found.');
                }
                $stmt->close();
            } else {
                error_log('Admin login: database statement could not be prepared.');
            }
        }
        
        if ($auth_success) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'role' => $admin_role_found]);
                exit;
            } else {
                header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
                exit;
            }
        } else {
            $login_error = 'Invalid username or password';
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $login_error]);
                exit;
            }
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . strtok($_SERVER['PHP_SELF'], '?'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_student') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || !in_array($admin_role, ['root', 'admin'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $id = trim($_POST['id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $mother_phone = trim($_POST['mother_phone'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $father_phone = trim($_POST['father_phone'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $password = generatePasswordLikePattern();
    $eagle_coins = isset($_POST['eagle_coins']) ? intval($_POST['eagle_coins']) : 0;
    $assignments = $_POST['assignments'] ?? '[]';
    $assigns_complete = isset($_POST['assigns_complete']) ? intval($_POST['assigns_complete']) : 0;
    $waiting_assigns = $_POST['waiting_assigns'] ?? '[]';
    
    if (empty($id) || empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'ID, name, and email are required']);
        exit;
    }
    
    $check_query = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    if ($check_stmt) {
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            $check_stmt->close();
            exit;
        }
        $check_stmt->close();
    }
    
    $access = $_POST['access'] ?? 'false';

    // Calculate lab access expiry date in UTC
    $duration_days   = intval($_POST['lab_access_duration'] ?? 30);
    $lab_expiry_date = gmdate('Y-m-d H:i:s', time() + ($duration_days * 86400));
    
    $query = "INSERT INTO users (id, name, gender, phone, address, mother_name, mother_phone, father_name, father_phone, email, dob, passw, eagle_coins, assignments, course, assigns_complete, waiting_assigns, access, labAccessExpiryDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssssssssssississs", $id, $name, $gender, $phone, $address, $mother_name, $mother_phone, $father_name, $father_phone, $email, $dob, $password, $eagle_coins, $assignments, $course, $assigns_complete, $waiting_assigns, $access, $lab_expiry_date);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Student added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add student: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_students') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $query = "SELECT id, name, email, phone, gender, address, mother_name, mother_phone, father_name, father_phone, dob, course, eagle_coins, assignments, assigns_complete, waiting_assigns, IPADDR,passw,access FROM users ORDER BY id DESC";
    $result = $conn->query($query);
    
    $students = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    
    echo json_encode(['success' => true, 'data' => $students]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_student') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $student_id = $_GET['id'] ?? '';
    if (empty($student_id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID is required']);
        exit;
    }
    
    $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_student') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || !in_array($admin_role, ['root', 'admin'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $student_id = $_POST['id'] ?? '';
    if (empty($student_id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID is required']);
        exit;
    }
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $mother_phone = trim($_POST['mother_phone'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $father_phone = trim($_POST['father_phone'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $eagle_coins = isset($_POST['eagle_coins']) ? intval($_POST['eagle_coins']) : 0;
    $assignments = $_POST['assignments'] ?? '[]';
    $assigns_complete = isset($_POST['assigns_complete']) ? intval($_POST['assigns_complete']) : 0;
    $waiting_assigns = $_POST['waiting_assigns'] ?? '[]';
    $access = trim($_POST['access'] ?? 'false');
    
    $query = "UPDATE users SET name=?, gender=?, phone=?, address=?, mother_name=?, mother_phone=?, father_name=?, father_phone=?, email=?, dob=?, course=?, eagle_coins=?, assignments=?, assigns_complete=?, waiting_assigns=?, access=? WHERE id=?";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // pattern: 11s, 1i (eagle_coins), 1s (assignments), 1i (assigns_complete), 1s (waiting_assigns), 1s (access), 1s (id)
        $stmt->bind_param("sssssssssssisisss", $name, $gender, $phone, $address, $mother_name, $mother_phone, $father_name, $father_phone, $email, $dob, $course, $eagle_coins, $assignments, $assigns_complete, $waiting_assigns, $access, $student_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update student']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_student') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || $admin_role !== 'root') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $student_id = $_POST['id'] ?? '';
    if (empty($student_id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID is required']);
        exit;
    }
    
    $query = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $student_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete student']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_admins') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || $admin_role !== 'root') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $query = "SELECT username, role FROM admins ORDER BY username";
    $result = $conn->query($query);
    
    $admins = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
    }
    
    echo json_encode(['success' => true, 'data' => $admins]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || $admin_role !== 'root') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'admin';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        exit;
    }
    
    $check_query = "SELECT username FROM admins WHERE username = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    if ($check_stmt) {
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            $check_stmt->close();
            exit;
        }
        $check_stmt->close();
    }
    
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $query = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Admin created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create admin']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_admin') {
    header('Content-Type: application/json');
    
    if (!$is_logged_in || $admin_role !== 'root') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $username = trim($_POST['username'] ?? '');
    if (empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Username is required']);
        exit;
    }
    
    $query = "DELETE FROM admins WHERE username=? AND role='admin'";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Admin deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete admin']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProWorldz - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/eaglone/p-eaglone.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #e5191e;
            --secondary: #ffffff;
            --accent: #ff2a2f;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --success: #ff2a2f;
            --danger: #e5191e;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.15);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.2);
            --transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            --font-body: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #000000 0%, #0f0f0f 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            font-family: var(--font-body);
            overflow-x: hidden;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            animation: slideUpFade 0.6s ease 0.1s both;
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #000000 0%, #080808 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .login-logo {
            font-size: 48px;
            margin-bottom: 20px;
            display: inline-block;
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 14px;
            transition: var(--transition);
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-input:focus {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 3px rgba(139, 12, 16, 0.05);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            outline: none;
            text-decoration: none;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #ffffff 0%, #d0d0d0 100%);
            color: #000000;
            border: none;
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            width: auto;
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #e5191e;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .btn-success {
            background: rgba(16, 185, 129, 0.1);
            color: #ff2a2f;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-top-color: #000000;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            animation: slideDownAlert 0.3s ease;
        }

        @keyframes slideDownAlert {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fecaca;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #ff2a2f;
        }

        .hidden {
            display: none !important;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: rgba(0, 0, 0, 0.6);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            padding: 30px 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-logo {
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo-icon {
            font-size: 28px;
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-logo-text h2 {
            font-size: 16px;
            font-weight: 700;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .nav-section {
            margin-bottom: 30px;
            padding: 0 15px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 10px;
            margin-bottom: 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
            font-weight: 500;
            position: relative;
            margin-bottom: 6px;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-link.active {
            color: var(--text-primary);
            background: rgba(139, 12, 16, 0.1);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            border-radius: 0 2px 2px 0;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 40px;
            overflow-y: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .top-bar-title h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .top-bar-title p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(139, 12, 16, 0.1) 0%, rgba(139, 12, 16, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 1px solid rgba(139, 12, 16, 0.1);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-primary);
        }

        .form-container {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group-full {
            margin-bottom: 20px;
        }

        .form-group-full textarea {
            min-height: 100px;
            font-family: var(--font-body);
            padding: 12px 16px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            border-bottom: 2px solid rgba(139, 12, 16, 0.1);
        }

        .data-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 14px;
        }

        .data-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(139, 12, 16, 0.1);
            border-radius: 16px;
            padding: 40px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(139, 12, 16, 0.1);
        }

        .modal-header h2 {
            font-size: 22px;
            font-weight: 700;
        }

        .close-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-btn:hover {
            color: var(--danger);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .info-label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .info-value {
            font-size: 15px;
            color: var(--text-primary);
            font-weight: 500;
            word-break: break-word;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(239, 68, 68, 0.1);
            color: #e5191e;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                width: 250px;
                transform: translateX(-100%);
                transition: var(--transition);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
<div class="login-wrapper">
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1>ProWorldz Admin</h1>
            <p>Student Management System</p>
        </div>
        <div class="login-body">
            <div id="login-error" class="alert alert-danger <?php echo empty($login_error) ? 'hidden' : ''; ?>">
                <i class="fas fa-circle-exclamation"></i>
                <span id="error-text"><?php echo htmlspecialchars($login_error ?? 'Invalid credentials'); ?></span>
            </div>

            <div id="login-success" class="alert alert-success hidden">
                <i class="fas fa-check-circle"></i>
                <span id="success-text">Login successful!</span>
            </div>

            <form id="login-form" method="POST" action="" onsubmit="handleLogin(event)">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="Enter username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-primary" id="login-button">
                    <span id="button-text">Login</span>
                    <div id="spinner" class="spinner hidden"></div>
                </button>
            </form>

        </div>
    </div>
</div>

<script>
    function handleLogin(event) {
        event.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const button = document.getElementById('login-button');
        const spinner = document.getElementById('spinner');
        const errorDiv = document.getElementById('login-error');
        const successDiv = document.getElementById('login-success');
        const buttonText = document.getElementById('button-text');

        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');

        if (!username || !password) {
            errorDiv.classList.remove('hidden');
            document.getElementById('error-text').textContent = 'Username and password are required';
            return;
        }

        button.disabled = true;
        spinner.classList.remove('hidden');
        buttonText.textContent = 'Logging in...';

        const formData = new FormData();
        formData.append('action', 'login');
        formData.append('is_ajax', '1');
        formData.append('username', username);
        formData.append('password', password);

        fetch(window.location.pathname, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                successDiv.classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = window.location.pathname;
                }, 600);
            } else {
                errorDiv.classList.remove('hidden');
                document.getElementById('error-text').textContent = (data && data.message) ? data.message : 'Invalid credentials';
                button.disabled = false;
                spinner.classList.add('hidden');
                buttonText.textContent = 'Login';
            }
        })
        .catch(error => {
            console.warn('AJAX login fallback to standard submit:', error);
            // Fallback to standard form POST submission if AJAX gets intercepted
            document.getElementById('login-form').submit();
        });
    }
</script>

<?php else: ?>
<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="sidebar-logo-text">
                <h2>ProWorldz</h2>
                <p>Admin Panel</p>
            </div>
        </div>

        <nav class="nav-section">
            <div class="nav-section-title">Menu</div>
            
            <?php if ($admin_role === 'root'): ?>
                <button class="nav-link active" data-section="student-entry">
                    <i class="fas fa-user-plus"></i>
                    Student Entry
                </button>
                <button class="nav-link" data-section="student-management">
                    <i class="fas fa-users"></i>
                    Student Management
                </button>
                <button class="nav-link" data-section="admin-management">
                    <i class="fas fa-shield-alt"></i>
                    Admin Management
                </button>
            <?php else: ?>
                <button class="nav-link active" data-section="student-entry">
                    <i class="fas fa-user-plus"></i>
                    Student Entry
                </button>
                <button class="nav-link" data-section="student-management">
                    <i class="fas fa-users"></i>
                    Student Management
                </button>
            <?php endif; ?>
            <a href="owasp-2026-requests.php" class="nav-link">
                <i class="fas fa-shield-halved"></i>
                OWASP 2026 Request
            </a>

            <div class="nav-section-title" style="margin-top: 30px;">Account</div>
            <a href="?logout=true" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-title">
                <h1 id="page-title">Dashboard</h1>
                <p id="page-subtitle">Manage your system</p>
            </div>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($current_admin['username']); ?></div>
                    <div class="user-role"><?php echo strtoupper($admin_role); ?></div>
                </div>
                <div class="user-avatar"><?php echo strtoupper(substr($current_admin['username'], 0, 1)); ?></div>
            </div>
        </div>

        <div id="student-entry" class="content-section active">
            <h2 class="section-title">Add New Student</h2>
            <div class="form-container">
                <form id="student-form" onsubmit="addStudent(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="id" class="form-input" placeholder="Enter student ID" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-input" placeholder="Enter student name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-input">
                                <option value="Male" style="color:black;">Male</option>
                                <option value="Female" style="color:black;">Female</option>
                                <option value="Other" style="color:black;">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-input">
                                <option value="" style="color: black;">Select Course</option>
                                <option value="Secure X" style="color: black;">Secure X</option>
                                <option value="AI Verse Web Labs" style="color: black;">AI Verse Web Labs</option>
                                <option value="Hunt Elite" style="color: black;">Hunt Elite</option>
                                <option value="Creative Craft" style="color: black;">Creative Craft</option>
                                <option value="Py Desk Systems" style="color: black;">Py Desk Systems</option>
                                <option value="Biz Dev" style="color: black;">Biz Dev</option>
                                <option value="Code Foundry" style="color: black;">Code Foundry</option>
                                <option value="Startup Gene Labs" style="color: black;">Startup Gene Labs</option>
                                <option value="CLI++ Systems" style="color: black;">CLI++ Systems</option>
                                <option value="APMAN" style="color: black;">APMAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Eagle Coins</label>
                            <input type="number" name="eagle_coins" class="form-input" placeholder="0" value="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mother Name</label>
                            <input type="text" name="mother_name" class="form-input" placeholder="Enter mother's name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mother Phone</label>
                            <input type="text" name="mother_phone" class="form-input" placeholder="Enter mother's phone">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" class="form-input" placeholder="Enter father's name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Father Phone</label>
                            <input type="text" name="father_phone" class="form-input" placeholder="Enter father's phone">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Access</label>
                            <select name="access" class="form-input" style="background-color: #000000; color: white;">
                                <option value="false">False</option>
                                <option value="true">True</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lab Access Duration</label>
                            <select name="lab_access_duration" class="form-input" required style="background-color:#000000;color:white;">
                                <option value="30"  style="color:white;">1 Month (30 days)</option>
                                <option value="365" style="color:white;">1 Year (365 days)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-full">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-input" placeholder="Enter student address"></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="student-management" class="content-section">
            <h2 class="section-title">Student Management</h2>
            <div id="students-alert"></div>
            <div class="table-container">
                <table class="data-table" id="students-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Eagle Coins</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($admin_role === 'root'): ?>
        <div id="admin-management" class="content-section">
            <h2 class="section-title">Admin Management</h2>
            
            <div class="form-container">
                <h3 style="margin-bottom: 20px; font-size: 18px;">Add New Admin</h3>
                <form id="admin-form" onsubmit="addAdmin(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-input" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input" placeholder="Enter password" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-input" style="background-color: #000000;">
                                <option value="admin" style="color: white;">Admin</option>
                                <option value="root" style="color: white;">Root</option>
                            </select>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Add Admin
                        </button>
                    </div>
                </form>
            </div>

            <div id="admins-alert"></div>
            <div class="table-container">
                <table class="data-table" id="admins-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="admins-tbody">
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px;">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="student-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Student Information</h2>
            <button class="close-btn" onclick="closeModal('student-modal')">×</button>
        </div>
        <div id="modal-body"></div>
        <div class="action-buttons" style="margin-top: 30px;">
            <?php if ($admin_role === 'root'): ?>
                <button class="btn btn-danger btn-sm" id="delete-btn" onclick="deleteStudent()">
                    <i class="fas fa-trash"></i> Delete Student
                </button>
            <?php endif; ?>
            <?php if (in_array($admin_role, ['root', 'admin'])): ?>
                <button class="btn btn-success btn-sm" id="edit-btn" onclick="editStudent()">
                    <i class="fas fa-edit"></i> Edit Student
                </button>
            <?php endif; ?>
            <button class="btn btn-primary btn-sm" onclick="closeModal('student-modal')">Close</button>
        </div>
    </div>
</div>

<div id="edit-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Student</h2>
            <button class="close-btn" onclick="closeModal('edit-modal')">×</button>
        </div>
        <form id="edit-form" onsubmit="saveEditedStudent(event)">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Student ID</label>
                    <input type="text" name="id" class="form-input" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-input">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Eagle Coins</label>
                    <input type="number" name="eagle_coins" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Access</label>
                    <select name="access" class="form-input" style="background-color: #000000; color: white;">
                        <option value="false">False</option>
                        <option value="true">True</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mother Name</label>
                    <input type="text" name="mother_name" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Mother Phone</label>
                    <input type="text" name="mother_phone" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Father Phone</label>
                    <input type="text" name="father_phone" class="form-input">
                </div>
            </div>

            <div class="form-group-full">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-input"></textarea>
            </div>

            <div class="action-buttons" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="closeModal('edit-modal')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentStudentId = null;
    let allStudents = [];

    function getAssignmentCount(assignmentsJson) {
        try {
            if (!assignmentsJson) return 0;
            const assignments = JSON.parse(assignmentsJson);
            return Array.isArray(assignments) ? assignments.length : 0;
        } catch(e) {
            return 0;
        }
    }

    function displayAssignments(assignmentsJson) {
        try {
            if (!assignmentsJson) {
                return '<p style="color: #a0a0a0; font-size: 13px;">No assignments</p>';
            }
            
            const assignments = JSON.parse(assignmentsJson);
            if (!Array.isArray(assignments) || assignments.length === 0) {
                return '<p style="color: #a0a0a0; font-size: 13px;">No assignments</p>';
            }

            return assignments.map((assignment, index) => `
                <div style="background: rgba(255,255,255,0.05); padding: 12px; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid rgba(16,185,129,0.5);">
                    <div style="margin-bottom: 8px;">
                        <span class="info-label">Title</span>
                        <span class="info-value">${escapeHtml(assignment.title || 'N/A')}</span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span class="info-label">Link</span>
                        <a href="${escapeHtml(assignment.link || '#')}" target="_blank" style="color: #ff2a2f; text-decoration: underline; font-size: 13px;">
                            ${escapeHtml(assignment.link || 'N/A')}
                        </a>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span class="info-label">Eagle Coins</span>
                            <span class="info-value" style="color: #8b0c10;">${escapeHtml(assignment.coin || '0')}</span>
                        </div>
                        <button class="btn btn-success btn-sm" onclick="submitAssignment('${escapeHtml(currentStudentId)}', ${index}, '${escapeHtml(assignment.coin || '0')}')">
                            <i class="fas fa-check"></i> Submit
                        </button>
                    </div>
                </div>
            `).join('');
        } catch(error) {
            console.error('Error parsing assignments:', error);
            return '<p style="color: #e5191e; font-size: 13px;">Error loading assignments</p>';
        }
    }

    function submitAssignment(studentId, assignmentIndex, coins) {
        if (!confirm(`Submit this assignment and award ${coins} Eagle Coins to the student?`)) return;

        const student = allStudents.find(s => s.id === studentId);
        if (!student) return;

        try {
            let waitingAssigns = [];
            if (student.waiting_assigns && student.waiting_assigns !== '' && student.waiting_assigns !== 'null') {
                try {
                    const parsed = JSON.parse(student.waiting_assigns);
                    waitingAssigns = Array.isArray(parsed) ? parsed : [];
                } catch(e) {
                    console.error('Error parsing waiting_assigns:', e);
                    waitingAssigns = [];
                }
            }

            let completeAssigns = [];
            if (student.assignments && student.assignments !== '' && student.assignments !== 'null') {
                try {
                    const parsed = JSON.parse(student.assignments);
                    completeAssigns = Array.isArray(parsed) ? parsed : [];
                } catch(e) {
                    console.error('Error parsing assignments:', e);
                    completeAssigns = [];
                }
            }

            if (!waitingAssigns[assignmentIndex]) {
                showAlert('students-alert', 'Assignment not found', 'danger');
                return;
            }

            completeAssigns.push(waitingAssigns[assignmentIndex]);
            waitingAssigns.splice(assignmentIndex, 1);

            const newEagleCoins = parseInt(student.eagle_coins || 0) + parseInt(coins);
            const newAssignsComplete = parseInt(student.assigns_complete || 0) + 1;

            const formData = new FormData();
            formData.append('action', 'update_student');
            formData.append('id', studentId);
            formData.append('name', student.name);
            formData.append('gender', student.gender || '');
            formData.append('phone', student.phone || '');
            formData.append('address', student.address || '');
            formData.append('mother_name', student.mother_name || '');
            formData.append('mother_phone', student.mother_phone || '');
            formData.append('father_name', student.father_name || '');
            formData.append('father_phone', student.father_phone || '');
            formData.append('email', student.email);
            formData.append('dob', student.dob || '');
            formData.append('course', student.course || '');
            formData.append('eagle_coins', newEagleCoins);
            formData.append('assignments', JSON.stringify(completeAssigns));
            formData.append('assigns_complete', newAssignsComplete);
            formData.append('waiting_assigns', JSON.stringify(waitingAssigns));

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    student.eagle_coins = newEagleCoins;
                    student.assigns_complete = newAssignsComplete;
                    student.assignments = JSON.stringify(completeAssigns);
                    student.waiting_assigns = JSON.stringify(waitingAssigns);
                    
                    showAlert('students-alert', `✓ Assignment submitted! ${coins} coins awarded to ${student.name}!`, 'success');
                    
                    setTimeout(() => {
                        viewStudent(studentId);
                    }, 500);
                } else {
                    showAlert('students-alert', 'Error: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showAlert('students-alert', 'Network error occurred', 'danger');
            });
        } catch(error) {
            console.error('General Error:', error);
            showAlert('students-alert', 'Error processing assignment: ' + error.message, 'danger');
        }
    }

    function showSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(el => {
            el.classList.remove('active');
        });
        
        document.querySelectorAll('.nav-link').forEach(el => {
            el.classList.remove('active');
        });
        
        document.getElementById(sectionId).classList.add('active');
        
        event.target.classList.add('active');

        const titles = {
            'student-entry': { title: 'Add New Student', subtitle: 'Enter student information' },
            'student-management': { title: 'Student Management', subtitle: 'View and manage students' },
            'admin-management': { title: 'Admin Management', subtitle: 'Manage admin users' }
        };

        if (titles[sectionId]) {
            document.getElementById('page-title').textContent = titles[sectionId].title;
            document.getElementById('page-subtitle').textContent = titles[sectionId].subtitle;
        }

        if (sectionId === 'student-management') {
            loadStudents();
        } else if (sectionId === 'admin-management') {
            loadAdmins();
        }
    }

    function addStudent(event) {
        event.preventDefault();
        
        const form = document.getElementById('student-form');
        const formData = new FormData(form);
        formData.append('action', 'add_student');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Student added successfully!', 'success');
                form.reset();
                setTimeout(() => loadStudents(), 1000);
            } else {
                showToast(data.message || 'An error occurred', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Network error — please try again', 'danger');
        });
    }

    function loadStudents() {
        fetch('?action=get_students')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allStudents = data.data;
                    displayStudents(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function displayStudents(students) {
        const tbody = document.getElementById('students-tbody');
        
        if (students.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">No students found</td></tr>';
            return;
        }

        tbody.innerHTML = students.map(student => `
            <tr>
                <td>${escapeHtml(student.id)}</td>
                <td>${escapeHtml(student.name)}</td>
                <td>${escapeHtml(student.email)}</td>
                <td>${escapeHtml(student.phone || '-')}</td>
                <td>${escapeHtml(student.course || '-')}</td>
                <td>${student.eagle_coins || 0}</td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="viewStudent('${escapeHtml(student.id)}')">
                        <i class="fas fa-info-circle"></i> Info
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function viewStudent(studentId) {
        const student = allStudents.find(s => s.id === studentId);
        if (!student) return;

        currentStudentId = studentId;

        const modalBody = document.getElementById('modal-body');
        modalBody.innerHTML = `
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Student ID</span>
                    <span class="info-value">${escapeHtml(student.id)}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">${escapeHtml(student.name)}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">${escapeHtml(student.email)}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">${escapeHtml(student.phone || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value">${escapeHtml(student.dob || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gender</span>
                    <span class="info-value">${escapeHtml(student.gender || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Course</span>
                    <span class="info-value">${escapeHtml(student.course || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Eagle Coins</span>
                    <span class="info-value">${student.eagle_coins || 0}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother Name</span>
                    <span class="info-value">${escapeHtml(student.mother_name || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother Phone</span>
                    <span class="info-value">${escapeHtml(student.mother_phone || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Father Name</span>
                    <span class="info-value">${escapeHtml(student.father_name || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Father Phone</span>
                    <span class="info-value">${escapeHtml(student.father_phone || '-')}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Assignments Completed</span>
                    <span class="info-value">${student.assigns_complete || 0}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Access</span>
                    <span class="info-value" style="color: ${student.access === 'true' ? '#00ff9d' : '#e5191e'};">
                        ${student.access === 'true' ? 'True' : 'False'}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Password</span>
                    <span class="info-value">
                        <span style="display: inline-block; margin-right: 10px;">${escapeHtml(student.passw || '')}</span>
                        <button class="btn btn-sm" onclick="copyToClipboard('${escapeHtml(student.passw || '')}', event)" style="background: rgba(255,255,255,0.1); color: #ff2a2f; padding: 4px 8px; font-size: 12px;">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </span>
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 15px; border-radius: 10px; margin-top: 20px;">
                <span class="info-label">Address</span>
                <span class="info-value">${escapeHtml(student.address || '-')}</span>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 15px; border-radius: 10px; margin-top: 20px;">
                <span class="info-label">Assignments (${getAssignmentCount(student.waiting_assigns)})</span>
                <div id="assignments-list" style="margin-top: 10px;">
                    ${displayAssignments(student.waiting_assigns)}
                </div>
            </div>
        `;

        openModal('student-modal');
    }

    function copyToClipboard(text, event) {
        event.preventDefault();
        event.stopPropagation();
        
        navigator.clipboard.writeText(text).then(() => {
            const button = event.target.closest('button');
            if (!button) return;
            
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.style.background = 'rgba(16,185,129,0.2)';
            button.style.color = '#ff2a2f';
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.style.background = 'rgba(255,255,255,0.1)';
                button.style.color = '#ff2a2f';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy password');
        });
    }

    function editStudent() {
        const student = allStudents.find(s => s.id === currentStudentId);
        if (!student) return;

        const form = document.getElementById('edit-form');
        form.querySelector('input[name="id"]').value = student.id;
        form.querySelector('input[name="name"]').value = student.name;
        form.querySelector('input[name="email"]').value = student.email;
        form.querySelector('input[name="phone"]').value = student.phone || '';
        form.querySelector('input[name="dob"]').value = student.dob || '';
        form.querySelector('select[name="gender"]').value = student.gender || '';
        form.querySelector('input[name="course"]').value = student.course || '';
        form.querySelector('input[name="eagle_coins"]').value = student.eagle_coins || 0;
        
        let accessField = form.querySelector('select[name="access"]');
        if (accessField) {
            accessField.value = student.access === 'true' ? "true" : "false";
        }
        
        form.querySelector('input[name="mother_name"]').value = student.mother_name || '';
        form.querySelector('input[name="mother_phone"]').value = student.mother_phone || '';
        form.querySelector('input[name="father_name"]').value = student.father_name || '';
        form.querySelector('input[name="father_phone"]').value = student.father_phone || '';
        form.querySelector('textarea[name="address"]').value = student.address || '';

        closeModal('student-modal');
        openModal('edit-modal');
    }

    function saveEditedStudent(event) {
        event.preventDefault();
        
        const form = document.getElementById('edit-form');
        const formData = new FormData(form);
        formData.append('action', 'update_student');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('students-alert', 'Student updated successfully!', 'success');
                closeModal('edit-modal');
                setTimeout(() => loadStudents(), 1000);
            } else {
                showAlert('students-alert', data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('students-alert', 'An error occurred', 'danger');
        });
    }

    function deleteStudent() {
        if (!confirm('Are you sure you want to delete this student?')) return;

        const formData = new FormData();
        formData.append('action', 'delete_student');
        formData.append('id', currentStudentId);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('students-alert', 'Student deleted successfully!', 'success');
                closeModal('student-modal');
                setTimeout(() => loadStudents(), 1000);
            } else {
                showAlert('students-alert', data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('students-alert', 'An error occurred', 'danger');
        });
    }

    function addAdmin(event) {
        event.preventDefault();
        
        const form = document.getElementById('admin-form');
        const formData = new FormData(form);
        formData.append('action', 'add_admin');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('admins-alert', 'Admin created successfully!', 'success');
                form.reset();
                setTimeout(() => loadAdmins(), 1000);
            } else {
                showAlert('admins-alert', data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('admins-alert', 'An error occurred', 'danger');
        });
    }

    function loadAdmins() {
        fetch('?action=get_admins')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAdmins(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function displayAdmins(admins) {
        const tbody = document.getElementById('admins-tbody');
        
        if (admins.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 40px;">No admins found</td></tr>';
            return;
        }

        tbody.innerHTML = admins.map(admin => `
            <tr>
                <td>${escapeHtml(admin.username)}</td>
                <td><span style="background: rgba(16,185,129,0.2); padding: 4px 8px; border-radius: 4px; font-size: 12px;">${admin.role.toUpperCase()}</span></td>
                <td>
                    ${admin.role === 'admin' ? `
                        <button class="btn btn-danger btn-sm" onclick="deleteAdmin('${escapeHtml(admin.username)}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    ` : '<span style="color: #a0a0a0;">-</span>'}
                </td>
            </tr>
        `).join('');
    }

    function deleteAdmin(username) {
        if (!confirm('Are you sure you want to delete this admin?')) return;

        const formData = new FormData();
        formData.append('action', 'delete_admin');
        formData.append('username', username);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('admins-alert', 'Admin deleted successfully!', 'success');
                setTimeout(() => loadAdmins(), 1000);
            } else {
                showAlert('admins-alert', data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('admins-alert', 'An error occurred', 'danger');
        });
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    function showAlert(containerId, message, type) {
        const container = document.getElementById(containerId);
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation';
        
        container.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="fas ${icon}"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;

        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.remove('show');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nav-link').forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section');
                if (sectionId) {
                    showSection(sectionId);
                }
            });
        });
    });

    // ── Admin Toast Notification ──────────────────────────────────────────
    (function() {
        const style = document.createElement('style');
        style.textContent = `
            #admin-toast {
                position: fixed;
                bottom: 28px;
                right: 28px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                pointer-events: none;
            }
            .admin-toast-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 14px 18px;
                border-radius: 10px;
                font-family: var(--font-body, 'Poppins', sans-serif);
                font-size: 14px;
                font-weight: 500;
                max-width: 360px;
                pointer-events: all;
                animation: toastIn 0.35s cubic-bezier(0.16,1,0.3,1) both;
                box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            }
            .admin-toast-item.toast-success {
                background: rgba(16,185,129,0.12);
                border: 1px solid rgba(16,185,129,0.35);
                color: #6ee7b7;
            }
            .admin-toast-item.toast-danger {
                background: rgba(239,68,68,0.12);
                border: 1px solid rgba(239,68,68,0.35);
                color: #fca5a5;
            }
            .admin-toast-item.toast-out {
                animation: toastOut 0.3s ease forwards;
            }
            @keyframes toastIn {
                from { opacity: 0; transform: translateY(16px) scale(0.96); }
                to   { opacity: 1; transform: translateY(0)   scale(1); }
            }
            @keyframes toastOut {
                from { opacity: 1; transform: translateY(0); }
                to   { opacity: 0; transform: translateY(10px); }
            }
        `;
        document.head.appendChild(style);

        const container = document.createElement('div');
        container.id = 'admin-toast';
        document.body.appendChild(container);
    })();

    function showToast(message, type) {
        type = type === 'success' ? 'success' : 'danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation';

        const item = document.createElement('div');
        item.className = `admin-toast-item toast-${type}`;
        item.innerHTML = `<i class="fas ${icon}"></i><span>${escapeHtml(message)}</span>`;

        document.getElementById('admin-toast').appendChild(item);

        setTimeout(() => {
            item.classList.add('toast-out');
            item.addEventListener('animationend', () => item.remove(), { once: true });
        }, 2500);
    }
    // ── End Toast ─────────────────────────────────────────────────────────

</script>

<?php endif; ?>

</body>
</html>
