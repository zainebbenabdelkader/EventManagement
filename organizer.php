<?php
header('Content-Type: application/json');

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=eventify", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Retrieve and validate POST data
$eventName = isset($_POST['eventName']) ? trim($_POST['eventName']) : null;
$eventDate = isset($_POST['eventDate']) ? trim($_POST['eventDate']) : null;
$eventType = isset($_POST['eventType']) ? trim($_POST['eventType']) : null;
$price = isset($_POST['price']) ? trim($_POST['price']) : null;

if (!$eventName || !$eventDate || !$eventType || !$price) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit();
}

// Insert data into database
try {
    $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_type, price) VALUES (:eventName, :eventDate, :eventType, :price)");
    $stmt->bindParam(':eventName', $eventName);
    $stmt->bindParam(':eventDate', $eventDate);
    $stmt->bindParam(':eventType', $eventType);
    $stmt->bindParam(':price', $price);

    if ($stmt->execute()) {
        // Retrieve the last inserted event
        $lastId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->bindParam(':id', $lastId);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'event' => $event]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to insert event.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
