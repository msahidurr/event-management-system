<?php
session_start();
require_once 'config.php';
require_once APP_PATH . '/db.php';

try {
    $stmt = $conn->prepare("
    SELECT events.*, COUNT(attendees.id) AS attendee_count 
    FROM events 
    LEFT JOIN attendees ON events.id = attendees.event_id 
    WHERE events.date >= NOW() 
    GROUP BY events.id 
    ORDER BY events.date ASC
");

$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die("Error fetching events: " . $e->getMessage());
}

require_once APP_PATH . '/includes/layout/header.php';
?>

<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <nav class="navbar navbar-light bg-light justify-content-between">
                    <div></div>
                    <div>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a href="<?= BASE_URL ?>/dashboard.php" class="btn btn-primary">Dashboard</a>
                        <?php } else { ?>
                            <a href="<?= BASE_URL ?>/login.php" class="btn btn-primary">Login</a>
                            <a href="<?= BASE_URL ?>/register.php" class="btn btn-info">Register</a>
                        <?php } ?>
                    </div>
                </nav>
                <h3>Event list</h3>
                <div class="row">
                    <?php foreach ($events as $event) { ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?= $event['name'] ?></h3>
                                </div>
                                <div class="card-body">
                                    <p><?= $event['description'] ?></p>
                                    <p><strong>Date:</strong> <?= date('d F Y h:i a', strtotime($event['date'])) ?></p>
                                    <p><strong>Capacity:</strong> <?= $event['capacity'] ?></p>
                                    <p><strong>Attendees:</strong> <?= $event['attendee_count'] ?></p>
                                </div>
                                <div class="card-footer">
                                    <a href="<?= BASE_URL ?>/attend.php?id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">Attend</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require_once APP_PATH . '/includes/layout/footer.php'; ?>