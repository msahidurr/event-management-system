<?php
require_once '../config.php';
require_once APP_PATH . '/auth.php';
require_once APP_PATH . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $event_date = $_POST['event_date'];
  $capacity = (int)$_POST['capacity']; // Ensure integer type

  if (empty($name) || empty($event_date) || empty($capacity)) {
    $error = "All fields are required!";
  } else {
    try {
      $stmt = $conn->prepare("INSERT INTO events (name, description, date, capacity, created_by) VALUES (?, ?, ?, ?, ?)");

      $stmt->bind_param('sssii', $name, $description, $event_date, $capacity, $_SESSION['user_id']);

      if ($stmt->execute()) {
        $stmt->close();
        header('Location: ' . BASE_URL . '/events/list.php?success=Event created successfully!');
        exit();
      } else {
        $stmt->close();
        header('Location: ' . BASE_URL . '/events/list.php?error=Error creating event: ' . $stmt->error);
        exit();
      }

    } catch (Exception $e) {
      header('Location: ' . BASE_URL . '/events/list.php?error=Error creating event: ' . $e->getMessage());
      exit();
    }
  }
}

require_once APP_PATH . '/includes/layout/admin.php';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card card-info card-outline mb-4 mt-4">
        <form class="needs-validation" novalidate="" method="post">
          <div class="card-header">
            <h3 class="card-title">Create Event</h3>
          </div>

          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-12">
                <label for="name" class="form-label">Title</label>
                <input type="text" class="form-control" id="name" name="name" required="">
              </div>

              <div class="col-md-12">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="datetime-local" name="event_date" class="form-control" id="event_date" required="" />
              </div>

              <div class="col-md-12">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" name="capacity" class="form-control" id="capacity" required="" />
              </div>

              <div class="col-md-12">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description"></textarea>
              </div>

            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-info" type="submit">Create</button>
          </div>
        </form>
      </div>
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
<script>
    // Get the current date and time in the correct format for input type datetime-local
    const currentDateTime = new Date().toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:MM

    // Set the min attribute of the input to the current date and time
    document.getElementById('event_date').setAttribute('min', currentDateTime);
</script>
</body>
<?php require_once APP_PATH . '/includes/layout/footer.php'; ?>