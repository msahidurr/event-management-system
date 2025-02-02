<?php
session_start();

require_once 'config.php';
require_once APP_PATH . '/db.php';

$event_id = (int)$_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND date >= NOW()");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendee_name = $_POST['name'];
    $attendee_email = $_POST['email'];

    if ($event) {
        // Get the current number of attendees for the event
        $stmt = $conn->prepare("SELECT COUNT(*) AS total_attendees FROM attendees WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $total_attendees = $result['total_attendees'];

        if ($total_attendees < $event['capacity']) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM attendees WHERE attendee_email = ? AND event_id = ?");
            $stmt->bind_param("si", $attendee_email, $event_id);
            $stmt->execute();
            $stmt->bind_result($existing_count);
            $stmt->fetch();
            $stmt->close();

            if ($existing_count > 0) {
                $error = "You have already registered for this event.";
            } else {
                $stmt = $conn->prepare("INSERT INTO attendees (attendee_name, attendee_email, event_id) VALUES (?, ?, ?)");

                $stmt->bind_param("ssi", $attendee_name, $attendee_email, $event_id);

                if ($stmt->execute()) {
                    $success = "You have successfully registered for the event!";
                } else {
                    $error = "There was an error registering for the event. Please try again.";
                }
            }
        } else {
            $error = "Sorry, the event is full. You cannot register for this event.";
        }
    } else {
        $error = "Invalid event or the event has already passed.";
    }
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

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <form action="<?= BASE_URL ?>/attend.php?id=<?= $event['id'] ?>" method="post" class="needs-validation" novalidate="">
                        <div class="card-header">
                            <h3 class="card-title">Attend (<?= $event['name'] ?>)</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="" value="<?= $attendee_name ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required="" value="<?= $attendee_email ?? '' ?>">
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary btn-sm">Back</a>
                            <button type="submit" class="btn btn-success btn-sm">Attend</>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach((form) => {
                form.addEventListener(
                    'submit',
                    (event) => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated');
                    },
                    false,
                );
            });
        })();
    </script>
</body>

<?php require_once APP_PATH . '/includes/layout/footer.php'; ?>