<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once 'dbconfig.php';

$db = new DBconfig();
$check = $db->check_con();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['task-name'], $_POST['task-coin'], $_SESSION['id'])) {
        $task_name = $_POST['task-name'];
        $coin = (int)$_POST['task-coin'];
        $user_id = $_SESSION['id']; 
        
        $result = $db->upload_tasks($user_id, $task_name, $coin);
        
        if($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'failure']);
        }
    } else {
        $missing = [];
        if(!isset($_POST['task-name'])) $missing[] = 'task-name';
        if(!isset($_POST['task-coin'])) $missing[] = 'task-coin';
        if(!isset($_SESSION['id'])) $missing[] = 'session-id';
        
        echo json_encode([
            'status' => 'missing_data',
            'missing' => $missing
        ]);
    }
} else {
    echo json_encode(['status' => 'invalid_method']);
}
?>