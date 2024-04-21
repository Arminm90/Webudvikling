<?php
session_start();

require_once __DIR__ . '/../_.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: /views/index.php'); 
    exit();
}

try {
    $db = _db(); 

    // Get user ID from session
    $userId = $_SESSION['user_id'];

    // Delete user from the database
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $q = $db->prepare($sql);
    $q->bindParam(':user_id', $userId);

    if ($q->execute()) {
        session_destroy(); 
        header('Location: /views/index.php'); 
        exit();
    } else {
        throw new Exception('Failed to delete user');
    }
} catch (Exception $e) {
    header('Location: /views/index.php'); 
    exit();
}
?>