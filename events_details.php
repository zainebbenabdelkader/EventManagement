
<?php
if (isset($_GET['id'])) {
    $eventId = (int) $_GET['id'];
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=eventify", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            echo json_encode($event); // Return the event data as JSON
        } else {
            echo json_encode(["error" => "Event not found"]);
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
