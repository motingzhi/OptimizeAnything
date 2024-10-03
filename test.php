<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $savedSolutions = json_decode($_POST['savedSolutions'], true);
    $savedObjectives = json_decode($_POST['savedObjectives'], true);
    $timestamp = $_POST['timestamp'];

    // 创建表格
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `$userID` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        saved_solution TEXT,
        saved_objective TEXT,
        saved_timestamp TIMESTAMP
    )";

    if ($conn->query($createTableQuery) === FALSE) {
        echo "Error creating table: " . $conn->error;
        exit();
    }

    // 插入数据
    $stmt = $conn->prepare("INSERT INTO `$userID` (saved_solution, saved_objective, saved_timestamp) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $savedSolutionJson = json_encode($savedSolutions);
    $savedObjectiveJson = json_encode($savedObjectives);

    $stmt->bind_param("sss", $savedSolutionJson, $savedObjectiveJson, $timestamp);
    if ($stmt->execute()) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>