<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Tutorial</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .fixed-size-card {
            height: 300px; /* 设置卡片的固定高度 */
            width: 100%; /* 设置卡片宽度为100%占满列宽 */
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center; /* 垂直居中内容 */
            align-items: center; /* 水平居中内容 */
        }
        /* 整体布局样式 */
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
        body, html {
            margin: 0;
            overflow: hidden; /* 隐藏滚动条 */
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Onboarding tutorial</h1>
<<<<<<< Updated upstream
            <button class="btn btn-outline-primary">Skip</button>
=======
            <a href="define.php" class="btn btn-outline-primary">Skip</a>
>>>>>>> Stashed changes
        </div>
    </div>
    <div class="container content">
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Build a rocket</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
            <a href="material_1.php" class="card-link">

                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Optimize car material</h4>
                    </div>
                </div>
                </a>

            </div>
            <div class="col-md-4 mb-3">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Plan a trip</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
