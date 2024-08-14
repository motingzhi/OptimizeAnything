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

    $objective_timestamp = json_encode(date("Y-m-d H:i:s")); // 将时间戳转换为JSON格式/ 格式化时间戳为字符串

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
            margin: 10px 0;
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
                <a class="nav-link active" href="#">Specify</a>
                <a class="nav-link" href="#">Optimize</a>
                <a class="nav-link" href="#">Get results</a>
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
                <p class="text-primary">Your optimization task:</p>
                <div class="card-body">
                    <label>Imagine you are a runner preparing for a marathon. You want to optimize your diet to lose weight and stay healthy at the same time. What variables and objectives will you specify here?</label></br>
                </div>
            </div>
        </div>

        <div class="container">
            <br>
            <p>Below is a summary model of your specifications. <br>If you think this specification seems irrational from the model, you can go back and modify it.</p>
            <p class="text-primary">Note: Irrational specification will result in inaccurate optimization.</p>
        </div>

        <div class="container">
            <div class="card custom-card">
                <p class="text-primary">Hints</p>
                <div class="card-body">
                            <label >1. The solution generated by AI will be constructed by <strong>Variables</strong> you specified here, and the value generated will be inside the minimum and maximum values you specified.</label></br>
                            <label >2. All changes in <strong>Variables</strong> are aimed at achieving the overall <strong>Objectives</strong>. </label></br>
                            <label >3. You will evaluate the solution generated by AI later based on the <strong>Objectives</strong> you specified.</label></br>
         
            </div>
            </div>
        </div>
        <p>You can click different <strong>objectives</strong> to check the correspondence relationship.</p>
        <div class="container2" id="container2">
            <div class="column" id="variables-column">
                <div class="title">You want to change variable(s) below</div>
                <div class="variables" id="variables">
                    <!-- Variables and plus signs will be inserted here -->
                </div>
            </div>
            <div class="column" id="to-objective-column">
                <div id="to-objective" class="to-objective">to minimize</div>
            </div>
            <canvas id="canvas" width="1000" height="600" style="position:absolute; top:0; left:0; pointer-events:none;"></canvas>
            <div class="column" id="objectives-column">
                <div class="title">Objective(s)</div>
                <div class="objectives" id="objectives">
                    <!-- Objectives will be inserted here -->
                </div>
            </div>
        </div>
    </div>



<div class="bottom-bar">
    <div class="row">
            <div class="col text-left">
            <button class="btn btn-outline-primary" id="back-button" onclick="goBack()" style="width: 20%;">Back</button>
            </div>
            <div class="col text-right">
            <button class="btn btn-primary" id="confirm-definitions-button" onclick="confirmDefinitions()" style="width: 20%;">Confirm</button>
            </div>
        </div>
</div>
    
<div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
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

            let selectedObjectiveIndex = 0;  // Default selected objective
            
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

            // function drawLines() {
            //     const canvas = document.getElementById('canvas');
            //     const ctx = canvas.getContext('2d');
            //     ctx.clearRect(0, 0, canvas.width, canvas.height);

            //     const toObjectiveElement = document.getElementById('to-objective');
            //     const variablesElements = document.querySelectorAll('.variables .to-objective'); // 修改选择器
            //     const objectiveElement = document.querySelectorAll('.objective')[selectedObjectiveIndex];

            //     const toObjectiveRect = toObjectiveElement.getBoundingClientRect();
            //     const containerRect = document.getElementById('container2').getBoundingClientRect();

            //     variablesElements.forEach(variableElement => {
            //         const variableRect = variableElement.getBoundingClientRect();
            //         const fromX = variableRect.right - containerRect.left;
            //         const fromY = variableRect.top + variableRect.height / 2 - containerRect.top;
            //         const toX = toObjectiveRect.left - containerRect.left;
            //         const toY = toObjectiveRect.top + toObjectiveRect.height / 2 - containerRect.top;
            //         ctx.beginPath();
            //         drawArrow(ctx, fromX, fromY, toX, toY);
            //         ctx.stroke();
            //     });

            //     const fromX2 = toObjectiveRect.right - containerRect.left;
            //     const fromY2 = toObjectiveRect.top + toObjectiveRect.height / 2 - containerRect.top;
            //     const toX2 = objectiveElement.getBoundingClientRect().left - containerRect.left;
            //     const toY2 = objectiveElement.getBoundingClientRect().top + objectiveElement.getBoundingClientRect().height / 2 - containerRect.top;
            //     ctx.beginPath();
            //     drawArrow(ctx, fromX2, fromY2, toX2, toY2);
            //     ctx.stroke();
            // }

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
                    ctx.stroke();
                });

                const fromX2 = toObjectiveRect.right - containerRect.left;
                const fromY2 = toObjectiveRect.top + toObjectiveRect.height / 2 - containerRect.top;
                const toX2 = objectiveElement.getBoundingClientRect().left - containerRect.left;
                const toY2 = objectiveElement.getBoundingClientRect().top + objectiveElement.getBoundingClientRect().height / 2 - containerRect.top;
                ctx.beginPath();
                drawArrow(ctx, fromX2, fromY2, toX2, toY2);
                ctx.stroke();
            }


            // function updateSelectedObjective(index) {
            //     selectedObjectiveIndex = index;
            //     document.getElementById('to-objective').textContent = `to ${objectiveMinMax[index]}`;
            //     document.querySelectorAll('.objective').forEach((el, i) => {
            //         if (i === index) {
            //             el.classList.add('selected');
            //         } else {
            //             el.classList.remove('selected');
            //         }
            //     });
            //     drawLines();
            // }

            function updateSelectedObjective(index) {
                selectedObjectiveIndex = index;
                document.getElementById('to-objective').textContent = `to ${objectiveMinMax[index]}`;

                // 更新按钮样式和单选按钮的选中状态
                document.querySelectorAll('.objective-btn').forEach((btn, i) => {
                    const radio = btn.previousElementSibling; // 获取对应的单选按钮
                    if (i === index) {
                        btn.classList.add('selected');
                        btn.classList.remove('btn-secondary');
                        btn.classList.add('btn-primary'); // 选中状态
                        radio.checked = true;
                    } else {
                        btn.classList.remove('selected');
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-secondary'); // 未选中状态
                        radio.checked = false;
                    }
                });

                drawLines();
            }

//             function populateFields() {
//     const variablesContainer = document.getElementById('variables');
//     parameterNames.forEach((variable, index) => {
//         const div = document.createElement('div');
//         div.className = 'to-objective';  // 使用与 to-objective 相同的样式
//         div.textContent = variable;
//         variablesContainer.appendChild(div);

//         if (index < parameterNames.length - 1) {
//             const plusSign = document.createElement('div');
//             plusSign.className = 'plus-sign';
//             plusSign.textContent = '+';
//             variablesContainer.appendChild(plusSign);
//         }
//     });

//     const objectivesContainer = document.getElementById('objectives');
//     objectiveNames.forEach((objective, index) => {
//         const button = document.createElement('button');
//         button.className = 'btn btn-secondary objective';
//         button.textContent = objective;
//         button.onclick = () => updateSelectedObjective(index);
//         objectivesContainer.appendChild(button);
//     });

//     // 默认选中第一个objective
//     updateSelectedObjective(selectedObjectiveIndex);
// }


            function populateFields() {
                const variablesContainer = document.getElementById('variables');
                parameterNames.forEach((variable, index) => {
                    const div = document.createElement('div');
                    div.className = 'to-objective';  // 使用与 to-objective 相同的样式
                    div.textContent = variable;
                    variablesContainer.appendChild(div);

                    if (index < parameterNames.length - 1) {
                        const plusSign = document.createElement('div');
                        plusSign.className = 'plus-sign';
                        plusSign.textContent = '+';
                        variablesContainer.appendChild(plusSign);
                    }
                });

                const objectivesContainer = document.getElementById('objectives');
                objectiveNames.forEach((objective, index) => {
                    const div = document.createElement('div');
                    div.className = 'objective';

                    // 创建按钮元素
                    const button = document.createElement('button');
                    button.className = 'btn btn-secondary objective-btn';
                    button.textContent = objective;
                    button.onclick = () => updateSelectedObjective(index);

                    // 创建单选按钮
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

                // 默认选中第一个objective
                updateSelectedObjective(selectedObjectiveIndex);
            }
                        

            window.addEventListener('resize', drawLines);
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
                    var url = "optimise_withnewsolution.php";
                    window.location.href = url;
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
                    //             window.location.href = url;
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
