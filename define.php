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
    <title>1. Specify Variables</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container-fluid {
            display: flex;
            height: 100vh;
            justify-content: space-between;
        }
        .card-custom {
            width: 25%;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .main-content {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 80%;
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
            right: 100;
            /* right: 0%; */
            width:100%;
            z-index: -1;
        }
        .step span {
            display: inline-block;
            padding: 10px 20px;
            background: #f8f9fa;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .step.active span {
            font-weight: bold;
            color: #007bff;
            border-color: #007bff;
            background: white;
        }
        .tooltip-container {
            position: relative;
            display: inline-block;
        }
        .tooltip-container .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 100%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .separator {
            border-left: 1px solid #ddd;
            height: 100%;
        }
        .bottom-bar {
            width: 100%;
            background: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }
        .top-bar {
            width: 100%;
            padding: 10px 0;
            /* box-shadow: 0 2px 4px rgba(0,0,0,0.1); */
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

    </style>
</head>
<body>
    <div class="container-fluid">

        <div class="card card-custom">
            <div class="card-body">
                <p class="text-primary">Your optimization task:</p>
                <label>Imagine you have decided to eat more healthily. You want to choose a diet that is enjoyable, helps you lose weight, and keeps you healthy at the same time. What variables and objectives will you specify here?</label>
            </div>
            <div class="card-body">
                <p class="text-primary">Hints</p>
                <label>1. The solution generated by AI will be constructed by <strong>Variables</strong> specified here, and the value generated will be inside the minimum and maximum values you specified.</label><br>
                <label>2. The <strong>Objectives</strong> are the criteria to evaluate the solution generated by AI.</label><br>
                <label>3. The specification for <strong>Variables</strong> shall not be equal to <strong>Objectives</strong>.</label><br>
            </div>

        </div>


        <div class="separator"></div>

        <div class="main-content">
            <div class="top-bar">
                <div class="stepper">
                    <div class="step active">
                        <span>1</span>
                        <div>Specify Variables</div>
                    </div>
                    <div class="step">
                        <span>2</span>
                        <div>Specify Objectives</div>
                    </div>
                    <div class="step">
                        <span>3</span>
                        <div>Confirm Definition</div>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <label style="margin-bottom: 20px;">Variables</label>
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
                    </tbody>
                </table>
                <button class="btn btn-outline-primary" id="add-record-button" onclick="addDesignParametersTable()">Add Variable</button>
            </div>
            <div class="bottom-bar">
                <button class="btn btn-primary" id="finish-objectives-button" style="width: 20%;" onclick="finishObjs()">Next</button>
            </div>
        </div>

        <div class="separator"></div>

        <div class="card card-custom">
            <div class="card-body">
                <p class="text-primary">Your specification overview:</p>
                <div>
                    You want to optimize
                    <span class="tooltip-container">
                        <input type="text" id="defineWhat" class="form-control mb-2 inline-input" placeholder="Variables" readonly>
                        <span class="tooltip-text">to be specified in the table below</span>
                    </span>
                    by
                    <span class="normal">minimizing/maximizing</span>
                    <span class="tooltip-container">
                        <input type="text" id="defineWhat" class="form-control mb-2 inline-input" placeholder="Objectives" readonly>
                        <span class="tooltip-text">to be specified in the table below</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
    <script>

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
    

        if (JSON.stringify(parameterNames) !== '[]') {                // Clear existing rows in the table body
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
                    $(window.document).on('click', ".record-delete", deleteParameterTable);

                }
            }
        else{
                addExampleParametersTable();
                addExampleParametersTable();
            }




        // $(document).ready(function() {
        //     const firstCell = $('#parameter-table tbody tr:first td:first');
        //     firstCell.focus();

        //     $('.record-data').on('focus', function() {
        //         if ($(this).css('color') === 'rgb(128, 128, 128)') { // gray color in rgb
        //             $(this).css('color', 'black');
        //         }
        //     });
        // });



        function finishObjs() {
            // saveFormData();
            var parameterNames = [];

            var parameterBounds = [];

            var noError = true;
 


            //根据local storage填充表格：


            // var parameterNames = [];
            // var parameterBounds = [];

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
                        url: "define-2.php",
                        type: "post",
                        data: {
                        'parameter-names'    :String(parameterNames),
                        'parameter-bounds'   :String(parameterBounds)
                        },
                        beforeSend: function() {
                        // 显示 loading 动画和文字
                        $('#loadingContainer').show();
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


