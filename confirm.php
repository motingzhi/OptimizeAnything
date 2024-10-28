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
    $objectiveminmax = json_encode($_POST['objective-min-max']);
    $datetime = new DateTime("now", new DateTimeZone("Europe/Helsinki"));
    $objective_timestamp = json_encode($datetime->format("Y-m-d H:i:s")); // 格式化时间戳为字符串


    $stmt = $conn->prepare("UPDATE data SET objectivename = ?, objectivebounds = ?, objectiveminmax = ?, objective_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $objectiveNames, $objectiveBounds,  $objectiveminmax, $objective_timestamp, $userID);
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    // $conn->close();
}

// // 查询当前用户的自增 ID 以及 randomizerstatus 和 ismanual
// $current_user_query = $conn->prepare("SELECT ID, randomizerstatus, ismanual FROM data WHERE prolific_ID = ?");
// if ($current_user_query === false) {
//     die("Prepare failed: " . $conn->error);
// }
// $current_user_query->bind_param("s", $userID);
// $current_user_query->execute();
// $current_user_result = $current_user_query->get_result()->fetch_assoc();

// if (!$current_user_result) {
//     die("No user found with ProlificID: " . $userID);
// }

// $current_user_id = $current_user_result['ID'];

// // 查询前一个用户的数据（ID-1）
// $previous_user_id = $current_user_id - 1;
// $previous_user_query = $conn->prepare("SELECT randomizerstatus, ismanual FROM data WHERE ID = ?");
// if ($previous_user_query === false) {
//     die("Prepare failed: " . $conn->error);
// }
// $previous_user_query->bind_param("i", $previous_user_id);
// $previous_user_query->execute();
// $previous_user_result = $previous_user_query->get_result()->fetch_assoc();

// // 通用处理函数，尝试将值转换为整数或设置为 null
// function processField($value) {
//     if (isset($value)) {
//         // 尝试将字段从 JSON 解析出来
//         $decoded_value = json_decode($value, true);
        
//         // 如果解析成功且非空，使用解析后的值
//         if ($decoded_value !== null && $decoded_value !== '') {
//             $value = $decoded_value;
//         }
        
//         // 尝试将最终的值转换为整数
//         if (is_numeric($value)) {
//             return (int)$value;
//         } else {
//             return null;
//         }
//     } else {
//         return null;
//     }
// }

// // 处理 previousUser 的 randomizerstatus 和 ismanual
// $previous_user_result['randomizerstatus'] = processField($previous_user_result['randomizerstatus']);
// $previous_user_result['ismanual'] = processField($previous_user_result['ismanual']);

// // 将前一个用户和当前用户的数据传递给 JavaScript
// echo "<script>
//     var previousUser = " . json_encode($previous_user_result) . ";
//     var currentUser = " . json_encode($current_user_result) . ";
//     var currentUserId = " . json_encode($current_user_id) . ";
// </script>";

// $current_user_query->close();
// $previous_user_query->close();

$conn->close(); // 现在关闭数据库连接

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
            margin: 10px;
            display: inline-block;
            width: 60%;
        }
        .custom-card .card-body {
            padding: 10px;
            text-align: left;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 900px;
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
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 60%;
            overflow: visible;
            border: 1px solid #000000;
            border-radius: 8px;
        }
        .column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        /* .variable, .objective, .to-objective {
            display: block;
            width: 100%;
            margin: 10px 0;
            transition: background-color 0.3s;
        } */
        .variable, .to-objective {
            display: block;
            width: 100%;
            margin: 10px 0;
            transition: background-color 0.3s;
        }

        .objective {
            display: flex;
            align-items: center;
            margin-left: 30px; /* 增加文本和 objective 按钮之间的间距 */
        }

        .objective input[type="radio"] {
            transform: scale(1.5); /* 将单选按钮放大1.5倍 */
            margin-right: 10px; /* 调整与文本的间距 */
        }
        .objective-btn {
            transition: background-color 0.3s, color 0.3s;
        }


        .plus-sign {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
            color: black;
        }
        .selected {
            background-color: white !important;
            color: black !important;
        }
        .title {
            margin-bottom: 20px;
            font-weight: bold;
        }
        .to-objective {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: gray;
            color: white;
            cursor: default;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    <div class="top-bar">
    <nav class="nav">
                <a class="nav-link active" href="#">1. Specify</a>
                <a class="nav-link" href="#">2. Optimize</a>
                <a class="nav-link" href="#">3. Get results</a>
            </nav>
    </div>

    <div class="centered-content">
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
        <div class="container">
            <div class="card custom-card">
            <p class="text-primary"> Your optimization task:</p>
                <div class="card-body">
                        <label > 
                        Imagine you have decided to optimize your diet plan. You want to choose an optimal diet that loses weight but stays healthy at the same time, or a diet that meets your other requirements. What kind of variables and objectives would you specify?
                        </label></br>
                </div>
            </div>
        </div>

        <!-- <div class="container">
            <div class="card custom-card">
                <p class="text-primary">Your optimization task:</p>
                <div class="card-body">
                    <label id="taskDescription"></label></br>
                </div>
            </div>
        </div> -->

        <div class="container">
            <br>
            <p>Below is a summary model of your specifications. <br>You need to confirm your specifications to move to the optimize step. You can refer to bad examples listed below to check your specifications.</p>
            <p class="text-primary">Please notice: </p>
            <p class="text-primary">All specifications will be reviewed manually, specification with low quality will affect the acceptance of your study result. </p>

        </div>

        <div class="container">
    
            <div class="card custom-card">
                <p class="text-primary">Hints</p>
                <div class="card-body">
                            <label >1. The solution generated by AI will be composed of variables, and the value generated will be inside the minimum and maximum values you specified.</label></br>
                            <label >
                            2. Every objective is related to <strong>all</strong> variables, so please <strong>avoid</strong> inputting objectives that are only related to a single variable.</label></br>
                            <label >
                            3. Since the objectives are used to evaluate the solution,  please <strong>avoid</strong> inputting the same things to objectives as variables.  </label></br>
                </div> 
            </div>
            <div class="card custom-card">


            <p class="text-primary">Bad examples(Please try to avoid these two examples.)</p>
                <div class="card-body">
                            <label >
                            <label>1. Input the same for variables and objectives.  <br>2. Only use the first objective for evaluating the first variable, only use the second objective for evaluating the second variable, etc.
                            </label></br>
                              
                </div>
                </div>
        </div>
        <p class="text-primary" style="width: 50%; margin-left: 25%;">You need to click every objective to check and confirm all objectives and corresponding variables to avoid bad examples.</p>
        <div class="container2" id="container2">
            <div class="column" id="variables-column">
                <div class="title">You want AI to adjust variable(s) below</div>
                <div class="variables" id="variables">
                    <!-- Variables and plus signs will be inserted here -->
                </div>
            </div>
            <div class="column" id="to-objective-column">
                <div id="to-objective" class="to-objective">to minimize</div>
            </div>
            <canvas id="canvas" width="1000" height="600" style="position:absolute; top:0; left:0; pointer-events:none;"></canvas>
            <div class="column" id="objectives-column">
                <!-- <div class="title">Objective(s)</div> -->
                <div class="objectives" id="objectives">
                    <!-- Objectives will be inserted here -->
                </div>
            </div>
        </div>
    </div>



<div class="bottom-bar">
    <div class="row">
            <div class="col text-left">
            <button class="btn btn-outline-primary" id="back-button" onclick="goBack()" style="width: 30%;">Back</button>
            </div>
            <div class="col text-right">
            <!-- <button class="btn btn-primary" id="confirm-definitions-button" onclick="openSurvey()" style="width: 30%;">Confirm and open the mid-term questionnaire</button> -->

            <button class="btn btn-primary" id="confirm-definitions-button" onclick="confirmDefinitions()" style="width: 30%;">Confirm</button>
            </div>
        </div>
</div>
    
<div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
       

            const taskDescriptionLabel = document.getElementById('taskDescription');

            // 从 local storage 获取 marathonPlanInput 的内容
            window.onload = function() {
                const marathonPlanContent = localStorage.getItem('marathonPlan');
                if (marathonPlanContent) {
                    taskDescriptionLabel.textContent = marathonPlanContent;
                } else {
                    taskDescriptionLabel.textContent = "No task specified.";
                }
            };
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

            let selectedObjectiveIndex = 0;  // Default selected objective
    let clickedObjectives = new Set(); // 存储用户点击过的objective索引

    function drawArrow(ctx, fromX, fromY, toX, toY) {
        const headlen = 10; // length of head in pixels
        const angle = Math.atan2(toY - fromY, toX - fromX);

        ctx.moveTo(fromX, fromY);
        ctx.lineTo(toX, toY);
        ctx.lineTo(toX - headlen * Math.cos(angle - Math.PI / 6), toY - headlen * Math.sin(angle - Math.PI / 6));
        ctx.moveTo(toX, toY);
        ctx.lineTo(toX - headlen * Math.cos(angle + Math.PI / 6), toY - headlen * Math.sin(angle + Math.PI / 6));
        ctx.stroke();
    }

    function drawLines() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const toObjectiveElement = document.getElementById('to-objective');
        const variablesElements = document.querySelectorAll('.variables .to-objective');
        const selectedRadio = document.querySelectorAll('input[name="objectiveRadio"]')[selectedObjectiveIndex];
        const objectiveElement = selectedRadio.closest('.objective');

        const toObjectiveRect = toObjectiveElement.getBoundingClientRect();
        const containerRect = document.getElementById('container2').getBoundingClientRect();

        variablesElements.forEach(variableElement => {
            const variableRect = variableElement.getBoundingClientRect();
            const fromX = variableRect.right - containerRect.left;
            const fromY = variableRect.top + variableRect.height / 2 - containerRect.top;
            const toX = toObjectiveRect.left - containerRect.left;
            const toY = toObjectiveRect.top + toObjectiveRect.height / 2 - containerRect.top;
            ctx.beginPath();
            drawArrow(ctx, fromX, fromY, toX, toY);
        });

        const fromX2 = toObjectiveRect.right - containerRect.left;
        const fromY2 = toObjectiveRect.top + toObjectiveRect.height / 2 - containerRect.top;
        const toX2 = objectiveElement.getBoundingClientRect().left - containerRect.left;
        const toY2 = objectiveElement.getBoundingClientRect().top + objectiveElement.getBoundingClientRect().height / 2 - containerRect.top;
        ctx.beginPath();
        drawArrow(ctx, fromX2, fromY2, toX2, toY2);
    }

    function updateSelectedObjective(index) {
        selectedObjectiveIndex = index;
        clickedObjectives.add(index); // 记录用户点击过的objective

        const toObjectiveText = `to ${objectiveMinMax[index]}`;
        document.getElementById('to-objective').textContent = toObjectiveText;

        const selectedVariables = parameterNames.join(' and ');

        const objectiveText = `You want to evaluate solutions constructed by different quantities of ${selectedVariables} using ${objectiveNames[index]}`;

        // 调整列宽度，并确保间隙足够大
        document.getElementById('variables-column').style.flex = '1 1 50%';
        document.getElementById('to-objective-column').style.flex = '1 1 20%';
        document.getElementById('objectives-column').style.flex = '1 1 45%';

        // 在切换 objective 时更新显示的文本，并隐藏其他文本
        document.querySelectorAll('.objective-text').forEach(textElement => {
            textElement.style.display = 'none'; // 隐藏所有文本
        });

        const objectiveContainer = document.querySelectorAll('.objective')[index];
        let existingText = objectiveContainer.querySelector('.objective-text');
        if (!existingText) {
            existingText = document.createElement('div');
            existingText.className = 'objective-text';
            objectiveContainer.appendChild(existingText);
        }
        // existingText.style.display = 'inline-block';
        // existingText.style.width = '30%'; // 30% of container2 width
        // existingText.textContent = objectiveText;

        // Update button and radio styles
        document.querySelectorAll('.objective-btn').forEach((btn, i) => {
            const radio = btn.previousElementSibling;
            if (i === index) {
                btn.classList.add('selected');
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
                radio.checked = true;
            } else {
                btn.classList.remove('selected');
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
                radio.checked = false;
            }
        });

        drawLines();
    }

    function populateFields() {
        const variablesContainer = document.getElementById('variables');
        parameterNames.forEach((variable, index) => {
            const div = document.createElement('div');
            div.className = 'to-objective';
            div.textContent = variable;
            variablesContainer.appendChild(div);

            if (index < parameterNames.length - 1) {
                const plusSign = document.createElement('div');
                plusSign.className = 'plus-sign';
                plusSign.textContent = 'and';
                variablesContainer.appendChild(plusSign);
            }
        });

        const objectivesContainer = document.getElementById('objectives');
        objectiveNames.forEach((objective, index) => {
            const div = document.createElement('div');
            div.className = 'objective';

            const button = document.createElement('button');
            button.className = 'btn btn-secondary objective-btn';
            button.textContent = objective;
            button.onclick = () => updateSelectedObjective(index);

            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'objectiveRadio';
            radio.value = index;
            radio.style.marginRight = '10px';
            radio.checked = (index === selectedObjectiveIndex);
            radio.onclick = () => updateSelectedObjective(index);

            div.appendChild(radio);
            div.appendChild(button);
            objectivesContainer.appendChild(div);
        });

        updateSelectedObjective(selectedObjectiveIndex);
    }

    window.addEventListener('resize', drawLines);
    document.addEventListener('DOMContentLoaded', populateFields);

    function openSurvey() {
        const totalObjectives = document.querySelectorAll('.objective-btn').length;

                if (clickedObjectives.size < totalObjectives) {
                    alert('You need to click every objective to confirm rational specification');
                    return;
                }

                const surveyUrl = "https://link.webropolsurveys.com/S/03AB1318B5A6F07E";
                window.open(surveyUrl, "_blank");
}


            function confirmDefinitions() {
            // localStorage.setItem("parameter-names", parameterNames);
            // localStorage.setItem("parameter-bounds", parameterBounds);
            // localStorage.setItem("objective-names", objectiveNames);
            // localStorage.setItem("objective-bounds", objectiveBounds);
            // localStorage.setItem("objective-min-max", objectiveMinMax);
                const totalObjectives = document.querySelectorAll('.objective-btn').length;
                if (clickedObjectives.size < totalObjectives) {
                    alert('You need to click every objective to confirm that you think specifications are reasonable');
                    return;
                }



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
                url: "./cgi/newSolution_confirm.py",
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
                    // submitReturned = true;

                //     var ismanual = localStorage.getItem("ismanual");
                //     if (ismanual == 1){
                //    // 如果是manual的话以下行要启用。
                //     var url = "optimise_manual.php";
                //     solution = [];
                //     }  else {
                //         solution = result.solution;//如果是manual的话这行要注释掉。
                //         // var url = "optimise_withnewsolution.php";
                //         var url = "optimise_withnewsolution_fastapi.php";//fastapi。
                //     }                  




                    
                    objectivesInput = result.objectives;
                    savedSolutions = result.saved_solutions;
                    savedObjectives = result.saved_objectives;
                    solution = result.solution;
                    localStorage.setItem("objectives-input", objectivesInput);
                    localStorage.setItem("saved-solutions", savedSolutions);
                    localStorage.setItem("saved-objectives", savedObjectives);
                    //向下一个页面传数据
                    // $.ajax({
                    //         url: "optimise_withnewsolution.php",
                    //         type: "post",
                    //         data: {
                    //         'objective-names'    :String(objectiveNames),
                    //         'objective-bounds'   :String(objectiveBounds)
                    //         },
                    //         success: function(response) {
                    //             var url = "optimise_withnewsolution.php";
                    //         },
                    //         error: function(response) {
                    //             console.log("Error sending data to define-2.php");
                    //         }
                    //     });
                    console.log("Success");
                    console.log(result.success)
                    console.log("result.parameterNames.length");
                    console.log(result.parameterNames.length)
                    console.log("result.parameterBounds.length");
                    // console.log(result.parameterBounds.length)
                    console.log(result.objectiveNames)
                    console.log(result.objectiveBounds)

  
                    
                    //利用PHP来计算 assigned be an optimizer
                    //php 新添状态栏 status check

                
                    // if the id -1 randomizerstatus = 1://看前一个id的randomizerstatus是s1
                    //     if id - 1 ismanual = 1 
                    //         var ismanual = 0;

                    //     else:
                    //         var ismanual = 1;
                    //     localStorage.setItem("ismanual", ismanual);
                    //     ismanual 用ajax传入php，本id的ismanual值更新到mysql。
                    //     set id status = 2.
                    // if the id - 1 status  = 2 or ""://看前一个id的randomizerstatus是不是s2，以及这个ID是不是初始randomizerstatus。
                    //     var ismanual = randomizer(0 or 1) ;
                    //     localStorage.setItem("ismanual", ismanual);
                    //     ismanual 用ajax传入php，本id的ismanual值更新到mysql。
                    //     set id status = 1.
        

                        // JavaScript 逻辑
                    // var randomizerstatus, ismanual, url;   
                    // if (previousUser.randomizerstatus == '1') {
                    //     if (previousUser.ismanual == '1') {
                    //         ismanual = 0; // 设置本用户的ismanual为0
                    //     } else {
                    //         ismanual = 1; // 设置本用户的ismanual为1
                    //     }
                    //     randomizerstatus = 2;                   

                    // }
                    // // else if (previousUser.status == 2 || previousUser.randomizerstatus == "")
                    //  else  {
                    //     // 如果前一个用户的randomizerstatus是2或者是空
                    //     var ismanual = Math.random() < 0.5 ? 0 : 1; // 随机生成0或1
                    //     randomizerstatus = 1;
                    // }
                    ismanual = localStorage.getItem("ismanual");
                    if (ismanual == 1){
                            solution = [];
                            url = "optimise_manual.php";
                        } else {
                            url = "optimise_withnewsolution_fastapi.php";
                        }

                    // 将ismanual存储到localStorage
                    // localStorage.setItem("ismanual", ismanual);
                    // localStorage.setItem("randomizerstatus", randomizerstatus);
                    localStorage.setItem("solution-list", solution);

                    // 用AJAX将ismanual传递到PHP并更新数据库
                    // $.ajax({
                    //         url: url,
                    //         type: "post",
                    //         data: {
                    //             'ismanual': ismanual,
                    //             'randomizerstatus': randomizerstatus

                    //         },
                    //         success: function(response) {
         
                    //         },
                    //         error: function(response) {
                    //             console.log("Error sending data to fastapi.php");
                    //         }
                    //     });
                    window.location.href = url;







                    // const randomNumber = Math.random(); // 生成一个 0 到 1 之间的随机数
                    // if (randomNumber < 0.5) {
                    //     ismanual = 0;
                    //     localStorage.setItem("ismanual", ismanual);
                    //     localStorage.setItem("solution-list", solution);

                    //      $.ajax({
                    //         url: "optimise_withnewsolution_fastapi.php",
                    //         type: "post",
                    //         data: {
                    //         'ismanual'    :ismanual
                    //         },
                    //         success: function(response) {
                    //             var url  = "optimise_withnewsolution_fastapi.php"; // 跳转到页面fastapi
                    //             window.location.href = url;

                    //         },
                    //         error: function(response) {
                    //             console.log("Error sending data to fastapi.php");
                    //         }
                    //     });
                    // } else {
                    //     ismanual = 1;
                    //     localStorage.setItem("ismanual", ismanual);
                    //     solution = [];
                    //     localStorage.setItem("solution-list", solution);

                    //     $.ajax({
                    //         url: "optimise_manual.php",
                    //         type: "post",
                    //         data: {
                    //         'ismanual'    :ismanual
                    //         },
                    //         success: function(response) {
                    //             var url  = "optimise_manual.php"; // 跳转到页面manual
                    //             window.location.href = url;

                    //         },
                    //         error: function(response) {
                    //             console.log("Error sending data to fastapi.php");
                    //         }
                    //     });
                    // }

                    // location.href = url;
                    $('#loadingContainer').hide();
                },
                error: function(result){
                    console.log("Error");
                    $('#loadingContainer').hide();
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
