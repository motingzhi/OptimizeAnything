<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['ProlificID'];
    $parameterNames = json_encode($_POST['parameter-names']);
    $parameterBounds = json_encode($_POST['parameter-bounds']);
    $parameter_timestamp = json_encode(date("Y-m-d H:i:s"));

    
//   // 输出调试信息
//     echo "Prolific ID: " . htmlspecialchars($prolificID) . "<br>";
//     echo "Parameter Names: " . htmlspecialchars($parameterNames) . "<br>";
//     echo "Parameter Bounds: " . htmlspecialchars($parameterBounds) . "<br>";
//     echo "Define Timestamp: " . htmlspecialchars($defineTimestamp) . "<br>";

    $stmt = $conn->prepare("UPDATE data SET parametername = ?, parameterbounds = ?, parameter_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $parameterNames, $parameterBounds, $parameter_timestamp, $userID);
    if ($stmt->execute()) {
        header("Location: define-2.php");
        exit();
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
            overflow-y: auto; /* 添加垂直滚动条 */
            max-height: calc(100vh - 350px); /* 计算中间内容的最大高度减去top-bar和bottom-bar的高度 */
            margin-top: calc(100vh / 10 + 100px); /* Offset by the height of top-bar */
            text-align: center;
            width: 60%; /* Content width as 1/3 of the page */
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
            <form action="tutorial_1.php">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>
        </div>
    </div>
    
    <div class="centered-content">


        <h5 for="optimizeScenario">Your optimization task: </h5>

        <label > <strong class="text-primary">Imagine you are an athlete preparing for a marathon contest. You want to optimize your diet to lose weight and keep fit at the same time. What kind of variables and objectives you will input here?</strong>
        </label></br>
        <!-- <h2 style="margin-top: 20px;">Specify variables</h2> -->
        <!-- <p><i>Describe each varible that you want to change for optimization. Here a pre-filled example is for the travel scenario, and varibles for the travel are “destination distance”, “number of days” or "number of flight connections".</i></p> -->
        <!-- <p><i>You can modify those values in the form directly to what you want to optimize for your own scenario.</i></p>
        <p><i>You can modify those values in the form directly to what you want to optimize for your own scenario.</i></p> -->

        <label style="margin-bottom: 20px;">Variables</label></br>
        <!-- <label style="margin-bottom: 20px;">For example, you can input gram of fruit, or portions of spagetti, or gram of carbon, etc</label> -->

        <table class="table table-bordered" id="parameter-table">
            <thead>  
                <tr>  
                    <th id="record-parameter-name" width="40%"> Variable Name </th>   
                    <th id="record-parameter-unit" width="40%"> Unit(if have) </th>   
                    <th id="record-parameter-lower-bound"> Minimum </th>  
                    <th id="record-parameter-upper-bound"> Maximum </th>  
                    <th class="delete"> Delete </th>   
                </tr>  
            </thead>  
            <tbody>


                <tr>
                    <!-- <td contenteditable="true" class="record-data" id="record-parameter-name">Input variable here</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-unit"></td>
                    <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">Input number</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">Input number</td>
                    <td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>
                </tr>
                <tr>
                    <td contenteditable="true" class="record-data" id="record-parameter-name"> Input variable here</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-unit"></td>
                    <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">Input number</td>
                    <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">Input number</td>
                    <td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td> -->
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

            try {
            var parameterNames = localStorage.getItem("parameter-names").split(",");
            } catch (err) {
            // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
            var parameterNames = [];
            }

            try {
            var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
            } catch (err) {
            // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
            var parameterBounds = [];
            }

    
            if (parameterNames.length > 0) {
                // Clear existing rows in the table body
                $('#parameter-table tbody').empty();
                // Add rows based on parameterNames and parameterBounds
                for (let i = 0; i < parameterNames.length; i++) {
                    let nameParts = parameterNames[i].split('/');
                    let lowerBound = parameterBounds[2 * i];
                    let upperBound = parameterBounds[2 * i + 1];
                    
                    let htmlNewRow = "<tr>";
                    htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-name'>${nameParts[0]}</td>`;
                    htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-unit'>${nameParts[1] || ''}</td>`;
                    htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-lower-bound'>${lowerBound}</td>`;
                    htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-upper-bound'>${upperBound}</td>`;
                    htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>";
                    htmlNewRow += "</td></tr>";
                    
                    $("#parameter-table tbody").append(htmlNewRow);
                }
            }
            else{
                addExampleParametersTable();
                addExampleParametersTable();
            }

            //根据local storage填充表格：


            // var parameterNames = [];
            // var parameterBounds = [];

            // var tableParam = $("#parameter-table tbody");
                
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
                    $.ajax({
                            url: "define-2.php",
                            type: "post",
                            data: {
                            'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds)
                            },
                            success: function(response) {
                                var url = "define-2.php";
                                window.location.href = url;
                            },
                            error: function(response) {
                                console.log("Error sending data to define-2.php");
                            }
                        });
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
            htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>"
            htmlNewRow += "</td></tr>"
            $("#parameter-table", window.document).append(htmlNewRow);  
            $(window.document).on('click', ".record-delete", deleteParameterTable);
        }
        function addExampleParametersTable(){
            var htmlNewRow = ""
            htmlNewRow += "<tr>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-name'>Input variable here</td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-unit'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-lower-bound'>Input number</td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-upper-bound'>Input number</td>"
            htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>"
            htmlNewRow += "</td></tr>"
            $("#parameter-table", window.document).append(htmlNewRow);  
            $(window.document).on('click', ".record-delete", deleteParameterTable);
        }

        function deleteParameterTable(){
            $(this).parents('tr').remove();
        }
    </script>
    
    </body>
</html>


