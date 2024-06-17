<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// // 第二个 PHP 页面
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
// // $google_oauth = new Google_Service_Oauth2($client);
// // $google_account_info = $google_oauth->userinfo->get();
// // $userinfo = [
// //     'token' => $google_account_info['id'],
// //   ];

// // // // 获取用户 ID
// // // $user_id = $_SESSION['user_id'];
// $user_email= $_SESSION['user_info']['email'];
// echo "user_email";
// echo $user_email;

// // 处理用户提交的参数名称并存储到数据库中

//   // 检查连接
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }

//     // 获取用户输入的参数名称
//     // $parameterNames = 7;
//     // 准备 SQL 语句
//     $sql = "UPDATE users SET locale = 7 WHERE email = ?";

//     // 创建预处理语句
//     $stmt = $conn->prepare($sql);

//     // 绑定参数
//     $stmt->bind_param("s", $user_email);

//     // 执行查询
//     if ($stmt->execute()) {
//         echo "Locale updated successfully.";
//     } else {
//         echo "Error updating locale: " . $conn->error;
//     }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1. Define</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-bar {
            position: fixed;
            top: calc(100vh / 12);
            width: 100%;
            background: transparent;
            padding: 10px 0;
            box-shadow: none;
        }

        .centered-content {
            margin-top: calc(100vh / 10 + 100px); /* Offset by the height of top-bar */
            text-align: center;
            width: 33.33%; /* Content width as 1/3 of the page */
            margin-left: auto;
            margin-right: auto;
        }

        .bottom-bar {
            position: fixed;
            /* margin-top: 100px; */
            bottom: 0px;
            width: 100%;
            background: #f8f9fa; /* Light grey background similar to Bootstrap's default navbar */
            padding: 10px 0;
            /* box-shadow: none; */
             /* Shadow for the bottom bar */
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1); /* Shadow for the bottom bar */
        }

        #loadingContainer {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #loadingIcon {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #53A451;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #loadingText {
            text-align: center;
            margin-top: 20px;
        }

        /* .record-data {
            color: black;
        } */
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>1. Specify</h1>
            <form action="help.php#define">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>
        </div>
    </div>
    
    <div class="centered-content">
        <h2 style="margin-top: 20px;">Specify variables</h2>
        <p><i>Describe each varible that you want to change for optimization. Here a pre-filled example is for the travel scenario, and varibles for the travel are “destination distance”, “number of days” or "number of flight connections".</i></p>
        <p><i>You can modify those values in the form directly to what you want to optimize for your own scenario.</i></p>

        <h5 style="margin-bottom: 20px;">Variables</h5>
        <table class="table table-bordered" id="parameter-table">
            <thead>  
                <tr>  
                    <th id="record-parameter-name" width="40%"> Variable Name </th>   
                    <th id="record-parameter-unit" width="40%"> Unit(if have) </th>   
                    <th id="record-parameter-lower-bound"> Minimum </th>  
                    <th id="record-parameter-upper-bound"> Maximum </th>  
                </tr>  
            </thead>  
            <tbody>
                <tr>
                    <td contenteditable="true" class="record-data" id="record-parameter-name">Destination distance</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-unit">km</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">500</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">3000</td>
                </tr>
                <tr>
                    <td contenteditable="true" class="record-data" id="record-parameter-name">Number of days</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-unit">None</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">3</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">14</td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-primary" id="add-record-button" onclick="addDesignParametersTable()">Add Variable</button>
    </div>
    
    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>

    <div class="bottom-bar">
        <div class="container text-right">
            <button class="btn btn-success" id="finish-objectives-button" style="width: 20%;" onclick="finishObjs()">Next</button>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
    <script>
        $(document).ready(function() {
            const firstCell = $('#parameter-table tbody tr:first td:first');
            firstCell.focus();

            $('.record-data').on('focus', function() {
                if ($(this).css('color') === 'rgb(128, 128, 128)') { // gray color in rgb
                    $(this).css('color', 'black');
                }
            });
        });

        function finishObjs() {
            // saveFormData();


            var noError = true;
            var parameterNames = [];
            var parameterBounds = [];

            var tableParam = $("#parameter-table tbody");
                
            tableParam.find('tr').each(function() {
                var $paramCols = $(this).find("td");
                var paramRowEntries = [];
    
                $.each($paramCols, function() {
                    paramRowEntries.push($(this).text());
                });
                
                var paramName = paramRowEntries[0];
                var unit = paramRowEntries[1];
                if (unit === "None"){
                parameterNames.push(paramName);
                } 
                else {
                    parameterNames.push(paramName+"/"+unit);

                }

                var paramLowerBound = paramRowEntries[2];
                var paramUpperBound = paramRowEntries[3];
                var validLowerBound = (!isNaN(parseFloat(paramLowerBound)) && isFinite(paramLowerBound));
                var validUpperBound = (!isNaN(parseFloat(paramUpperBound)) && isFinite(paramUpperBound));

                if (validLowerBound && validUpperBound){
                    if (parseFloat(paramLowerBound) < parseFloat(paramUpperBound)){
                        var rowBounds = [parseFloat(paramLowerBound), parseFloat(paramUpperBound)];
                        parameterBounds.push(rowBounds);
                    }
                    else {
                       noError = false;
                    }
                }
                else {
                    noError = false;
                }
            });

            if (parameterBounds.length != parameterNames.length && parameterBounds.length <= 1){
                noError = false;
            }
    
            if (noError){
                localStorage.setItem("parameter-names", parameterNames);
                localStorage.setItem("parameter-bounds", parameterBounds);
    
                $.ajax({
                url: "./cgi/log-definitions_u.py",
                type: "post",
                datatype: "json",
                data: { 'parameter-names'    :String(parameterNames),
                        'parameter-bounds'   :String(parameterBounds)},
                beforeSend: function() {
                // 显示 loading 动画和文字
                $('#loadingContainer').show();
                },
                success: function(result) {
                    var url = "define-2.php";
                    location.href = url;
                    $('#loadingContainer').hide();
                },
                error: function(result){
                    console.log("Error");
                }
                });
            }
            else {
                alert("Invalid entry");
            }    
        }

        function addDesignParametersTable(){
            var htmlNewRow = ""
            htmlNewRow += "<tr>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-name'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-unit'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-lower-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-upper-bound'></td>"
            htmlNewRow += "</td></tr>"
            $("#parameter-table", window.document).append(htmlNewRow);  
        }

    </script>
    
    </body>
</html>
