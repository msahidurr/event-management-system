<?php
require_once '../config.php';
require_once APP_PATH . '/auth.php';
require_once APP_PATH . '/db.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");

    if (!$stmt) {
        header("Location:" . BASE_URL . "/events/list.php?error=Error deleting event.");
        exit();
    }

    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        header("Location:" . BASE_URL . "/events/list.php?success=Event deleted successfully.");
        exit();
    } else {
        header("Location:" . BASE_URL . "/events/list.php?error=Error deleting event.");
    }
} else {
    header("Location:" . BASE_URL . "/events/list.php?error=Event ID is required.");
    exit();
}
