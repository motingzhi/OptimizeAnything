<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}
$userID = $_SESSION['ProlificID']; 


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .top-bar {
            margin-top: 120px;
        }
        .top-bar h1 {
            font-size: 36px;
        }
        .subheading {
            margin-top: 80px;
            font-size: 18px;
        }
        .image-section {
            margin-top: 80px;
        }
        .centered-content img {
            max-width: 100%;
        }
        .content-description {
            margin-top: 120px;
            font-size: 18px;
        }
        .card-section {
            margin-top: 80px;
            margin-bottom: 40px;
        }
        .fixed-size-card {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .card-title {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container text-center top-bar">
    <br>
        <h1>Welcome to the user study on the service "Optimize anything"!</h1>
        <h5><br><br><br><br>This service helps users to find the optimal solutions for the optimization task, assisting by AI. <br><br>Here is an example of how it works:<br><br><br><br></h5>
    </div>

    <div class="container text-center centered-content image-section">
        <img src="Pictures/Group 1551.png" alt="Example Process">
    </div>

    <div class="container text-center content-description">
    <!-- <h5>To start, please go through the tutorial of this service first:</h5> -->
    <h5>To get started, select a tutorial that interests you.</h5>

    </div>

    <div class="container card-section">
    <div class="row justify-content-center text-center">
        <div class="col-md-4 mb-1">
            <a href="javascript:void(0)" class="card-link" id="material">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Find the optimal solutions for car material optimization</h4>
                    </div>
                </div>
            </a>
        </div>
        <!-- <div class="col-md-4 mb-1">
            <a href="javascript:void(0)" class="card-link" id="plan">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Find the optimal solutions for the upper body exercise plan</h4>
                    </div>
                </div>
            </a>
        </div> -->
    </div>
</div>
    
</div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqic4YVOuVVV1F6wJ4g2KqLkEBwJB0+TE9YfIWqZl0O2VSr10p" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>

        $(document).ready(function() {
            $('#material').on('click', function() {
                // 假设 Prolific ID 已经在页面上获取或可以从会话中获取
                // var ismanual = localStorage.getItem("ismanual");
                var tutorial = 1;
                // AJAX 请求
                $.ajax({
                    url: "material_1.php",
                    type: "post",
                    data: {
                        // 'ismanual'   :ismanual,
                        'tutorial'   :tutorial


                    },
                    success: function(response) {
                        // 请求成功后，跳转到 material_1.php 页面
                        window.location.href = 'material_1.php';
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            });
            // $('#plan').on('click', function() {
            //     // 假设 Prolific ID 已经在页面上获取或可以从会话中获取
            //     var ismanual = localStorage.getItem("ismanual");
            //     var tutorial = 2;
            //     // AJAX 请求
            //     $.ajax({
            //         url: "plan_1.php",
            //         type: "post",
            //         data: {
            //             // 'ismanual'   :ismanual,
            //             'tutorial'   :tutorial

            //         },
            //         success: function(response) {
            //             // 请求成功后，跳转到 material_1.php 页面
            //             window.location.href = 'plan_1.php';
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('AJAX Error: ' + status + error);
            //         }
            //     });
            // });
        });
    //                   // 添加历史记录
    //                   history.pushState(null, null, location.href);

    // // 当用户按后退按钮时，自动跳转到 index.php
    // window.addEventListener('popstate', function(event) {
    //     // 重定向到 index.php
    //     window.location.href = 'index.php';
    // });
 localStorage.setItem('ismanual', ismanual);
localStorage.setItem('randomizerstatus', randomizerstatus);  
    </script>

</body>
</html>



