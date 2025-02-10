<?php
header("Content-Type: application/json");
require_once APP_PATH .'/config.php';
require_once APP_PATH . '/db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['id'])) {
    // Fetch a single event
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    
    echo json_encode($event ?: ["error" => "Event not found"]);
    
} elseif ($method === 'GET') {
    // Fetch all events
    $result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
    $events = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($events);
    
} elseif ($method === 'POST') {
    // Register an attendee
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("INSERT INTO attendees (attendee_name, attendee_email, event_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $data['name'], $data['email'], $data['event_id']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "Attendee registered"]);
    } else {
        echo json_encode(["error" => "Failed to register attendee"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>
