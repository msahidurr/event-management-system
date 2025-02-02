<?php
require_once '../config.php';
require_once APP_PATH . '/auth.php';
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
} catch (PDOException $e) {
    die("Error fetching events: " . $e->getMessage());
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
            <a href="<?= BASE_URL ?>/events/create.php" class="btn btn-primary mb-4">Create new event</a>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Event Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered event-list-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Capacity</th>
                                <th>Attendees</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($events as $event) { ?>
                                <tr class="align-middle">
                                    <td><?= $i++ ?></td>
                                    <td><?= $event['name'] ?></td>
                                    <td><?= $event['description'] ?></td>
                                    <td><?= date('d F Y h:i a', strtotime($event['date'])) ?></td>
                                    <td><?= $event['capacity'] ?></td>
                                    <td><?= $event['attendee_count'] ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/events/edit.php?id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="<?= BASE_URL ?>/events/delete.php?id=<?= $event['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<?php require_once APP_PATH . '/includes/layout/footer.php'; ?>
<script>
    $(document).ready(function() {
        $('.event-list-table').DataTable();
    });
</script>