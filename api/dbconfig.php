<?php

if (!class_exists('DBconfig')) {
class DBconfig {
    protected $db_host = "sql204.infinityfree.com";
    protected $db_user = "if0_40322633";
    protected $db_pass = "HDm584vG4kZDnt";
    protected $db_name = "if0_40322633_students";
    public $con;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        $envHost = getenv('LAB_DB_HOST'); if ($envHost !== false) $this->db_host = $envHost;
        $envUser = getenv('LAB_DB_USER'); if ($envUser !== false) $this->db_user = $envUser;
        $envPass = getenv('LAB_DB_PASS'); if ($envPass !== false) $this->db_pass = $envPass;
        $envName = getenv('LAB_DB_NAME'); if ($envName !== false) $this->db_name = $envName;
        $this->con = @new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    }

    public function check_con(){
        if($this->con->connect_error) return "connection error";
        else return $this->con;
    }

    private function getTeamsTable() {
        static $cachedTableName = null;
        if ($cachedTableName !== null) return $cachedTableName;

        $res = $this->con->query("SHOW TABLES LIKE 'teams'");
        if ($res && $res->num_rows > 0) {
            $cachedTableName = 'teams';
        } else {
            $res2 = $this->con->query("SHOW TABLES LIKE 'Teams'");
            if ($res2 && $res2->num_rows > 0) {
                $cachedTableName = 'Teams';
            } else {
                $cachedTableName = 'teams'; 
            }
        }
        return $cachedTableName;
    }
    public function getIdbyName($name){
        $sql = "SELECT id FROM users WHERE name = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['id'] : null;
    }
    public function getNamebyId($id){
        $sql = "SELECT name FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['name'] : null;
    }
    public function getEagleCoinsbyId($id){
        $sql = "SELECT eagle_coins FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['eagle_coins'] : null;
    }
    public function getGenderbyId($id){
        $sql = "SELECT gender FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['gender'] : null;
    }
    public function getPhonebyId($id){
        $sql = "SELECT phone FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['phone'] : null;
    }
    public function getEmailbyId($id){
        $sql = "SELECT email FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['email'] : null;
    }
    public function getIPAddressbyId($id){
        $sql = "SELECT IPADDR FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['IPADDR'] : null;
    }
    public function getAssignmentsbyId($id){
        $sql = "SELECT assignments FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['assignments'] : null;
    }
    public function getCoursebyId($id){
        $sql = "SELECT course FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['course'] : null;
    }
    public function getAllUsersData($fields = [], $orderBy = null, $orderDir = 'DESC', $limit = null){
        if (empty($fields)) {
            $fields = ['id', 'name', 'gender', 'phone', 'email', 'IPADDR', 'eagle_coins', 'assignments', 'course'];
        }
        
        $allowedFields = [
            'id', 'name', 'gender', 'phone', 'email',
            'IPADDR', 'eagle_coins', 'assignments', 'course', 'Avatars', 'active_avatar'
        ];
        
        $validFields = array_intersect($fields, $allowedFields);
        
        if (empty($validFields)) {
            return null;
        }
        
        $columns = implode(", ", $validFields);
        $sql = "SELECT $columns FROM users";
        
        if ($orderBy && in_array($orderBy, $allowedFields)) {
            $orderDir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY $orderBy $orderDir";
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserRank($userId) {
        $sql = "SELECT (SELECT COUNT(*) FROM users WHERE eagle_coins > u.eagle_coins) + 1 AS rank FROM users u WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? (int)$res['rank'] : 0;
    }

    public function getMaxEagleCoins() {
        $sql = "SELECT MAX(eagle_coins) as max_coins FROM users";
        $res = $this->con->query($sql);
        $data = $res->fetch_assoc();
        return $data ? (int)$data['max_coins'] : 0;
    }

    public function getTotalUsersCount() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $res = $this->con->query($sql);
        $data = $res->fetch_assoc();
        return $data ? (int)$data['total'] : 0;
    }
    public function getFieldbyId($id, $field){
        $allowedFields = [
            'id', 'name', 'gender', 'phone', 'email',
            'IPADDR', 'eagle_coins', 'assignments', 'course', 'access', 'active_avatar'
        ];
        
        if (!in_array($field, $allowedFields)) {
            return null;
        }
        
        $sql = "SELECT $field FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result[$field] : null;
    }
    public function getUserInfo($userId, $requestedFields = []) {
        if (empty($requestedFields)) {
            return null;
        }

        $allowedFields = [
            'id', 'name', 'gender', 'phone', 'email',
            'IPADDR', 'eagle_coins', 'assignments', 'course','assigns_complete','device', 'access'
        ];

        $validFields = array_intersect($requestedFields, $allowedFields);

        if (empty($validFields)) {
            return null;
        }

        $columns = implode(", ", $validFields);

        if ($userId === 'all') {
            $sql = "SELECT $columns FROM users";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        $sql = "SELECT $columns FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
    public function updateUserEagleCoins($userId, $newCoins) {
        $sql = "UPDATE users SET eagle_coins = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log("mysqli prepare failed for updateUserEagleCoins, user: $userId");
            return false;
        }

        $coins = intval($newCoins);
        $stmt->bind_param("is", $coins, $userId);

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return true;
        } else {
            error_log("Failed to execute updateUserEagleCoins for user ID: $userId");
            return false;
        }
    }
    public function addEagleCoins($userId, $amountToAdd) {

        $currentCoins = $this->getEagleCoinsbyId($userId);
        if ($currentCoins === null) {
            return false;
        }
        

        $newCoins = $currentCoins + $amountToAdd;
        

        return $this->updateUserEagleCoins($userId, $newCoins);
    }
    public function incrementEagleCoins($userId) {
        return $this->addEagleCoins($userId, 0.50);
    }

    public function upload_data($column_name, $value, $id) {
        if (empty($column_name) || $value === null || empty($id)) {
            return false;
        }
        
        $allowedFields = [
            'name', 'gender', 'phone', 'email', 
            'IPADDR', 'eagle_coins', 'assignments', 'course', 'assigns_complete','device'
        ];
        
        if (!in_array($column_name, $allowedFields)) {
            return false;
        }
        
        $sql = "UPDATE users SET $column_name = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $type = '';
        if (is_int($value)) {
            $type = 'i';
        } elseif (is_float($value)) {
            $type = 'd';
        } else {
            $type = 's';
        }
        
        $stmt->bind_param($type . 's', $value, $id);
        
        return $stmt->execute();
    }

    public function upload_waiting_assign($userId, $assignmentTitle, $link, $coin) {
        if (empty($userId) || empty($assignmentTitle)) {
            return false;
        }
        
        $existing = $this->get_waiting_assign($userId);
        

        $newAssignment = [
            'title' => $assignmentTitle,
            'link' => $link,
            'coin' => $coin
        ];
        
        $existing[] = $newAssignment;
        
        $jsonData = json_encode($existing);
        
        $sql = "UPDATE users SET waiting_assigns = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("ss", $jsonData, $userId);
        return $stmt->execute();
    }
    public function get_waiting_assign($userId) {
    $sql = "SELECT waiting_assigns FROM users WHERE id = ?";
    $stmt = $this->con->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $data = $row['waiting_assigns'];
        if (!empty($data)) {
            $decoded = json_decode($data, true);
            
            $assignments = [];
            

            if (is_array($decoded)) {
                foreach ($decoded as $item) {

                    if (is_string($item)) {
                        $parts = explode(':', $item);
                        if (count($parts) >= 3) {
                            $assignments[] = [
                                'title' => $parts[0], 
                                'link' => $parts[1],
                                'coin' => $parts[2]
                            ];
                        } else if (count($parts) >= 2) {
                            $assignments[] = [
                                'title' => $parts[0], 
                                'link' => $parts[1],
                                'coin' => 0
                            ];
                        } else {
                            $assignments[] = [
                                'title' => $item,
                                'link' => '',
                                'coin' => 0
                            ];
                        }
                    } 

                    else if (is_array($item) && isset($item['title'])) {
                        $assignments[] = [
                            'title' => $item['title'] ?? '',
                            'link' => $item['link'] ?? '',
                            'coin' => $item['coin'] ?? 0
                        ];
                    }
                }
            }
            return $assignments;
        }
    }
    
    return [];
}

    public function upload_tasks($userId, $taskNameToRemove, $coin) {
    if(empty($userId) || empty($taskNameToRemove)) return false;
    
    $sql = "SELECT tasks, completed FROM tasksdb WHERE id = ?";
    $stmt = $this->con->prepare($sql);
    if(!$stmt) {
        error_log("upload_tasks prepare failed for user: $userId");
        return false;
    }
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    $stmt->close();
    
    $completedArray = [];
    if($data && isset($data['completed']) && !empty($data['completed'])) {
        $decoded = json_decode($data['completed'], true);
        if(is_array($decoded)) {
            $completedArray = $decoded;
        }
    }
    
    
    $alreadyCompleted = false;
    foreach($completedArray as $comp) {
        if(is_string($comp) && $comp === $taskNameToRemove) {
            $alreadyCompleted = true;
            break;
        } elseif(is_array($comp) && (($comp['title'] ?? '') === $taskNameToRemove || ($comp['name'] ?? '') === $taskNameToRemove)) {
            $alreadyCompleted = true;
            break;
        }
    }
    if($alreadyCompleted) {
        return true;
    }
    
    $completedArray[] = $taskNameToRemove;
    $mod_completed = json_encode($completedArray);
    
    
    $newTasksArray = [];
    if($data && isset($data['tasks']) && !empty($data['tasks'])) {
        $tasksArray = json_decode($data['tasks'], true);
        if(is_array($tasksArray)) {
            foreach($tasksArray as $task) {
                if(is_string($task) && $task === $taskNameToRemove) continue;
                if(is_array($task) && (($task['title'] ?? '') === $taskNameToRemove || ($task['name'] ?? '') === $taskNameToRemove)) continue;
                $newTasksArray[] = $task;
            }
        }
    }
    $mod_tasks = json_encode($newTasksArray);
    
    $this->con->begin_transaction();
    
    try {
        if($data) {
            $updateTasksSql = "UPDATE tasksdb SET tasks = ?, completed = ? WHERE id = ?";
            $updateTasksStmt = $this->con->prepare($updateTasksSql);
            if(!$updateTasksStmt) throw new Exception("Prepare failed for update tasksdb");
            $updateTasksStmt->bind_param("sss", $mod_tasks, $mod_completed, $userId);
            $tasksResult = $updateTasksStmt->execute();
            $updateTasksStmt->close();
            if(!$tasksResult) throw new Exception("Failed to execute update tasksdb");
        } else {
            $insertTasksSql = "INSERT INTO tasksdb (id, tasks, completed, description) VALUES (?, ?, ?, '')";
            $insertTasksStmt = $this->con->prepare($insertTasksSql);
            if(!$insertTasksStmt) throw new Exception("Prepare failed for insert tasksdb");
            $insertTasksStmt->bind_param("sss", $userId, $mod_tasks, $mod_completed);
            $tasksResult = $insertTasksStmt->execute();
            $insertTasksStmt->close();
            if(!$tasksResult) throw new Exception("Failed to execute insert tasksdb");
        }
        
        $getCoinsSql = "SELECT eagle_coins FROM users WHERE id = ?";
        $getCoinsStmt = $this->con->prepare($getCoinsSql);
        if(!$getCoinsStmt) throw new Exception("Prepare failed for get eagle_coins");
        $getCoinsStmt->bind_param("s", $userId);
        $getCoinsStmt->execute();
        $getCoinsStmt->bind_result($currentCoins);
        $getCoinsStmt->fetch();
        $getCoinsStmt->close();
        
        $newCoins = intval($currentCoins) + intval($coin);
        
        $updateCoinsSql = "UPDATE users SET eagle_coins = ? WHERE id = ?";
        $updateCoinsStmt = $this->con->prepare($updateCoinsSql);
        if(!$updateCoinsStmt) throw new Exception("Prepare failed for update users eagle_coins");
        $updateCoinsStmt->bind_param("is", $newCoins, $userId);
        $coinsResult = $updateCoinsStmt->execute();
        $updateCoinsStmt->close();
        
        if(!$coinsResult) throw new Exception("Failed to update users eagle_coins");
        
        $this->con->commit();
        return true;
        
    } catch(Exception $e) {
        $this->con->rollback();
        error_log("upload_tasks credit error for user $userId: " . $e->getMessage());
        return false;
    }
}

    public function ensurePaymentVerificationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `payment_verifications` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `screenshot_path` VARCHAR(255) NOT NULL,
            `payment_method` VARCHAR(100) NOT NULL,
            `status` ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
            `decline_reason` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `reviewed_at` DATETIME NULL,
            `reviewed_by_admin_id` VARCHAR(100) NULL,
            INDEX (`user_id`),
            INDEX (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        $this->con->query($sql);
        @$this->con->query("ALTER TABLE `payment_verifications` MODIFY `user_id` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }

    public function getPaymentVerificationByUser($userId) {
        $this->ensurePaymentVerificationsTable();
        $sql = "SELECT * FROM payment_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function submitPaymentVerification($userId, $paymentMethod, $screenshotPath) {
        $this->ensurePaymentVerificationsTable();
        $sql = "INSERT INTO payment_verifications (user_id, payment_method, screenshot_path, status, created_at) VALUES (?, ?, ?, 'pending', NOW())";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log("submitPaymentVerification prepare failed: " . $this->con->error);
            return false;
        }
        $stmt->bind_param("sss", $userId, $paymentMethod, $screenshotPath);
        $result = $stmt->execute();
        if (!$result) {
            error_log("submitPaymentVerification execute failed for user_id=" . var_export($userId, true) . " method=" . var_export($paymentMethod, true) . " path=" . var_export($screenshotPath, true) . " error=" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function getAllPaymentVerifications() {
        $this->ensurePaymentVerificationsTable();
        $sql = "SELECT pv.*, u.name as user_name, u.email as user_email, u.phone as user_phone 
                FROM payment_verifications pv 
                LEFT JOIN users u ON pv.user_id COLLATE utf8mb4_general_ci = u.id COLLATE utf8mb4_general_ci 
                ORDER BY pv.id DESC";
        $res = $this->con->query($sql);
        $list = [];
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }

    public function acceptPaymentVerification($verificationId, $adminId) {
        $this->ensurePaymentVerificationsTable();
        $sql = "SELECT user_id FROM payment_verifications WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $verificationId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        if (!$row) return false;

        $userId = $row['user_id'];
        $this->con->begin_transaction();
        try {
            $upSql = "UPDATE payment_verifications SET status = 'accepted', reviewed_at = NOW(), reviewed_by_admin_id = ?, decline_reason = NULL WHERE id = ?";
            $upStmt = $this->con->prepare($upSql);
            if (!$upStmt) throw new Exception("Prepare failed for update verification");
            $upStmt->bind_param("si", $adminId, $verificationId);
            $upStmt->execute();
            $upStmt->close();

            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollback();
            error_log("acceptPaymentVerification failed: " . $e->getMessage());
            return false;
        }
    }

    public function declinePaymentVerification($verificationId, $adminId, $reason = '') {
        $this->ensurePaymentVerificationsTable();
        $sql = "UPDATE payment_verifications SET status = 'declined', decline_reason = ?, reviewed_at = NOW(), reviewed_by_admin_id = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log("declinePaymentVerification prepare failed: " . $this->con->error);
            return false;
        }
        $stmt->bind_param("ssi", $reason, $adminId, $verificationId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function hasOwasp2026Access($userId) {
        if (empty($userId)) return false;
        $this->ensurePaymentVerificationsTable();
        $this->resetExpiredOwasp2026Access();
        $sql = "SELECT id FROM payment_verifications WHERE user_id = ? AND status = 'accepted' AND reviewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $hasAccess = ($res && $res->num_rows > 0);
        $stmt->close();
        return $hasAccess;
    }

    public function resetExpiredOwasp2026Access() {
        $this->ensurePaymentVerificationsTable();
        $sql = "UPDATE payment_verifications SET status = 'declined', decline_reason = 'Access expired after 30 days. Renew your subscription to continue.' WHERE status = 'accepted' AND reviewed_at IS NOT NULL AND reviewed_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        @$this->con->query($sql);
    }

    public function getOwasp2026AccessInfo($userId) {
        if (empty($userId)) return null;
        $this->ensurePaymentVerificationsTable();
        $this->resetExpiredOwasp2026Access();
        $sql = "SELECT * FROM payment_verifications WHERE user_id = ? AND status = 'accepted' ORDER BY reviewed_at DESC LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        if ($data && !empty($data['reviewed_at'])) {
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime($data['reviewed_at'] . ' +30 days'));
        }
        return $data;
    }

    public function get_tasks($userId, $all) {
    if(empty($userId)) return $all === 'all' ? [] : ($all === 'total' ? 0 : []);
    
    if($all === 'all') {
        $sql = "SELECT id, tasks, completed, description FROM tasksdb WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if(!$stmt) return [];
        
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        
        return $data ?: [];
    }
    
    $column = $all;
    if(!in_array($all, ['tasks', 'completed', 'total', 'description', ''])) {
        $column = 'tasks';
    }
    
    $sql = "SELECT $column FROM tasksdb WHERE id = ?";
    $stmt = $this->con->prepare($sql);
    if(!$stmt) return $all === 'total' ? 0 : [];
    
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    $stmt->close();
    
    if(!$data || !isset($data[$column])) {
        return $all === 'total' ? 0 : [];
    }
    
    if($all === 'total') {
        return (int)$data[$column];
    }
    
    $result = json_decode($data[$column], true);
    return is_array($result) ? $result : [];
}

    public function getUserAvatars($userId) {
        $sql = "SELECT Avatars FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$result || empty($result['Avatars'])) {
            return [];
        }
        $decoded = json_decode($result['Avatars'], true);
        return is_array($decoded) ? $decoded : [];
    }

    public function setUserAvatars($userId, array $avatars) {
        $json = json_encode(array_values($avatars));
        $sql = "UPDATE users SET Avatars = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ss", $json, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getActiveAvatar($userId) {
        $sql = "SELECT active_avatar FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ? $result['active_avatar'] : null;
    }

    public function setActiveAvatar($userId, $avatarName) {
        $sql = "UPDATE users SET active_avatar = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ss", $avatarName, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    function avatar_coin($filename) {

    $filename = trim(strtolower(basename($filename)));

    $coins = [
        'byte hawk.png'        => 100,
        'aegis hawk.png'       => 250,
        'sentinel hawk.png'    => 350,
        'drago wing.png'       => 450,
        'eaglonito.png'        => 550,
        'red eaglone.png'      => 650,
        'shadow eaglone.png'   => 800,
        'cyber eaglone.png'    => 950,
        'iron eaglone.png'     => 1100,
        'eaglone x.png'        => 1250,
        'vortex x.png'         => 1350,
        'securewing x.png'     => 1450,
        'code eaglone.png'     => 1500
    ];

    return $coins[$filename] ?? 100; 
}

    public function getTeams($search = '') {
        $teams = [];
        $tableName = $this->getTeamsTable();

        $sql = "SELECT id, team_name, created_at, members_list, leader_pos, co_leader_pos, team_profile FROM $tableName";
        
        if (!empty($search)) {
            $sql .= " WHERE team_name LIKE ?";
            $stmt = $this->con->prepare($sql);
            if (!$stmt) return false;
            $searchParam = "%$search%";
            $stmt->bind_param("s", $searchParam);
            if (!$stmt->execute()) return false;
            $result = $stmt->get_result();
        } else {
            $result = $this->con->query($sql);
        }
        
        if ($result === false) {
            return false;
        }

        while ($row = $result->fetch_assoc()) {
            $row['has_profile'] = (!empty($row['team_profile'])) ? 1 : 0;
            unset($row['team_profile']); 
            foreach($row as $key => $val) {
                if (is_string($val)) $row[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            $teams[] = $row;
        }
        return $teams;
    }

    public function getTeamById($id) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT id, team_name, created_at, members_list, leader_pos, co_leader_pos, 
                (CASE WHEN team_profile IS NULL THEN 0 ELSE 1 END) as has_profile 
                FROM $tableName WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTeamProfile($id) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT team_profile FROM $tableName WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['team_profile'] : null;
    }

    public function createTeam($name, $members, $leader, $coLeader, $profileImage = null) {
        $tableName = $this->getTeamsTable();
        $sql = "INSERT INTO $tableName (team_name, members_list, leader_pos, co_leader_pos, team_profile) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return false;
        }
        
        $membersJson = json_encode($members);
        $null = NULL;
        $stmt->bind_param("ssssb", $name, $membersJson, $leader, $coLeader, $null);
        
        if ($profileImage) {
            $stmt->send_long_data(4, $profileImage);
        }
        
        $executed = $stmt->execute();
        if (!$executed) {
        }
        return $executed;
    }

    public function getAllUsers() {
        $sql = "SELECT id, name FROM users";
        $result = $this->con->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllUsersWithStats() {
        $sql = "SELECT id, name, eagle_coins, active_avatar FROM users ORDER BY eagle_coins DESC";
        $result = $this->con->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateTeamName($id, $newName) {
        $tableName = $this->getTeamsTable();
        $sql = "UPDATE $tableName SET team_name = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("si", $newName, $id);
        return $stmt->execute();
    }

    public function removeTeamMember($id, $memberName) {
        $team = $this->getTeamById($id);
        if (!$team) return false;

        $members = json_decode($team['members_list'], true);
        if (!is_array($members)) return false;

        $newMembers = array_values(array_filter($members, function($m) use ($memberName) {
            return $m !== $memberName;
        }));

        $membersJson = json_encode($newMembers);
        $tableName = $this->getTeamsTable();
        $sql = "UPDATE $tableName SET members_list = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("si", $membersJson, $id);
        return $stmt->execute();
    }

    public function deleteTeam($id) {
        $tableName = $this->getTeamsTable();
        $sql = "DELETE FROM $tableName WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function checkTeamNameExists($name) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT id FROM $tableName WHERE team_name = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function updateTeamProfile($id, $imageData) {
        $tableName = $this->getTeamsTable();
        $sql = "UPDATE $tableName SET team_profile = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $null = NULL;
        $stmt->bind_param("bi", $null, $id);
        $stmt->send_long_data(0, $imageData);
        return $stmt->execute();
    }

    public function addMemberToTeam($id, $memberName) {
        $team = $this->getTeamById($id);
        if (!$team) return false;

        $members = json_decode($team['members_list'], true);
        if (!is_array($members)) return false;
        if (count($members) >= 3) return false;

        if (in_array($memberName, $members)) return true; 

        $members[] = $memberName;
        $membersJson = json_encode($members);
        
        $tableName = $this->getTeamsTable();
        $sql = "UPDATE $tableName SET members_list = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("si", $membersJson, $id);
        return $stmt->execute();
    }
    public function createBattle($type, $challengerId, $challengedId, $fee = 50.00) {
        $sql = "INSERT INTO coding_battles (type, challenger_id, challenged_id, entry_fee) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("siid", $type, $challengerId, $challengedId, $fee);
        return $stmt->execute();
    }

    public function getActiveBattles() {
        $sql = "SELECT * FROM coding_battles WHERE status != 'completed' AND status != 'cancelled' ORDER BY created_at DESC";
        $result = $this->con->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBattlesForUser($userId) {
        $sql = "SELECT * FROM coding_battles WHERE (type = '1v1' AND (challenger_id = ? OR challenged_id = ?)) OR (status = 'pending') ORDER BY created_at DESC";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateBattleStatus($battleId, $status, $winnerId = null) {
        if ($winnerId !== null) {
            $sql = "UPDATE coding_battles SET status = ?, winner_id = ?, completed_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("sii", $status, $winnerId, $battleId);
        } else {
            $sql = "UPDATE coding_battles SET status = ? WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("si", $status, $battleId);
        }
        return $stmt->execute();
    }

    public function ensureJoinRequestsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS join_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            team_id INT,
            user_name VARCHAR(255),
            user_id VARCHAR(255),
            status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
            notified_user TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        return $this->con->query($sql);
    }

    public function createJoinRequest($teamId, $userName, $userId) {
        $sql = "SELECT id FROM join_requests WHERE team_id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("is", $teamId, $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) return true;

        $sql = "INSERT INTO join_requests (team_id, user_name, user_id) VALUES (?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iss", $teamId, $userName, $userId);
        return $stmt->execute();
    }

    public function getJoinRequestsForLeader($leaderName) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT jr.*, t.team_name 
                FROM join_requests jr 
                JOIN $tableName t ON jr.team_id = t.id 
                WHERE t.leader_pos = ? AND jr.status = 'pending'";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $leaderName);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getProcessedRequestsForUser($userId) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT jr.*, t.team_name 
                FROM join_requests jr 
                JOIN $tableName t ON jr.team_id = t.id 
                WHERE jr.user_id = ? AND jr.status IN ('accepted', 'rejected') AND jr.notified_user = 0";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function processJoinRequest($requestId, $status) {
        if ($status === 'accepted') {
            $sql = "SELECT team_id, user_name FROM join_requests WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res) {
                $this->addMemberToTeam($res['team_id'], $res['user_name']);
            }
        }
        $sql = "UPDATE join_requests SET status = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("si", $status, $requestId);
        return $stmt->execute();
    }

    public function markRequestAsNotified($requestId) {
        $sql = "UPDATE join_requests SET notified_user = 1 WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $requestId);
        return $stmt->execute();
    }
    private function getPresenceWindowSeconds() {
        return 75;
    }

    public function updateLastActive($userId) {
        $sql = "UPDATE users SET last_active = NOW() WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("s", $userId);
        return $stmt->execute();
    }

    public function getOnlineUserIds() {
        $windowSeconds = (int)$this->getPresenceWindowSeconds();
        $sql = "SELECT id FROM users WHERE last_active >= DATE_SUB(NOW(), INTERVAL {$windowSeconds} SECOND)";
        $result = $this->con->query($sql);
        if (!$result) return [];
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    public function getOnlineUsers() {
        $windowSeconds = (int)$this->getPresenceWindowSeconds();
        $sql = "SELECT id, name FROM users WHERE last_active >= DATE_SUB(NOW(), INTERVAL {$windowSeconds} SECOND)";
        $result = $this->con->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getPendingAdminBattles() {
        $sql = "SELECT * FROM coding_battles WHERE admin_status = 'pending_admin' ORDER BY created_at DESC";
        $result = $this->con->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function adminApproveBattle($battleId) {
        $sql = "UPDATE coding_battles SET admin_status = 'approved', status = 'pending' WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $battleId);
        return $stmt->execute();
    }

    public function adminRejectBattle($battleId) {
        $sql = "UPDATE coding_battles SET admin_status = 'rejected', status = 'cancelled' WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $battleId);
        return $stmt->execute();
    }

    public function getBattleById($battleId) {
        $sql = "SELECT * FROM coding_battles WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $battleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function createBattleNotification($userId, $battleId, $message, $type) {
        $sql = "INSERT INTO battle_notifications (user_id, battle_id, message, type) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("siss", $userId, $battleId, $message, $type);
        return $stmt->execute();
    }

    public function getBattleNotifications($userId) {
        $sql = "SELECT * FROM battle_notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function markBattleNotificationRead($notifId) {
        $sql = "UPDATE battle_notifications SET is_read = 1 WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $notifId);
        return $stmt->execute();
    }

    public function getLeaderTeams($leaderName) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT id, team_name, members_list, leader_pos FROM $tableName WHERE leader_pos = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("s", $leaderName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getTeamNameById($teamId) {
        $tableName = $this->getTeamsTable();
        $sql = "SELECT team_name FROM $tableName WHERE id = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return 'Unknown Team';
        $stmt->bind_param("i", $teamId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['team_name'] : 'Unknown Team';
    }

    public function setOffline($userId) {
        $sql = "UPDATE users SET last_active = '1000-01-01 00:00:00' WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("s", $userId);
        return $stmt->execute();
    }
}
}
