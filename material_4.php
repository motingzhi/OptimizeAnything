<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}
$prolificID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

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
    .centered-content video {
        max-height: calc(100vh - 350px);
        max-width: 100%;
        height: 60%;
        width: auto;
    }
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

        .top-bar .nav {
            display: flex;
            justify-content: center;
            width: 100%; /* Control the width of the progress bar */
        }

        .top-bar .nav-link {
            color: #6c757d;
            background-color: #e9ecef;
            padding: 10px 20px;
            text-align: center;
            width: 100%;
            max-width: 33.333333%; /* Ensure consistent width for each link */
            margin: 0px;
            flex-grow: 1;
        }

        .top-bar .nav-link.active {
            color: white;
            background-color: #007bff;
            font-weight: bold;
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
            margin-left: 15%;
            margin-bottom: 5%;

            width: 70%;
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
            width: 50%;
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
   <h5><strong>Tutorial: (5/5)
   <br><br>Get results</strong></h5>

    </div>

    <div class="centered-content">
    <img src="Pictures/Group 138.png" alt="Specify objectives" class="img-fluid" style="width: 60%"><br><br><br>
        <!-- <div class="stepper">
            <div class="step">
                <span>1</span>
                <p>Specify Variables</p>
            </div>
            <div class="step active">
                <span>2</span>
                <p>Specify Objectives</p>
            </div>
        </div> -->
        <div class="card custom-card">

        <!-- <p ><strong>Get results:</strong> </p> -->
                <div class="card-body">

                <label>1. Al will sort out the optimal solutions from all solutions previously generated in the "Optimize" step, based on your evaluations. 
                <br> 2. The results might include multiple solutions when your have multiple objectives, and you must make trade-offs and select one you like the most. </p>    </label>
                </div>
        </div>
        <!-- <p><strong>Get results:</strong> </p>
        <br>
        <p>You will see the optimal solutions of variables to achieve your objectives. The displayed results might include multiple solutions when your objectives include both minimizing and maximizing, and you will need to make trade-offs.</p> -->

        <!-- Example Table -->
        <div class="text-center mb-4">
        <figcaption style= "width: 50%; text-align: center;  margin-left: 25%;"><strong>Example of the results</strong> </figcaption><br>

           
<p style= "width: 50%; text-align: left;  margin-left: 25%;">
            In this example, Solution 3 is the best solution to minimize costs, followed by Solution 4 and then Solution 5. But to maximize durability, Solution 5 is the best solution, followed by Solution 4 and then Solution 3.  <br> 
                        <br> AI cannot identify a single solution among these three that performs the best in both cost and durability. The user needs to choose the one that suits the user's preferences best.      </p> 

            <img src="Pictures/results.png" alt="Specify variables" class="img-fluid"  style="border: 1px solid black; width: 50%; text-align: left;">
        </div>

        <div class="d-flex justify-content-center" id="exercise" style="display: none;">
            <div class="col-md-4 mb-1" id="exercise" style="display: none;">
                <br> <p>Now you have finished the first tutorial, please complete the comprehension check.
                <br>                <br>

                If you are still confused after reading this example, you can also continue to look at the example of optimizing an exercise plan. This example is not required for the comprehension check. </p>
                    <a href="javascript:void(0)" class="card-link" id="plan">
                        <div class="card fixed-size-card">
                            <div class="card-body">
                                <h4 class="card-title">Optimize the upper body exercise plan</h4>
                            </div>
                        </div>
                    </a>
                </label>
            </div>
        </div>
        
    </div>

    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="material_3.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <!-- <div class="col text-right">
                <a href="intro.php" class="btn btn-primary">Try your own!</a>
            </div> -->
            <div class="col text-right">
            <a href="#" id="understandButton" class="btn btn-primary">Go to the comprehension check</a>
            </div>
            <!-- <div class="col text-right">
                <a href="intro-diet.php" class="btn btn-primary"></a>
            </div> -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
    // 假设答题数据存储在 localStorage 的 'quizData' 键中
    let quizData = JSON.parse(localStorage.getItem('quizData')) || {
        q1_attempts: 0,
        q1_correct: false,
        q2_attempts: 0,
        q2_correct: false,
    };

    document.addEventListener("DOMContentLoaded", function () {
        // 如果用户全部答对，改变按钮文字和链接
        if (quizData.q1_correct && quizData.q2_correct) {
            const understandButton = document.getElementById('understandButton');
            understandButton.textContent = "Start solving optimization task";
            understandButton.href = "intro-diet.php";  // 跳转到 intro-diet.php
        }

        // // 检查用户是否有答题尝试，如果有，则显示 exercise 部分
        // if (quizData.q1_attempts > 0 or quizData.q2_attempts > 0) {
        //     document.getElementById('exercise').style.display = 'block';
        // }
    });
    // console.log(ispass);
    let hasScrolledToBottom = false;
    let hasWaitedLongEnough = false;
    const hasVisitedBefore = localStorage.getItem('hasVisitedBefore3');

    // 检查用户是否已经访问过页面
    if (!hasVisitedBefore) {
        // 用户第一次访问页面
        localStorage.setItem('hasVisitedBefore3', 'true');

        // Function to check if the user has scrolled to the bottom
        function checkScrollPosition() {
            const content = document.getElementById('content');
            if (content.scrollTop + content.clientHeight >= 0.67 * content.scrollHeight) {
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
        document.getElementById('understandButton').addEventListener('click', function (event) {
            if (hasWaitedLongEnough) {

                $.ajax({
                    url: "check.php",
                    type: "post",
                    data: {
                    },
                    success: function (response) {
                        // 请求成功后，跳转到 plan_1.php 页面
                        window.open('check.php', '_blank');
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });

            } else {
                alert("Please spend time reading all tutorial first");
                event.preventDefault(); // Prevent the default action of the anchor
            }
        });
    } else {
        // 用户已经访问过页面
        document.getElementById('understandButton').addEventListener('click', function () {

            $.ajax({
                    url: "check.php",
                    type: "post",
                    data: {
                    },
                    success: function (response) {
                        // 请求成功后，跳转到 plan_1.php 页面
                        window.open('check.php', '_blank');
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            // window.open('check.php', '_blank');
        });
    }

    $('#plan').on('click', function () {
        var tutorial = 2;
        // AJAX 请求
        $.ajax({
            url: "plan_1.php",
            type: "post",
            data: {
                'tutorial': tutorial
            },
            success: function (response) {
                // 请求成功后，跳转到 plan_1.php 页面
                window.location.href = 'plan_1.php';
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
</script>


</body>
</html>
