<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $prolificID =$_POST['Prolific'];


   

    // // 检查 Prolific ID 是否存在于数据表中，并查询 pass 列的值
    // $check_query = $conn->prepare("SELECT COUNT(*), pass FROM data WHERE prolific_ID = ?");
    // if ($check_query === false) {
    //     die("Prepare failed: " . $conn->error);
    // }
    // $check_query->bind_param("s", $prolificID);
    // $check_query->execute();
    // $check_query->bind_result($count, $pass); // 检索 pass 列的值
    // $check_query->fetch();
    // $check_query->close();

    // // 如果用户存在并且 pass 列等于 0，弹出提示对话框
    // if ($count > 0 && $pass == 0) {
    //     echo "<script>alert('You didn\\'t pass the comprehension check, please return the study.');</script>";
    //     exit; // 停止执行后续代码
    // }

    // 检查 Prolific ID 是否存在于数据表中，并查询 pass 列的值
    $check_query = $conn->prepare("SELECT pass FROM data WHERE prolific_ID = ? LIMIT 1");
    if ($check_query === false) {
        die("Prepare failed: " . $conn->error);
    }
    $check_query->bind_param("s", $prolificID);
    $check_query->execute();
    $check_query->bind_result($pass);
    $record_found = $check_query->fetch(); // 通过 fetch() 方法检查是否有记录
    $check_query->close();

    // 如果用户存在并且 pass 列等于 0，弹出提示对话框
    if ($record_found && $pass == 0) {
        echo "<script>alert('You didn\\'t pass the comprehension check, please return the study.');</script>";
        exit; // 停止执行后续代码
    }

    $check_query = $conn->prepare("SELECT ismanual, randomizerstatus FROM data WHERE prolific_ID = ? LIMIT 1");
    if ($check_query === false) {
        die("Prepare failed: " . $conn->error);
    }
    $check_query->bind_param("s", $prolificID);
    $check_query->execute();
    $check_query->bind_result($ismanual, $randomizerstatus);
    $record_found_manual = $check_query->fetch(); // 检查是否找到记录
    $check_query->close();

    if (        $record_found_manual  ) {
        echo "User! ";
        // 将查询到的 ismanual 和 randomizerstatus 输出到前端
        echo "<script>
            var ismanual = " . $ismanual . ";
            var randomizerstatus = " . $randomizerstatus. ";
        </script>";
    }
    else{
        $ismanual = ''; // 默认值
        $randomizerstatus = ''; // 默认值
        $ismanual = json_encode($ismanual);
        $randomizerstatus = json_encode($randomizerstatus);

    }
    // 初始化所有字段为空字符串
    $solutionlist = ''; // 默认值
    $savedsolutions = ''; // 默认值
    $savedobjectives = ''; // 默认值
    $parameterNames = ''; // 默认值
    $parameterBounds = '';
    $parameter_timestamp = '';
    $objectiveNames = ''; // 默认值
    $objectiveBounds = ''; // 默认值
    $objective_timestamp = '';
    $saved_timestamp = '';
    $objectiveminmax = '';

    $taskdescription = '';
    $chosen_solution = '';
    $start_timestamp = '';
    $tutorial = '';
 
    $pass = 3; // 默认值
    $tutorial_timestamp = ''; // 默认值



    // 将数组转换为 JSON 格式
    $solutionlist = json_encode($solutionlist);
    $savedsolutions = json_encode($savedsolutions);
    $savedobjectives = json_encode($savedobjectives);
    $parameterNames = json_encode($parameterNames);
    $parameterBounds = json_encode($parameterBounds);
    $parameter_timestamp = json_encode($parameter_timestamp);
    $objectiveNames = json_encode($objectiveNames);
    $objectiveBounds = json_encode($objectiveBounds);
    $objective_timestamp = json_encode($objective_timestamp);
    $saved_timestamp = json_encode($saved_timestamp);
    $objectiveminmax = json_encode($objectiveminmax);
    $taskdescription = json_encode($taskdescription);
    $chosen_solution = json_encode($chosen_solution);
    $start_timestamp = json_encode($start_timestamp);
    $tutorial = json_encode($tutorial);
    $pass = json_encode($pass);
    $tutorial_timestamp = json_encode($tutorial_timestamp);


  
    // 构建列名和相应的值
    $columns = [
        'prolific_ID' => $prolificID,
        'Solutionlist' => $solutionlist,
        'Savedsolutions' => $savedsolutions,
        'Savedobjectives' => $savedobjectives,
        'parametername' => $parameterNames,
        'parameterbounds' => $parameterBounds,
        'parameter_timestamp' => $parameter_timestamp,
        'objectivename' => $objectiveNames,
        'objectivebounds' => $objectiveBounds,
        'objective_timestamp' => $objective_timestamp,
        'saved_timestamp' => $saved_timestamp,
        'objectiveminmax' => $objectiveminmax,
        'ismanual' => $ismanual,
        'taskdescription' => $taskdescription,
        'chosen_solution' => $chosen_solution,
        'start_timestamp' => $start_timestamp,
        'tutorial' => $tutorial,
        'randomizerstatus' => $randomizerstatus,
        'tutorial_timestamp' => $tutorial_timestamp,
        'pass' => $pass

    ];

    $check_query = $conn->prepare("SELECT COUNT(*) FROM data WHERE prolific_ID = ?");
    if ($check_query === false) {
        die("Prepare failed: " . $conn->error);
    }
    $check_query->bind_param("s", $prolificID);
    $check_query->execute();
    $check_query->bind_result($count);
    $check_query->fetch();
    $check_query->close();

    // 如果不存在，则插入新记录
    if ($count == 0) {
        $columnNames = implode(", ", array_keys($columns));
        $placeholders = implode(", ", array_fill(0, count($columns), "?"));

        $stmt = $conn->prepare("INSERT INTO data ($columnNames) VALUES ($placeholders)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $types = str_repeat("s", count($columns));
        $values = array_values($columns);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    } 
    // // 如果存在，则更新该记录
    else {
        $_SESSION['ProlificID'] = $prolificID;
        $update_query = "UPDATE data SET ";
        $update_values = [];
        foreach ($columns as $column => $value) {
            if ($column != 'prolific_ID') {
                $update_query .= "$column = ?, ";
                $update_values[] = $value;
            }
        }
        $update_query = rtrim($update_query, ", "); // 去掉最后的逗号
        $update_query .= " WHERE prolific_ID = ?";
        $update_values[] = $prolificID;

        $stmt = $conn->prepare($update_query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $types = str_repeat("s", count($update_values));
        $stmt->bind_param($types, ...$update_values);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    // $conn->close();
}
// $ismanual = 1;
// $ismanual = 1;



    if (        $record_found_manual  ) {
        echo "User! ";
        // 将查询到的 ismanual 和 randomizerstatus 输出到前端
        echo "<script>
            var ismanual = " . json_encode($ismanual) . ";
            var randomizerstatus = " . json_encode($randomizerstatus) . ";
        </script>";
    } else {
        // 如果未找到记录，可以在此处理错误或提示
        // echo "<script>alert('User not found with Prolific ID: " . htmlspecialchars($prolificID) . "');</script>";



   

   // 获取前一个用户的数据
   $current_user_query = $conn->prepare("SELECT ID, randomizerstatus, ismanual FROM data WHERE prolific_ID = ?");
   if ($current_user_query === false) {
       die("Prepare failed: " . $conn->error);
   }
   $current_user_query->bind_param("s", $prolificID);
   $current_user_query->execute();
   $current_user_result = $current_user_query->get_result()->fetch_assoc();

   if (!$current_user_result) {
       die("No user found with ProlificID: " . $prolificID);
   }

   $current_user_id = $current_user_result['ID'];

   // 查询前一个用户的数据（ID-1）
   $previous_user_id = $current_user_id - 1;
   $previous_user_query = $conn->prepare("SELECT randomizerstatus, ismanual FROM data WHERE ID = ?");
   if ($previous_user_query === false) {
       die("Prepare failed: " . $conn->error);
   }
   $previous_user_query->bind_param("i", $previous_user_id);
   $previous_user_query->execute();
   $previous_user_result = $previous_user_query->get_result()->fetch_assoc();

   // 函数：将值从 JSON 转换为整数或设置为 null
   function processField($json_value) {
       $decoded_value = json_decode($json_value, true);
       if (is_numeric($decoded_value)) {
           return (int)$decoded_value;
       }
       return null;
   }

   // 将 previousUser 的 ismanual 和 randomizerstatus 从 JSON 转换为数字
   $previous_ismanual = processField($previous_user_result['ismanual']);
   $previous_randomizerstatus = processField($previous_user_result['randomizerstatus']);

   // 应用逻辑，设置当前用户的 ismanual 和 randomizerstatus
   if ($previous_randomizerstatus == 1) {
       if ($previous_ismanual == 1) {
           $ismanual = 0; // 设置当前用户的 ismanual 为 0因为sql的错误只能这样做
        //    $ismanual = 1; // 设置当前用户的 ismanual 为 0

       } else {
           $ismanual = 1; // 设置当前用户的 ismanual 为 1
       }
       $randomizerstatus = 2; // 设置 randomizerstatus 为 2
   } else {
       // 如果前一个用户的 randomizerstatus 是 2 或者为空，随机生成 ismanual
       $ismanual = rand(0, 1); // 随机生成 0 或 1 因为sql的错误只能这样做
    // $ismanual = 1; // 设置当前用户的 ismanual 为 0

       $randomizerstatus = 1;  // 设置 randomizerstatus 为 1
   }

   // 再次将 ismanual 和 randomizerstatus 转换为 JSON 格式以存储
   $ismanual = $ismanual;
   $randomizerstatus = $randomizerstatus;

   // 更新数据到数据库
   $stmt = $conn->prepare("UPDATE data SET ismanual = ?, randomizerstatus = ? WHERE prolific_ID = ?");
   if ($stmt === false) {
       die("Prepare failed: " . $conn->error);
   }

   // 绑定参数并执行 SQL 查询
   $stmt->bind_param("sss", $ismanual, $randomizerstatus, $prolificID);
   if ($stmt->execute()) {
       echo "User record updated successfully.";
   } else {
       echo "Error updating record: " . $stmt->error;
   }

   // 输出 ismanual 和 randomizerstatus 到前端 JavaScript
   echo "<script>
       var ismanual = " . json_encode($ismanual) . ";
       var randomizerstatus = " . json_encode($randomizerstatus) . ";
   </script>";
}

    $conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Steps</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: left;
        }

        h1 {
            text-align: center;
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #333;
        }

        ol {
            line-height: 1.6;
            font-size: 1.1em;
            color: #555;
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #888;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Study Steps</h1>
        <ol>
            <li>You read one tutorial of the study.</li>
            <li>You need to complete a comprehension check to test your understanding of the tutorial.</li>
            <li>After successfully completing the comprehension check, you can continue reading tutorials or directly start solving an optimization task we have assigned you. During the task, you will have a mid-term questionnaire.</li>
            <li>After solving the task, you need to complete a questionnaire.</li>
        </ol>
        
        <!-- Button to start -->
        <div class="btn-container">
            <a href="index_3.php" class="btn btn-primary">Start</a>
        </div>

        <div class="footer">
            <p>Please follow the steps carefully to complete the study.</p>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhvWnY+2hZT1I1jvu2F6jDOMENt7MQi1w5n3p6TA5mDz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-q6E9RHvbIyZFJoft+SKNVkRBy4oO1podoEPI2mJrV69jIMwI5T9OMa0CB5pG1SkJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
    <script>  
 localStorage.setItem('ismanual', ismanual);
 localStorage.setItem('randomizerstatus', randomizerstatus);  
      
        </script>
</body>
</html>
