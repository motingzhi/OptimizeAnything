<?php
$servername = "localhost";
$username = "phpmyadmin"; // 替换为你的数据库用户名
$password = "sxsx23562456"; // 替换为你的数据库密码
$dbname = "optimize"; // 数据库名为 optimizer

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
