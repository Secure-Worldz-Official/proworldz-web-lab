<?php
session_start();
require_once 'dbconfig.php';

header('Content-Type: application/json');

$db = new DBconfig();

if(!$db->check_con()) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$sql = "SELECT tasks, completed, description FROM tasksdb";
$res = $db->con->query($sql);
$all_tasks = [];
$unique_tasks = [];
$completed = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $tasksStr = $row['tasks'];
        
        if ($tasksStr) {
            $tasksArr = json_decode($tasksStr, true);
            if (is_array($tasksArr)) {
                foreach ($tasksArr as $task) {
                    $title = is_array($task) ? ($task['title'] ?? ($task['name'] ?? '')) : (is_string($task) ? $task : '');
                    if ($title && !isset($unique_tasks[$title])) {
                        $unique_tasks[$title] = true;
                        $all_tasks[] = $task;
                    }
                }
            }
        }
    }
}

echo json_encode([
    'status' => [
        'tasks' => $all_tasks,
        'completed' => [], 
        'description' => 'List of all tasks from all users (Showcase).'
    ]
]);
?>
