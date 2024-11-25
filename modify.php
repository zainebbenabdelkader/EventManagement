<?php
header('Content-Type: application/json');

// Make sure you're connecting to your database
// Replace this with your actual database connection
$pdo = new PDO('mysql:host=localhost;dbname=eventify', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $eventId = $_POST['eventId'];
    $eventName = $_POST['eventName'];
    $eventType = $_POST['eventType'];
    $eventDate = $_POST['eventDate'];
    $price = $_POST['price'];

    // Validate the input data (basic example)
    if (empty($eventId) || empty($eventName) || empty($eventType) || empty($eventDate) || empty($price)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Prepare the SQL query to update the event
    $query = "UPDATE events SET event_name = ?, event_type = ?, event_date = ?, price = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        // Execute the query with the provided data
        $stmt->execute([$eventName, $eventType, $eventDate, $price, $eventId]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made to the event.']);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
