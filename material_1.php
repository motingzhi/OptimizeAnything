<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}
$userID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datetime = new DateTime("now", new DateTimeZone("Europe/Helsinki"));
    $start_timestamp = json_encode($datetime->format("Y-m-d H:i:s")); // 格式化时间戳为字符串
    $tutorial = json_encode($_POST['tutorial']);

    $stmt = $conn->prepare("UPDATE data SET start_timestamp = ? , tutorial = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $start_timestamp,$tutorial,$userID);
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimize the materials of a car</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-bar {
            position: fixed;
            top: 10%;
            left: 20%;
            right: 20%;
            width: 60%;
            background: transparent;
            padding: 0;
            box-shadow: none;
            z-index: 1000;
        }

        .top-bar h5 {
            text-align: center;
            margin: 0;
            margin-bottom: 1%; /* Distance between title and nav */
        }


        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5vh; /* Distance between stepper and content below it */
            width: 80%;
            margin: 0 auto;
            margin-top: 5%;
            margin-bottom: 5%;

        }

        .step {
            flex-grow: 1;
            text-align: center;
            position: relative;
        }

        .step:not(:last-child)::after {
            content: '';
            height: 2px;
            background: #ddd;
            position: absolute;
            top: 30%;
            right: -42%;
            width: 100%;
            z-index: -1;
        }

        .step span {
            display: inline-block;
            width: 50px;  /* Fixed width */
            height: 50px;  /* Fixed height */
            line-height: 50px;  /* Match the height to center the text */
            text-align: center;  /* Center the text horizontally */
            color: #8C8E97;
            background: #f8f9fa;
            border-radius: 50%;  /* Make it circular */
            border: 2px solid #ddd;
        }

        .step p {
            color: #8C8E97; /* Specify the color */
        }

        .step.active p {
            color: #007bff; /* Specify the color for active step */
        }

        .step.active span {
            font-weight: bold;
            color: #007bff;
            border-color: #007bff;
            background: white;
        }

        .centered-content {
            overflow-y: auto;
            max-height: calc(100vh - 350px);
            margin-top: 15%; /* Positioned 100px below the top-bar */
            margin-left: 20%;
            margin-bottom: 5%;

            width: 60%;
            text-align: center;
        }

        .bottom-bar {
            position: fixed;
            bottom: 0px;
            width: 100%;
            background: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }

        .bottom-bar .row {
            width: 100%;
            max-width: 60%;
            margin: 0 auto;
        }

        .custom-card {
            margin: 10px; /* 外边距 */
            display: inline-block; /* 使卡片宽度根据内容自适应 */
            width: 60%;
            border: none;

        }
        .custom-card .card-body {
            padding: 10px; /* 内边距 */
            text-align: left;
            border: none;

        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
    <h5><strong> Let's learn how to use this service!<br><br>Tutorial: (1/5)</strong><br><br>
 
        </h5>
        <!-- <nav class="nav">
            <a class="nav-link active" href="#">1. Specify</a>
            <a class="nav-link" href="#">2. Optimize</a>
            <a class="nav-link" href="#">3. Get results</a>
        </nav> -->
    </div>

    <div class="centered-content" id="content">
    <h5>

        </h5>
        <!-- <div class="stepper">
            <div class="step active">
                <span>1</span>
                <p>Specify Variables</p>
            </div>
            <div class="step">
                <span>2</span>
                <p>Specify Objectives</p>
            </div>
            <div class="step">
                <span>3</span>
                <p>Confirm</p>
            </div>
        </div> -->

        <div class="card custom-card">
            <label style= "text-align: left;">
            There are 3 steps to use this service: in the first step, "Specify", you will learn the basic concepts of "Variables" and "Objectives", with specific examples further down the page.<br><br>
            </label>
            <p ><strong>Specify variables:</strong> </p><br>
            <img src="Pictures/Group 134.png" alt="Specify variables" class="img-fluid" style="  width: 100%"><br><br><br>

                <div class="card-body">

                <label>


                1. AI will propose an optimal solution for your optimization task in the end.
                
                However, first you need to specify what composes the solutions—called 'Variables' in this service.
                                <br><br>
                                2. Specifying "Variables" involves providing both the names and ranges of these variables. During the "Optimize" step, AI will generate alternative solutions for your optimization task based on the variables and ranges you’ve specified.
                                                <br><br>

 </label>
                </div>
        </div>
        <!-- <p><strong>Specify variables:</strong> <br>You need to specify variables to AI for your optimization task. AI will provide solutiosns for your optimization task based on the variable name and the range of variables you specified.</p> -->
        <!-- Example Table -->
        <div class="text-center mb-4">
           <figcaption style= "width: 50%; text-align: center;  margin-left: 25%;">                <strong>Example of specifying variables:   </strong></figcaption> <br>
           <figcaption style= "width: 50%; text-align: left;  margin-left: 25%;">  
           For a car material optimization task, a material designer might specify "Material strength" and "Material density" as variables.

           <br></figcaption> <br>
           <video controls class="img-fluid" style="display: block; margin-top: 10px; width: 80%; margin: 0 auto 10px;border: 1px solid black;">
                <source src="Pictures/material_1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video> 
           <!-- <img src="Pictures/varible1.gif" alt="Specify variables" class="img-fluid" style="border: 1px solid black;"> -->
            <br>
            <figcaption style= "width: 50%; text-align: left;  margin-left: 25%;">  
           The minimum and maximum values are input based on his experience.

           <br></figcaption> 
            <br>
            <!-- <figcaption style= "width: 50%; text-align: center;  margin-left: 25%;">
            One of the alternative solutions generated by AI in the later 'Optimize' step will look like this: The solution's values will fall within the ranges of the 'Variables' specified in this 'Specify Variables' step            </figcaption>            <br>

            

            <img src="Pictures/Group 123.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black;  width: 60%"> -->
        </div>

    </div>

    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="index_2.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <div class="col text-right">
            <a href="#" id="understandButton" class="btn btn-primary">I understand</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
      <script>

        let hasScrolledToBottom = false;
        let hasWaitedLongEnough = false;
        const hasVisitedBefore = localStorage.getItem('hasVisitedBefore');

        // 检查用户是否已经访问过页面
        if (!hasVisitedBefore) {
            // 用户第一次访问页面
            // 记录用户已经访问过
            localStorage.setItem('hasVisitedBefore', 'true');

            // Function to check if the user has scrolled to the bottom
            function checkScrollPosition() {
                const content = document.getElementById('content');
                if (content.scrollTop + content.clientHeight >= 0.67*content.scrollHeight) {
                    hasScrolledToBottom = true;
                }
            }

            // Set a timer for 8 seconds after the page loads
            setTimeout(() => {
                hasWaitedLongEnough = true;
            }, 7000);

            // Add scroll event listener to the content area
            document.getElementById('content').addEventListener('scroll', checkScrollPosition);

            // Add click event listener to the "I understand" button
            document.getElementById('understandButton').addEventListener('click', function(event) {
                if (hasWaitedLongEnough) {
                    window.location.href = 'material_2.php';
                } else {
                    alert("Please spend time reading all tutorial first");
                    event.preventDefault(); // Prevent the default action of the anchor
                }
            });
        } else {
            // 用户已经访问过页面
            // 直接跳转到 material_2.php



        document.getElementById('understandButton').addEventListener('click', function() {
            window.location.href = 'material_2.php';
        });
        } 
    </script>

</body>
</html>
