<?php
session_start();
require_once 'dbconfig.php';

header('Content-Type: application/json');

if(!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['id'];
$db = new DBconfig();

if(!$db->check_con()) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$sql = "SELECT tasks FROM tasksdb";
$res = $db->con->query($sql);
$all_tasks = [];
$unique_titles = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $tasksStr = $row['tasks'];
        if ($tasksStr) {
            $tasksArr = json_decode($tasksStr, true);
            if (is_array($tasksArr)) {
                foreach ($tasksArr as $task) {
                    $title = is_array($task) ? ($task['title'] ?? ($task['name'] ?? '')) : (is_string($task) ? $task : '');
                    if ($title && !isset($unique_titles[$title])) {
                        $unique_titles[$title] = true;
                        $all_tasks[] = $task;
                    }
                }
            }
        }
    }
}

$user_data = $db->get_tasks($userId, 'all');

$completed = '[]';
if ($user_data && isset($user_data['completed'])) {
    $completed = $user_data['completed'];
}

$description = ($user_data && isset($user_data['description'])) ? $user_data['description'] : "Interactive challenge.";

echo json_encode(['status' => [
    'tasks' => json_encode($all_tasks), 
    'completed' => $completed, 
    'description' => $description
]]);
?>
