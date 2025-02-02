<?php
require_once '../config.php';
require_once APP_PATH . '/auth.php';
require_once APP_PATH . '/db.php';

try {
    $stmt = $conn->prepare("SELECT id, name FROM events ORDER BY name ASC");
    $stmt->execute();
    $events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error fetching events: " . $e->getMessage());
}

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Fetch event details
    $stmt = $conn->prepare("SELECT name FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        die("Event not found.");
    }

    $event_name = preg_replace("/[^a-zA-Z0-9]/", "_", $event['name']); // Sanitize filename

    // Fetch attendees for this event
    $stmt = $conn->prepare("SELECT attendee_name, attendee_email, created_at FROM attendees WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename={$event_name}_attendees.csv");

    // Open output stream
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Attendee Name', 'Email', 'Created_at']); // CSV Headers

    // Fetch and write attendee data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['attendee_name'], $row['attendee_email'], $row['created_at']]);
    }

    fclose($output);
    exit;
}


require_once APP_PATH . '/includes/layout/admin.php';
?>
<div class="container-fluid mt-4">

    <div class="row">
        <div class="col-md-12">
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= $_GET['error'] ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= $_GET['success'] ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <form action=" <?= BASE_URL ?>/reports/download.php" method="get">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="event_id">Select Event</label>
                                <select name="event_id" id="event_id" class="form-control">
                                    <option value="">Select Event</option>
                                    <?php foreach ($events as $event): ?>
                                        <option value="<?= $event['id'] ?>" <?= isset($_GET['event_id']) && $_GET['event_id'] == $event['id'] ? 'selected' : '' ?> ><?= $event['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Download</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<?php require_once APP_PATH . '/includes/layout/footer.php'; ?>
