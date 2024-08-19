<?php
require_once 'config.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $userID = $_POST['userID'];
//     $savedSolutions = json_decode($_POST['savedSolutions'], true);
//     $savedObjectives = json_decode($_POST['savedObjectives'], true);
//     $timestamp = $_POST['timestamp'];

//     // 创建表格
//     $createTableQuery = "CREATE TABLE IF NOT EXISTS `$userID` (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         saved_solution TEXT,
//         saved_objective TEXT,
//         saved_timestamp TIMESTAMP
//     )";

//     if ($conn->query($createTableQuery) === FALSE) {
//         echo "Error creating table: " . $conn->error;
//         exit();
//     }

//     // 插入数据
//     $stmt = $conn->prepare("INSERT INTO `$userID` (saved_solution, saved_objective, saved_timestamp) VALUES (?, ?, ?)");
//     if ($stmt === false) {
//         die("Prepare failed: " . $conn->error);
//     }

//     $savedSolutionJson = json_encode($savedSolutions);
//     $savedObjectiveJson = json_encode($savedObjectives);

//     $stmt->bind_param("sss", $savedSolutionJson, $savedObjectiveJson, $timestamp);
//     if ($stmt->execute()) {
//         echo "Record inserted successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     $stmt->close();
//     $conn->close();
// }


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $savedSolutions = json_decode($_POST['savedSolutions'], true);
    $savedObjectives = json_decode($_POST['savedObjectives'], true);
    $timestamp = $_POST['timestamp'];
    $isRefine = isset($_POST['isRefine']) ? $_POST['isRefine'] : true; // 获取是否为refine操作

    // 创建表格
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `$userID` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        saved_solution TEXT,
        saved_objective TEXT,
        saved_timestamp TIMESTAMP,
        saved_solution_refine TEXT,
        saved_objective_refine TEXT,
        saved_timestamp_refine TIMESTAMP
    )";

    if ($conn->query($createTableQuery) === FALSE) {
        echo "Error creating table: " . $conn->error;
        exit();
    }

    // 判断是插入原始列还是refine列
    if ($isRefine) {
        $stmt = $conn->prepare("INSERT INTO `$userID` (saved_solution_refine, saved_objective_refine, saved_timestamp_refine) VALUES (?, ?, ?)");
    } else {
        $stmt = $conn->prepare("INSERT INTO `$userID` (saved_solution, saved_objective, saved_timestamp) VALUES (?, ?, ?)");
    }

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
