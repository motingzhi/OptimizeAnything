<?php
// session_start();
// echo "logged_in";
// echo $_SESSION['logged_in'];
// echo "user_info";
// echo implode(', ', $_SESSION['user_info']);
// require_once 'config.php';
// // 检查用户是否已登录
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     // 如果用户未登录，将其重定向到登录页面
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
        <div class="top-bar">   
        <div class="container d-flex justify-content-between align-items-center">
            <h1>How it works</h1>
        </div>      

        </div>
        <div class="container content">

            <!-- <p>AI will propose you solutions one at a time. You evaluate them and tell the AI. You can always propose solutions and steer the AI.</p> -->
            <img src="Pictures/image2.png" class="img-fluid"> <!-- Bootstrap responsive image class -->
            <br>

            <div class="text-right"> <!-- Bootstrap class for right alignment -->
                <form action="tutorial.php">
                    <input type="submit" value="Ready" class="btn btn-success" style="width: 20%;"> <!-- Bootstrap button style -->
                </form>
            </div>
        </div>

    <style>
        .top-bar {
            position: absolute; /* 更改为绝对定位 */
            top: calc(100vh / 10); /* 设定距顶部1/6页面高度 */
            width: 100%;
            background: transparent;
            padding: 10px 0;
            box-shadow: none; /* 移除阴影 */
        }
        .content {
            display: flex;
            flex-direction: column;
            height: 100vh; /* 使主内容区占满视口高度 */
            justify-content: center; /* 垂直居中 */
        }
        .mySlides {
            display: block;
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }
        .active {
            background-color: #717171;
        }
    </style>
    
    <script>

    </script>

    </body>
</html>
