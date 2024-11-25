<?php
// Delete event handling
$pdo = new PDO("mysql:host=localhost;dbname=eventify", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $eventId = isset($_POST['eventId']) ? intval($_POST['eventId']) : null;

    if ($eventId) {
        try {
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = :eventId");
            $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Event deleted successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete the event."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid event ID."]);
    }
    exit();
}
?>
