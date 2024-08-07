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
    $objectiveNames = json_encode($_POST['objective-names']);
    $objectiveBounds = json_encode($_POST['objective-bounds']);
    $objective_timestamp = json_encode(date("Y-m-d H:i:s")); // 将时间戳转换为JSON格式/ 格式化时间戳为字符串

    $stmt = $conn->prepare("UPDATE data SET objectivename = ?, objectivebounds = ?, objective_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $objectiveNames, $objectiveBounds, $objective_timestamp, $userID);
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
            width: 50%; /* Content width as 1/3 of the page */
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

        .custom-card {
            margin: 10px; /* 外边距 */
            display: inline-block; /* 使卡片宽度根据内容自适应 */
            width: 60%;
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
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .container2 {
            align-items: center;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            display: flex;
            justify-content: space-between;
            width: 100%;
            height: 100px;
            overflow: visible;
        }
        .column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .variable, .objective {
            display: block;
            width: 100%;
            margin: 10px 0;
            transition: background-color 0.3s;
        }
        .selected {
            background-color: white !important;
            color: black !important;
        }
        .title {
            margin-bottom: 20px;
            font-weight: bold;
        }

    </style>
</head>
<body>
<div class="top-bar">
        <div class="container">

            <div class="stepper">
                    <div class="step">
                        <span>1</span>
                        <div>Specify Variables</div>
                    </div>
                    <div class="step">
                        <span>2</span>
                        <div>Specify Objectives</div>
                    </div>
                    <div class="step active">
                        <span>3</span>
                        <div>Confirm Specification</div>
                    </div>
            </div>
        </div>
</div>


<div class="centered-content">

        <div class="container">
            <div class="card custom-card">
            <p class="text-primary"> Your optimization task:</p>
                <div class="card-body">
                        <label > Imagine you have decided to eat more healthily. You want to choose a diet that is enjoyable, helps you lose weight, and keeps you healthy at the same time. What variables and objectives will you specify here?</label></br>
                </div>
            </div>
        </div>
        </br>
        <label style="margin-bottom: 20px;">This is a summary model of your specifications; you can click different objectives to check the correspondence relationship.</label></br>
        <label style="margin-bottom: 20px;">If you think this specification seems irrational from the model, you can go back and modify it.</label>

        <div class="container2" id="container2">
            <div class="column" id="variables-column">
                <div class="title">Your variables</div>
                <div class="variables" id="variables">
                    <!-- Variables will be inserted here -->
                </div>
            </div>
            <canvas id="canvas" width="800" height="600" style="position:absolute; top:0; left:0; pointer-events:none;"></canvas>
            <div class="column" id="objectives-column">
                <div class="title">Your objectives</div>
                <div class="objectives" id="objectives">
                    <!-- Objectives will be inserted here -->
                </div>
            </div>
        </div>


</div>

<div class="bottom-bar">
        <div class="d-flex justify-content-between">
        <button class="btn btn-outline-primary" id="back-button" onclick="goBack()" style="width: 20%;">Back</button>
        <button class="btn btn-primary" id="confirm-definitions-button" onclick="confirmDefinitions()" style="width: 20%;">Confirm</button>
        </div>
</div>
       

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
            var newSolution = true;
            var nextEvaluation = false;
            var refineSolution = false;
            var goodSolutions = [];
            var badSolutions = [];
            var solutionNameList =  "";

            var parameterNames = localStorage.getItem("parameter-names").split(",");
            var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
            var objectiveNames = localStorage.getItem("objective-names").split(",");
            var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
            var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");

            function goBack() {
                // saveFormData();
                location.href = "define-2.php";
            }


            console.log(parameterBounds);

            // parameterNames = document.getElementById('defineWhat').value;
            // objectiveNames = document.getElementById('defineGood').value;
            // objectiveMinMax = document.getElementById('defineFor').value;

            const capitalizedObjectiveMinMax = objectiveMinMax.map(obj => obj.charAt(0).toUpperCase() + obj.slice(1));
            const combinedList = capitalizedObjectiveMinMax.map((obj, index) => obj + ' ' + objectiveNames[index]);



            // for (let i = 0; i < objectiveNames.length; i++) {
            //     let nameParts = objectiveNames[i];
            //     let minmax = objectiveMinMax[i];
                
            //     let htmlNewRow = "<tr>";
            //     htmlNewRow += `<td contenteditable='true' class='record-data' id='record-objective-lower-bound'>${minmax}</td>`;
            //     htmlNewRow += `<td contenteditable='false' class='record-data' id='record-objective-name'>${nameParts}</td>`;
            //     htmlNewRow += "</td></tr>";

            //     $("#objective-table tbody").append(htmlNewRow);

            // }

            // for (let i = 0; i < parameterNames.length; i++) {
            //         let nameParts = parameterNames[i];
                      
            //         let htmlNewRow = "<tr>";
            //         htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-name'>${nameParts}</td>`;
            //         htmlNewRow += "</td></tr>";

            //         $("#parameter-table tbody").append(htmlNewRow);

            // }


            function drawArrow(ctx, fromX, fromY, toX, toY) {
                const headlen = 10; // length of head in pixels
                const angle = Math.atan2(toY - fromY, toX - fromX);

                // Calculate midpoint
                const midX = (fromX + toX) / 2;
                const midY = (fromY + toY) / 2;

                // Draw line from start to midpoint
                ctx.moveTo(fromX, fromY);
                ctx.lineTo(midX, midY);

                // Draw arrow at midpoint
                ctx.lineTo(midX - headlen * Math.cos(angle - Math.PI / 6), midY - headlen * Math.sin(angle - Math.PI / 6));
                ctx.moveTo(midX, midY);
                ctx.lineTo(midX - headlen * Math.cos(angle + Math.PI / 6), midY - headlen * Math.sin(angle + Math.PI / 6));

                // Continue line from midpoint to end
                ctx.moveTo(midX, midY);
                ctx.lineTo(toX, toY);
            }

            function drawLines(objectiveIndex) {
                const canvas = document.getElementById('canvas');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const objectiveElement = document.querySelectorAll('.objective')[objectiveIndex];
                const variablesElements = document.querySelectorAll('.variable');
                const objectiveRect = objectiveElement.getBoundingClientRect();
                const containerRect = document.getElementById('container2').getBoundingClientRect();

                variablesElements.forEach(variableElement => {
                    const variableRect = variableElement.getBoundingClientRect();
                    const fromX = variableRect.right - containerRect.left;
                    const fromY = variableRect.top + variableRect.height / 2 - containerRect.top;
                    const toX = objectiveRect.left - containerRect.left;
                    const toY = objectiveRect.top + objectiveRect.height / 2 - containerRect.top;
                    ctx.beginPath();
                    drawArrow(ctx, fromX, fromY, toX, toY);
                    ctx.stroke();
                });
            }

            function updateSelectedObjective(index) {
                document.querySelectorAll('.objective').forEach((el, i) => {
                    if (i === index) {
                        el.classList.add('selected');
                    } else {
                        el.classList.remove('selected');
                    }
                });
                drawLines(index);
            }

            function populateFields() {
                const variablesContainer = document.getElementById('variables');
                parameterNames.forEach(variable => {
                    const button = document.createElement('button');
                    button.className = 'btn btn-secondary variable';
                    button.textContent = variable;
                    variablesContainer.appendChild(button);
                });

                const objectivesContainer = document.getElementById('objectives');
                combinedList.forEach((objective, index) => {
                    const button = document.createElement('button');
                    button.className = 'btn btn-secondary objective';
                    button.textContent = objective;
                    button.onclick = () => updateSelectedObjective(index);
                    objectivesContainer.appendChild(button);
                });
                updateSelectedObjective(0);
            }

            document.addEventListener('DOMContentLoaded', populateFields);



            function confirmDefinitions() {
            // localStorage.setItem("parameter-names", parameterNames);
            // localStorage.setItem("parameter-bounds", parameterBounds);
            // localStorage.setItem("objective-names", objectiveNames);
            // localStorage.setItem("objective-bounds", objectiveBounds);
            // localStorage.setItem("objective-min-max", objectiveMinMax);




                localStorage.setItem("objective-names", objectiveNames);
                localStorage.setItem("objective-bounds", objectiveBounds);
                localStorage.setItem("objective-min-max", objectiveMinMax);
                localStorage.setItem("good-solutions", goodSolutions);
                localStorage.setItem("new-solution", newSolution);
                localStorage.setItem("next-evaluation", nextEvaluation);
                localStorage.setItem("refine-solution", refineSolution);
                localStorage.setItem("solution-name-list", solutionNameList);
                localStorage.setItem("bad-solutions", badSolutions);


                $.ajax({
                url: "./cgi/newSolution_u_copy.py",
                type: "post",
                datatype: "json",
                data: { 
                        'parameter-names'    :String(parameterNames),
                        'parameter-bounds'   :String(parameterBounds),
                        'objective-names'    :String(objectiveNames), 
                        'objective-bounds'   :String(objectiveBounds),
                        'objective-min-max'  :String(objectiveMinMax),
                        'good-solutions'     :String(goodSolutions),
                        'bad-solutions'      :String(badSolutions),
                        'new-solution'       :String(newSolution),
                        'next-evaluation'    :String(nextEvaluation),
                        'solution-name-list'      :String(solutionNameList),
                        'refine-solution'    :String(refineSolution),
                    },
                beforeSend: function() {
                // 显示 loading 动画和文字
                $('#loadingContainer').show();
                },
                success: function(result) {
                    // var progressBar = $('#progressBar');
                    // progressBar.empty();                    
                    // submitReturned = true;
                    submitReturned = true;
                    solution = result.solution;
                    objectivesInput = result.objectives;
                    savedSolutions = result.saved_solutions;
                    savedObjectives = result.saved_objectives;
                    localStorage.setItem("solution-list", solution);
                    localStorage.setItem("objectives-input", objectivesInput);
                    localStorage.setItem("saved-solutions", savedSolutions);
                    localStorage.setItem("saved-objectives", savedObjectives);
                    //向下一个页面传数据
                    $.ajax({
                            url: "optimise_withnewsolution.php",
                            type: "post",
                            data: {
                            'objective-names'    :String(objectiveNames),
                            'objective-bounds'   :String(objectiveBounds)
                            },
                            success: function(response) {
                                var url = "optimise_withnewsolution.php";
                                window.location.href = url;
                            },
                            error: function(response) {
                                console.log("Error sending data to define-2.php");
                            }
                        });
                    console.log("Success");
                    console.log(result.success)
                    console.log("result.parameterNames.length");
                    console.log(result.parameterNames.length)
                    console.log("result.parameterBounds.length");
                    // console.log(result.parameterBounds.length)
                    console.log(result.objectiveNames)
                    console.log(result.objectiveBounds)
                    //[Log] ["Cost ($)", "Satisfaction (%)", "Goal"] (3) (define.php, line 268)
                    //[Log] ["100", "1000", "0", "100", "50", "600"] (6) (define.php, line 269)
                    // var url = "optimise_withnewsolution.php";
                    // location.href = url;
                    $('#loadingContainer').hide();
                },
                error: function(result){
                    console.log("Error");
                }
                // complete: function() {
                // // 隐藏 loading 动画和文字
                
                // }

   
                // complete: function() {
                // // 隐藏 loading 动画和文字
                
                // }

                });
        }  

   
</script>
</body>
</html>
