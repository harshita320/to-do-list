<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task']; // Get the user input

    // Use a prepared statement to safely insert the data
    $sql = "INSERT INTO tasks (task) VALUES (:task)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':task', $task, PDO::PARAM_STR);
    $stmt->execute();
}

header('Location: index.php');
exit;
?>
