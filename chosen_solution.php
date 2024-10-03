<?php
session_start();
require_once 'config.php';


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $prolificID = $_POST['Prolific'];

//     $solutionlist = ''; // 默认值
//     $savedsolutions = ''; // 默认值
//     $savedobjectives = ''; // 默认值
//     $parameterNames = ''; // 默认值
//     $parameterBounds = '';
//     $parameter_timestamp = '';
//     $objectiveNames = ''; // 默认值
//     $objectiveBounds = ''; // 默认值
//     $objective_timestamp = '';
//     $saved_timestamp = '';
//     $objectiveminmax = '';
//     $ismanual = '';
//     $taskdescription = '';




//    // 将数组转换为 JSON 格式
//    $solutionlist = json_encode($solutionList);
//    $savedsolutions = json_encode($savedSolutions);
//    $savedobjectives = json_encode($savedObjectives);
//    $parameterNames = json_encode($parameterNames);
//    $parameterBounds = json_encode($parameterBounds);
//    $objectiveNames = json_encode($objectiveNames);
//    $objectiveBounds = json_encode($objectiveBounds);
//    $parameter_timestamp = json_encode($parameter_timestamp);
//    $objective_timestamp = json_encode($objective_timestamp);
//    $saved_timestamp = json_encode($saved_timestamp);
//    $objectiveminmax = json_encode($objectiveminmax);
//    $ismanual = json_encode($ismanual);
//    $taskdescription = json_encode($taskdescription);


//     if (empty($prolificID)) {
//         die("Prolific ID is required");
//     }

//         // 构建列名和相应的值
//     $columns = [
//         'prolific_ID' => $prolificID,
//         'Solutionlist' => $solutionlist,
//         'Savedsolutions' => $savedsolutions,
//         'Savedobjectives' => $savedobjectives,
//         'parametername' => $parameterNames,
//         'parameterbounds' => $parameterBounds,
//         'parameter_timestamp' => $parameter_timestamp,
//         'objectivename' => $objectiveNames,
//         'objectivebounds' => $objectiveBounds,
//         'objective_timestamp' => $objective_timestamp,
//         'saved_timestamp' => $saved_timestamp,
//         'objectiveminmax' => $objectiveminmax,
//         'ismanual' => $ismanual,
//         'taskdescription' => $taskdescription,

//     ];

//     // 动态生成列名和占位符
//     $columnNames = implode(", ", array_keys($columns));
//     $placeholders = implode(", ", array_fill(0, count($columns), "?"));

//     $stmt = $conn->prepare("INSERT INTO data ($columnNames) VALUES ($placeholders)");
//     if ($stmt === false) {
//         die("Prepare failed: " . $conn->error);
//     }

//     // 动态生成参数类型和值
//     $types = str_repeat("s", count($columns)); // 假设所有参数都是字符串类型
//     $values = array_values($columns);

//     // 使用 splat 操作符将参数数组传递给 bind_param
//     $stmt->bind_param($types, ...$values);

//     if ($stmt->execute()) {
//         $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
//         echo "New record created successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }


//     // $stmt = $conn->prepare("INSERT INTO data (ID, Solutionlist, Savedsolutions, Savedobjectives, parametername, parameterbounds, parameter_timestamp, objectivename,objectivebounds, objective_timestamp ) VALUES (?, ?, ?, ?, ?, ?, ?)");
//     // if ($stmt === false) {
//     //     die("Prepare failed: " . $conn->error);
//     // }

//     // $stmt->bind_param("sssssss", $prolificID, $solutionlist, $savedsolutions, $savedobjectives, $parameterNames, $parameterBounds, $defineTimestamp);
//     // if ($stmt->execute()) {
//     //     $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
//     //     echo "New record created successfully";
//     // } else {
//     //     echo "Error: " . $stmt->error;
//     // }

//     $stmt->close();
//     $conn->close();
// }



// if (!isset($_SESSION['ProlificID'])) {
//     // 如果会话中没有 Prolific ID，则重定向到初始页面
//     header("Location: index.php");
//     exit();
// }

$userID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $chosen_solution = $_POST['chosen_solution']; // 获取用户输入的方案名称
    $chosen_solution = json_encode($chosen_solution);

    // 更新数据库中对应用户的 chosen_solution 列
    $stmt = $conn->prepare("UPDATE data SET chosen_solution = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $chosen_solution, $userID);
    if ($stmt->execute()) {
        echo "Solution saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
