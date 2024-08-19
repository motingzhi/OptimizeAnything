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
    $parameterNames = json_encode($_POST['parameter-names']);
    $parameterBounds = json_encode($_POST['parameter-bounds']);
    $parameter_timestamp = json_encode(date("Y-m-d H:i:s"));// 格式化时间戳为字符串

    $stmt = $conn->prepare("UPDATE data SET parametername = ?, parameterbounds = ?, parameter_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $parameterNames, $parameterBounds, $parameter_timestamp, $userID);
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
    <title>2. Specify Objectives</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      
      .top-bar {
            position: fixed;
            top: 5%;
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
            margin-bottom: 10%; /* Distance between title and nav */
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
            right: -50%;
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
            overflow-y: auto; /* 添加垂直滚动条 */
            max-height: calc(100vh - 350px); /* 计算中间内容的最大高度减去top-bar和bottom-bar的高度 */
            margin-top: calc(100vh / 10); /* Offset by the height of top-bar */
            text-align: center;
            width: auto; /* Content width as 1/3 of the page */
            min-width: 50%;
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

        .bottom-bar .row {
            width: 100%;
            max-width: 60%;
            margin: 0 auto;
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
      
    
        .custom-card {
            margin: 10px; /* 外边距 */
            display: inline-block; /* 使卡片宽度根据内容自适应 */
            width:auto;
            min-width: 60%;
        }
        .custom-card .card-body {
            padding: 10px; /* 内边距 */
            text-align: left;

        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .description {
            font-size: 1em;
            margin-bottom: 20px;
        }
        .highlight {
            display: inline-flex;
            align-items: center;
            font-size: 1em;
        }
        .underline {
            margin: 0 5px;
            padding: 5px 10px;
            border-bottom: 2px solid;
            font-size: 1em;
        }
        .underline-text {
        display: inline-block;
        font-weight: bold;
        border-bottom: 2px solid black; /* Creates the underline */
        margin: 0 5px; /* Adds some spacing around the text */
    }
        .variables {
            border-bottom-color: #000000;
            color: #E08AE9;
        }
        .objectives {
            border-bottom-color: #000000;
            color: #fb923c;
        }
        .normal {
            border-bottom-color: #000000;
            color: #000000;
        }
        /* .inline-input {
            width: auto;
            display: inline-block;
            min-width: 100px;
        } */
        .colored-placeholder::placeholder {
            color: blue;
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

        .tooltip-container.show-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <!-- <div class="container d-flex justify-content-between align-items-center"> -->


        <nav class="nav">
                <a class="nav-link active" href="#">1. Specify</a>
                <a class="nav-link" href="#">2. Optimize</a>
                <a class="nav-link" href="#">3. Get results</a>
            </nav>




    </div>
    
    <div class="centered-content">
    <!-- <form action="tutorial_1.php">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>     -->
        <div class="stepper">
                        <div class="step ">
                        <span>1</span>
                        <div>Specify Variables</div>
                        </div>
                        <div class="step active">
                        <span>2</span>
                        <div>Specify Objectives</div>
                        </div>
                        <div class="step">
                        <span>3</span>
                        <div>Confirm Specification</div>
                        </div>
        </div>


        <div class="container">
            <div class="card custom-card">
            <p class="text-primary"> Your specification overview:</p>
                <div class="card-body">

                        You want to change
                        <span id="defineWhat" class="underline-text"></span>
                        to minimize/maximize
                        <!-- <span class="normal"></span> -->
                        <span id="defineFor" class="underline-text">Objectives (To be specified)</span>
                </div>
                        
            </div>
        </div>

        <div class="container">
            <div class="card custom-card">
            <p class="text-primary"> Your optimization task:</p>
                <div class="card-body">
                        <label > Imagine you are a runner preparing for a marathon. You want to optimize your diet to lose weight and stay healthy at the same time. What variables and objectives will you specify here?</label></br>
                </div>
            </div>
        </div>

                
        <div class="container">
            <div class="card custom-card">
                    <p class="text-primary">Hints</p>

                    <div class="card-body">
                            <label >1. The solution generated by AI will be constructed by <strong>Variables</strong> you specified here, and the value generated will be inside the minimum and maximum values you specified.</label></br>
                            <label >2. You will evaluate the solution generated by AI later based on the <strong>Objectives</strong> you specified.</label></br>
                            <label >3. Please <strong>avoid</strong> inputting the same thing for variables and objectives, as variables are aimed at achieving the overall objectives. </label></br>
  
                </div>
                </div>
        </div>
            
        <label style="margin-bottom: 20px;">Objectives</label></br>
        <!-- <label style="margin-bottom: 20px;">For example, you can input possibility to lose weight, cost, satisfication, etc</label> -->

        <table class="table table-bordered" id="objective-table" >
            <thead>  
                <tr>  
                <th id="record-objective-name" width="40%"> Name </th> 
                <th id="record-objective-unit" width="40%"> Unit (if any) </th>     
                <th id="record-objective-lower-bound"> Minimum </th>  
                <th id="record-objective-upper-bound"> Maximum </th> 
                <th id="record-objective-min-max"> Minimize or Maximize </th>  
                <th class="delete"> Delete </th>   

                </tr>  
            </thead>  
            <tbody>
            
            </tbody>
        </table>
        <button class="btn btn-outline-primary" id="add-record-button" onclick="addDesignObjectivesTable()" >Add Objective</button>
    <!-- <div id="progressBar"><div class="progress"></div> -->
    <br>

    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>


</div>
<div class="bottom-bar">
    <div class="row">
    <div class="col text-left">
    <button class="btn btn-outline-primary" id="back-button" style="width: 20%;" onclick="goBack()">Modify Variables</button>
    </div>
    <div class="col text-right">
    <button class="btn btn-primary" id="finish-objectives-button" style="width: 20%;" onclick="finishObjs()">Save</button>
    </div>

 
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    var solutionNameList =  "";
    var badSolutions = [];
    var newSolution = true;
    var nextEvaluation = false;
    var refineSolution = false;
    var goodSolutions = [];
    var badSolutions = [];

    var parameterNames = localStorage.getItem("parameter-names").split(",");

    var paraString = parameterNames.join(', ');
    // document.querySelector('.variables').innerText = paraString;

    document.getElementById('defineWhat').innerText = paraString;


    function goBack() {
        // saveFormData();
        location.href = "define.php";
    }

    try {
        var objectiveNames = localStorage.getItem("objective-names").split(",");
        var objString = objectiveNames.join(', ');

        document.getElementById('defineFor').innerText = objString;

    } catch (err) {
        // 如果发生异常，例如   不存在，赋值一个空数组
        var objectiveNames = [];
        // document.querySelector('.tooltip-container').classList.add('show-tooltip');


    }

    try {
        var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
    } catch (err) {
        // 如果发生异常，例如 不存在，赋值一个空数组
        var objectiveBounds = [];
    }

    try {
        var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");
    } catch (err) {
        // 如果发生异常，例如  不存在，赋值一个空数组
        var objectiveMinMax = [];
    }


    if (JSON.stringify(objectiveNames) !== '[]') {                
        // Clear existing rows in the table body
        $('#objective-table tbody').empty();

        // Add rows based on parameterNames and parameterBounds
        for (let i = 0; i < objectiveNames.length; i++) {
            let nameParts = objectiveNames[i].split('/');
            let lowerBound = objectiveBounds[2 * i];
            let upperBound = objectiveBounds[2 * i + 1];

            let isMinimize = objectiveMinMax[i] === 'minimize';
            let minimizeSelected = isMinimize ? "selected='selected'" : "";
            let maximizeSelected = !isMinimize ? "selected='selected'" : "";

            let htmlNewRow = "<tr>";
            htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-name'>${nameParts[0]}</td>`;
            htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-unit'>${nameParts[1] || ''}</td>`;
            htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-lower-bound'>${lowerBound}</td>`;
            htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-upper-bound'>${upperBound}</td>`;
            htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-min-max'>
                            <select id='min-max-1' style='font-family: calibri; font-size: medium;'>
                                <option value='minimize' ${minimizeSelected}>minimize</option>
                                <option value='maximize' ${maximizeSelected}>maximize</option>
                            </select>
                        </td>`;
            htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>";
            htmlNewRow += "</tr>";

            $("#objective-table tbody").append(htmlNewRow);
            $(window.document).on('click', ".record-delete", deleteObjectiveTable);
        }
    }

    else{
            addExampleObjectivesTable();
            addExampleObjectivesTable();
        }


    function finishObjs() {

        // saveFormData();

        var noError = true;

        var objectiveNames = [];
        var objectiveBounds = [];
        var objectiveMinMax = [];


        // Find all the objective names and bounds
        var tableObjs = $("#objective-table tbody");
            
        tableObjs.find('tr').each(function() {
            var $objCols = $(this).find("td");
            var objRowEntries = [];

            $.each($objCols, function() {
                objRowEntries.push($(this).text());
            });
            
            var objName = objRowEntries[0];
            var unit = objRowEntries[1];
            if ((unit === "None") || (unit === "")) {
                objectiveNames.push(objName);
            }
            else {
                objectiveNames.push(objName+"/"+unit);

            }


            var objLowerBound = objRowEntries[2];
            var objUpperBound = objRowEntries[3];
            var validLowerBound = (!isNaN(parseFloat(objLowerBound)) && isFinite(objLowerBound));
            var validUpperBound = (!isNaN(parseFloat(objUpperBound)) && isFinite(objUpperBound));

            if (validLowerBound && validUpperBound){
                if (parseFloat(objLowerBound) < parseFloat(objUpperBound)){
                    var rowBounds = [parseFloat(objLowerBound), parseFloat(objUpperBound)];
                    objectiveBounds.push(rowBounds);
                }
                else {
                   noError = false;
                }
            }
            else {
                noError = false;
            }
        
            var selectedOption = $(this).find('select option:selected').text();
            objectiveMinMax.push(selectedOption);

        });



        // if (parameterBounds.length != parameterNames.length && parameterBounds.length <= 1){
        //     noError = false;
        // }

        if (objectiveBounds.length != objectiveNames.length){
            noError = false;
        }

        if (noError){

            localStorage.setItem("objective-names", objectiveNames);
            localStorage.setItem("objective-bounds", objectiveBounds);
            localStorage.setItem("objective-min-max", objectiveMinMax);

            $.ajax({
                        url: "confirm.php",
                        type: "post",
                        data: {
                        'objective-names'    :String(objectiveNames),
                        'objective-bounds'   :String(objectiveBounds),
                        'objective-min-max'   :String(objectiveMinMax)
                        },
                        beforeSend: function() {
                        // 显示 loading 动画和文字
                        $('#loadingContainer').show();
                        },
                        success: function(response) {
                            var url = "confirm.php";
                            window.location.href = url;
                        },
                        error: function(response) {
                            console.log("Error sending data to confirm.php");
                        }
                    });
                $('#loadingContainer').hide();
        }
        else {
            alert("Invalid entry");
        }    
    }


    function addDesignObjectivesTable(){
        var htmlNewRow = ""
        htmlNewRow += "<tr>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-name'></td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-unit'></td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-lower-bound'></td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'></td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'><select id='min-max-3' style='font-family: calibri; font-size: medium;'><option value='minimize' selected='selected'>minimize</option><option value='maximize'>maximize</option></select></td>"
        htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>"

        htmlNewRow += "</td></tr>"
        $("#objective-table", window.document).append(htmlNewRow);  
        $(window.document).on('click', ".record-delete", deleteObjectiveTable);
    }

    function addExampleObjectivesTable(){
        var htmlNewRow = ""
        htmlNewRow += "<tr>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-name'>Input objectives here</td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-unit'></td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-lower-bound'>Input number</td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'>Input number</td>"
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'><select id='min-max-3' style='font-family: calibri; font-size: medium;'><option value='minimize' selected='selected'>minimize</option><option value='maximize'>maximize</option></select></td>"
        htmlNewRow += "<td button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></td>"

        htmlNewRow += "</td></tr>"
        $("#objective-table", window.document).append(htmlNewRow);  
        $(window.document).on('click', ".record-delete", deleteObjectiveTable);
    }
    function deleteObjectiveTable(){
        $(this).parents('tr').remove();
    }
    // document.getElementById('objective-table').addEventListener('input', saveFormData);
    // document.getElementById('objective-table').addEventListener('change', saveFormData);
</script>

</body>
</html>
