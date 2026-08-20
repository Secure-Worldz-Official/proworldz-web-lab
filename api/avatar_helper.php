<?php
function getActiveAvatarImage($db, $userId) {
    $directory = 'eagleshop/';
    $allowed_extensions = ['png', 'jpg', 'jpeg', 'webp'];
    $avatarImageMap = [];

    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed_extensions)) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $avatarImageMap[$name] = $directory . $file;
            }
        }
    }

    $activeAvatarName = $db->getActiveAvatar($userId);
    
    if (!$activeAvatarName) {
        $purchasedAvatars = $db->getUserAvatars($userId) ?: [];
        $activeAvatarName = $purchasedAvatars[0] ?? '';
    }
    
    return isset($avatarImageMap[$activeAvatarName]) ? $avatarImageMap[$activeAvatarName] : 'image.webp';
}





?>
