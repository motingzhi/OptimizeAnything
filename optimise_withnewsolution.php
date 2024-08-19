<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}

// $userID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $objectiveNames = json_encode($_POST['objective-names']);
//     $objectiveBounds = json_encode($_POST['objective-bounds']);
//     $objective_timestamp = json_encode(date("Y-m-d H:i:s")); // 将时间戳转换为JSON格式/ 格式化时间戳为字符串

//     $stmt = $conn->prepare("UPDATE data SET objectivename = ?, objectivebounds = ?, objective_timestamp = ? WHERE prolific_ID = ?");
//     if ($stmt === false) {
//         die("Prepare failed: " . $conn->error);
//     }

//     $stmt->bind_param("ssss", $objectiveNames, $objectiveBounds, $objective_timestamp, $userID);
//     if ($stmt->execute()) {
//         echo "Record updated successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     $stmt->close();
//     $conn->close();
// }
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
        .top-bar .nav-link.passed {
            color: white;
            background-color: #82AAF2;
        }

        .centered-content {
                overflow-y: auto; /* 添加垂直滚动条 */
                max-height: calc(100vh - 100px); /* 计算中间内容的最大高度减去top-bar和bottom-bar的高度 */
                margin-top: calc(100vh / 6); /* Offset by the height of top-bar */
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


        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 900px;
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
        .custom-card .card-body2 {
            padding: 10px; /* 内边距 */
            text-align: center;

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

        #colorBlock {
            width: 100px;
            height: 100px;
        }

        /* for ui optimization */
        #customButton {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #000;
            padding: 10px;
            margin: 20px;
            box-sizing: border-box;
            position: relative;
        }

        .checkmark {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: calc(var(--checkmark-size, 30px) / 2 + var(--confirm-font-size, 16px) / 4);
        }

        #confirmText {
            font-size: var(--confirm-font-size, 16px);
            font-family: inter;
        }
    </style>
	
</head>
<body>
<div id="background">
<div class="top-bar">
            <nav class="nav">
                <a class="nav-link passed " href="#">1. Specify</a>
                <a class="nav-link active" href="#">2. Optimize</a>
                <a class="nav-link" href="#">3. Get results</a>
            </nav>
</div>

<div class="centered-content">
    <div class="container">
    <br>
        <label><strong>Optimize</strong> </label>
        <br>
            <div class="card custom-card">
                <p class="text-primary">Hints</p>
                <div class="card-body">
                    <div id="RequirementDisplay"></div>
         
            </div>
            </div>


    </div>
    <div id="dataDisplay"></div>

    <div class="container">
        <div class="card custom-card">
            <div class="card-body2">
                <p class="card-title">New Solution</p>
                <!-- <div id="colorBlock"></div> -->

                <!-- <div id="customButton"> -->
                <!-- <div class="checkmark">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 50 L40 80 L90 20" stroke="black" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                        <div id="confirmText">Confirm</div>
                    </div> -->
                <!-- <div id="colorBlock"></div> -->

                <ul id="generatedSolution" class="list-unstyled">
                </ul>
            </div>
        </div>
    </div>
<!-- 
    <h5><b>New Alternative</b></h5>
    <ul id="generatedSolution" style="list-style-type: none;"></ul> -->

    <!-- 自己改的 -->


    
    <div id="options" style="display: inline-block; margin: 0 auto;">
        <button class="btn btn-primary" id="evaluate-button" style="width: 40%;" onclick="evaluateSolution()">I want to evaluate this</button>
        <button class="btn btn-outline-primary" id="skip-button" style="width: 40%;" onclick="newSolution()">Skip. I know it's not good</button>
    </div>

    <br>
    <div class="container">
        <div id="evaluate-solution" style="display: none;">
            <label for="solution_name">Name the solution </label><br>
            <input size="40" id = "solution_name" placeholder="Give a memorable name to this idea"><br><br>

            <label for="solution_name">Enter your measurements</label><br>
            <table class="table table-bordered" id="measurement-table" class="measurement-table" width="100%">
                <thead>  
                    <tr>  
                    <th id="record-objective-name" width="40%">Objective</th> 
                    <th id="record-objective-range" width="40%">Ranges of your objective </th>     
                    <th id="record-objective-measurement" width="40%"> Enter measurements </th>    
                    </tr>  
                </thead>  
                <tbody>
                </tbody>
            </table>
        </div>

        <div id="form-options-1"  style="width: 60%; margin: 0 auto;">
            <button class="btn btn-primary mr-3" id="next-button" style="margin-right: 10px;" onclick="nextEvaluation()">Save and let AI suggest me more solutions</button>
            <button class="btn btn-outline-primary" id="refine-button" onclick="refineSolution()">Save and I want to explore solutions closer to this</button>
        </div>

        
        <div id="form-options-2" style="display: inline-block; margin: 0 auto;">
                <button class="btn btn-success" id="next-button" onclick="nextEvaluation2()">Submit</button>
        </div>

        <div id="form-options-3" style="display: inline-block; margin: 0 auto;">
                <button class="btn btn-success" id="next-button" onclick="nextEvaluation()">Submit</button>
        </div>

        <br>
        <div id="done-button" class="btn btn-success" style="text-align: right;">
            <button class="btn btn-success" id="done" onclick="finishSolutions()">I'm done</button>    
        </div>
    </div>




    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>

 
</div>


    <script>
        var userID = '<?php echo $userID; ?>';

        var parameterNames = localStorage.getItem("parameter-names").split(",");
        var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
        var objectiveNames = localStorage.getItem("objective-names").split(",");
        var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
        var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");
        var goodSolutions = localStorage.getItem("good-solutions").split(",");
        var badSolutions = localStorage.getItem("bad-solutions").split(",");
        var solutionList = localStorage.getItem("solution-list").split(",");
        var savedSolutions = localStorage.getItem("saved-solutions").split(",");
        var savedObjectives = localStorage.getItem("saved-objectives").split(",");
        
        var saved_timestamp = [];
        localStorage.setItem("saved_timestamp", saved_timestamp);

        try {
        var objectiveMeasurements = localStorage.getItem("objective-Measurements").split(",");
        } catch (err) {
        // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
        var objectiveMeasurements = [];
        }
        // 现在，objectiveMeasurements 包含 localStorage 中 "saved-objectives" 的值，如果不存在则为一个空数组


        
        var num_parameters = parameterNames.length;

        try {var objectivesInput = localStorage.getItem("objectives-input").split(",");}
        catch(err) {}

        try {var solutionNameList = localStorage.getItem("solution-name-list").split(",");}
        catch(err) {}

        console.log("parameterNames",parameterNames)
        console.log("parameterBounds",parameterBounds)
        console.log("objectiveNames",objectiveNames)
        console.log("objectiveBounds",objectiveBounds)
        console.log("objectiveMinMax",objectiveMinMax)

        console.log("badSolutions",badSolutions)
        console.log("goodSolutions",goodSolutions)
        console.log("solutionList",solutionList)
        console.log("savedSolutions",savedSolutions)
        console.log("savedObjectives",savedObjectives)
        console.log("objectivesInput",objectivesInput)
        // catch(err) {}

        
	var DisplaySolutionText = 0;

	
	var displayDiv = document.getElementById("dataDisplay");
        displayDiv.innerHTML =  "<br>" + "You have evaluated  <strong>" + parseInt(savedSolutions.length/parameterNames.length) + " solutions.</strong>" + "<br><br>";

    var RequirementDisplay = document.getElementById("RequirementDisplay");
    RequirementDisplay.innerHTML =  "1. Let AI suggest solutions of variables with you. Please evaluate at least <strong>" + parseInt(2*(parameterNames.length+1)) + " solutions</strong> to proceed." + "<br>" + "2. Then, after several more evaluations, you will see the Done button, you can choose to continue until you find an optimal solution, or directly finish." + "<br>";


    if (savedSolutions.length/parameterNames.length < 2*(parameterNames.length+1)-1)
    {
        var x = document.getElementById('evaluate-solution');
        var y = document.getElementById('options');
        var z = document.getElementById('form-options-1');
        var z2 = document.getElementById('form-options-2');
        var z3 = document.getElementById('form-options-3');

            if (x.style.display == 'none') {
                x.style.display = 'block'
                z2.style.display = 'block'

                y.style.display = 'none'
                z.style.display = 'none'
                z3.style.display = 'none'

            }
            else {
                x.style.display = 'none'
                z2.style.display = 'none'
                z3.style.display = 'none'

                y.style.display = 'inline-block'
                z.style.display = 'block'
            }
        
           for (i = 0; i < objectiveNames.length; i++) {
                var htmlNewRow = "" 
                htmlNewRow += "<tr>" 
                htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>" 
                htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "(" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>" // placeholder的效果怎么做
                htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
                htmlNewRow += "</td></tr>"
                $("#measurement-table", window.document).append(htmlNewRow);
            }
    }
    
    if (savedSolutions.length/parameterNames.length > 2*(parameterNames.length+1)-1) {

     

        var x = document.getElementById('evaluate-solution');
        var y = document.getElementById('options');
        var z = document.getElementById('form-options-1');
        var z2 = document.getElementById('form-options-2');
        var z3 = document.getElementById('form-options-3');

        x.style.display = 'none';
        z.style.display = 'none';
        z2.style.display = 'none';
        z3.style.display = 'none';


    }

    if (savedSolutions.length/parameterNames.length == 2*(parameterNames.length+1)-1) {

        var x = document.getElementById('evaluate-solution');
        var y = document.getElementById('options');
        var z = document.getElementById('form-options-1');
        var z2 = document.getElementById('form-options-2');
        var z3 = document.getElementById('form-options-3');

        if (x.style.display == 'none') {
                x.style.display = 'block'
                z3.style.display = 'block'

                y.style.display = 'none'
                z.style.display = 'none'
                z2.style.display = 'none'

            }
            else {
                x.style.display = 'none'
                z2.style.display = 'none'
                z3.style.display = 'none'

                y.style.display = 'inline-block'
                z.style.display = 'block'
            }
        
           for (i = 0; i < objectiveNames.length; i++) {
                var htmlNewRow = "" 
                htmlNewRow += "<tr>" 
                htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>" 
                htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>" // placeholder的效果怎么做
                htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
                htmlNewRow += "</td></tr>"
                $("#measurement-table", window.document).append(htmlNewRow);
            }

    }



      var solutionsevaulted = parseInt(savedSolutions.length/parameterNames.length);

        
      var generatedSolution = [];
      


// 使用apply方法将颜色数组应用到色块
function setColor(r, g, b) {
    const colorBlock = document.getElementById('colorBlock');
    colorBlock.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;
}      
function setButtonProperties(sideMargin, confirmFontSize, buttonRadius, checkmarkSize, checkmarkMargin, checkmarkWeight) {
    const button = document.getElementById('customButton');
    const checkmark = document.querySelector('.checkmark');
    const confirmText = document.getElementById('confirmText');

    confirmText.style.fontSize = confirmFontSize + 'px';
        // Ensure the text is measured after setting the font size
    // confirmText.style.display = 'inline-block';
    // const confirmTextWidth = confirmText.offsetWidth;
    // confirmText.style.display = '';

    const confirmTextWidth = confirmText.offsetWidth;
    const totalContentWidth = checkmark.offsetWidth + parseInt(checkmarkMargin) + confirmTextWidth;
    const buttonWidth = totalContentWidth + 2 * sideMargin;

    button.style.width = buttonWidth + 'px';
    button.style.borderRadius = buttonRadius + 'px';


    
    const svg = checkmark.querySelector('svg');
    svg.setAttribute('width', checkmarkSize + 'px');
    svg.setAttribute('height', checkmarkSize + 'px');
    // svg.setAttribute('viewBox', `50 50 ${checkmarkSize} ${checkmarkSize}`); // Dynamic viewBox

    const path = svg.querySelector('path');
    path.setAttribute('stroke-width', checkmarkWeight + 'px');
}

///以下为了多parameter的情况：

if (savedSolutions.length/parameterNames.length < 2*(parameterNames.length+1))
{
    for (var i = 0; i<parameterNames.length; i++) {
            if (savedObjectives[0] == '')
            {
                // generatedSolution[i] = solutionList[i];

                generatedSolution[i] = parameterNames[i] + " =  " + solutionList[i];
            }
            else
            {
                // generatedSolution[i] =  solutionList[savedObjectives.length*parameterNames.length/objectiveNames.length+i];

                generatedSolution[i] = parameterNames[i] + " =  " + solutionList[savedObjectives.length*parameterNames.length/objectiveNames.length+i];
            }
        }
        // setButtonProperties.apply(null, generatedSolution); 

        // setColor.apply(null, generatedSolution);
        // console.log(generatedSolution);

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI = document.getElementById("generatedSolution");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI.appendChild(listItem);
        });
        
    }   

    if (savedSolutions.length/parameterNames.length >= 2*(parameterNames.length+1))
{
        for (var i = 0; i<parameterNames.length; i++) {
            // generatedSolution[i] = solutionList[solutionList.length-parameterNames.length+i];

            generatedSolution[i] = parameterNames[i] + " =  " + solutionList[solutionList.length-parameterNames.length+i];
        }
        // setColor.apply(null, generatedSolution);
        // setButtonProperties.apply(null, generatedSolution);

        // console.log(generatedSolution);

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI = document.getElementById("generatedSolution");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI.appendChild(listItem);
        });
        
    }   

// 隔几个插入一个consistency check:

// if (savedSolutions.length/parameterNames.length = 2*(parameterNames.length+1)+1) {

// }


        if (savedSolutions.length/parameterNames.length >= 2*(parameterNames.length+1)+parameterNames.length) {
            // document.getElementById("done-button").style.opacity = 1;
            document.getElementById("done-button").style.display = 'block';
        }
        else {
            // document.getElementById("done-button").style.opacity = 0.5;
            document.getElementById("done-button").style.display = 'none';
        }

        if (savedSolutions.length < 2) {
            document.getElementById("solution_name").value = 'Solution 1';
        }
        else {
            document.getElementById("solution_name").value = 'Solution ' + Math.round(savedSolutions.length/num_parameters + 1);
        }
            
        // Individual solutions
        // document.getElementById("solution_1").innerHTML = solution[0];
        // document.getElementById("solution_2").innerHTML = solution[1];
        // document.getElementById("solution_3").innerHTML = solution[2];

        function executeDatabaseOperation(userID, savedSolutions, savedObjectives, timestamp, isRefine = false) {
            $.ajax({
                url: "database_operations.php",
                type: "post",
                data: {
                    userID: userID,
                    savedSolutions: JSON.stringify(savedSolutions),
                    savedObjectives: JSON.stringify(savedObjectives),
                    timestamp: timestamp,
                    isRefine: isRefine // 新增的标识参数
                },
                success: function(response) {
                    console.log("Database operation successful:", response);
                },
                error: function(xhr, status, error) {
                    console.log("Database operation failed:", error);
                }
            });
        }



        function newSolution() {
            callNewSolution = true;
            callNextEvaluation = false;
            callRefineSolution = false;
            // badSolutions.push(solutionList[solutionList.length-2], solutionList[solutionList.length-1])
            // Placeholders
            objectiveMeasurements = "";
            solutionName = "";

            //console.log("Sending AJAX request to server...");
            console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)

                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("current-solutions",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)

            //localStorage.setItem("objective-measurements", objectiveMeasurements);




            $.ajax({
                // url: "./cgi/newSolution_u_copy.py",
                url: "./cgi/newSolution_u_2.py",

                type: "post",
                datatype: "json",
                data: { 'parameter-names'    :String(parameterNames),
                        'parameter-bounds'   :String(parameterBounds),
                        'objective-names'    :String(objectiveNames), 
                        'objective-bounds'   :String(objectiveBounds),
                        'objective-min-max'  :String(objectiveMinMax),
                        
                        'good-solutions'     :String(goodSolutions),
                        'bad-solutions'      :String(badSolutions),
                        'current-solutions'  :String(solutionList),
                        'saved-solutions'    :String(savedSolutions),
                        'saved-objectives'    :String(savedObjectives),
                        
                        'new-solution'       :String(callNewSolution),
                        'next-evaluation'    :String(callNextEvaluation),
                        'refine-solution'    :String(callRefineSolution),
                        
                        'solution-name'      :String(solutionName),
                        'objective-measurements'        :String(objectiveMeasurements)},
                beforeSend: function() {
                // 显示 loading 动画和文字
                $('#loadingContainer').show();
                },
                success: function(result) {
                    submitReturned = true;
                    solutionList = result.solution;
                    objectivesInput = result.objectives;

                    badSolutions = result.bad_solutions;
                    savedSolutions = result.saved_solutions;
                    savedObjectives = result.saved_objectives;
                    console.log("Success-newSolution_Reply_list");
                    console.log(result.solution);
                    console.log(result.tester);
                    console.log("train_x",result.train_x_actual);
                    localStorage.setItem("solution-list", solutionList);
                    localStorage.setItem("objectives-input", objectivesInput);
                    localStorage.setItem("bad-solutions", badSolutions);
                    localStorage.setItem("saved-solutions", savedSolutions);
                    localStorage.setItem("saved-objectives", savedObjectives);
                    console.log("Success-newSolution_Reply_list_ends");
                    var url = "optimise_withnewsolution.php";
                    location.href = url;
		            $('#loadingContainer').hide();

            
                },
                error: function(result){
                    console.log(parameterBounds);
                    console.log("Error in finishing experiment: " + result.message);
                    console.log("Current solutions: " + solutionList);
                    console.log("Objectives input: " + objectivesInput);
                    console.log("Bad solutions: " + badSolutions);
                    console.log("Saved solutions: " + savedSolutions);
                    console.log("Saved objectives: " + savedObjectives);
                }
               
            });
        }

        // if (solutionsevaulted < 3) {
        //   ////////我加的  
        // var x = document.getElementById('evaluate-solution');
        // var y = document.getElementById('options')
        // var z = document.getElementById('refine-button')
        //     if (x.style.display == 'none') {
        //         x.style.display = 'block';
        //         y.style.display = 'none';
        //         z.style.display = 'none';
        //     }
        //     else {
        //         x.style.display = 'none';
        //         y.style.display = 'inline-block';
        //     }
        
        //    for (i = 0; i < objectiveNames.length; i++) {
        //         var htmlNewRow = ""
        //         htmlNewRow += "<tr>"
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>"
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
        //         // htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' placeholder='Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")'> </td>"
        //         // htmlNewRow += "<td id='record-data-buttons'>"
        //         htmlNewRow += "</td></tr>"
        //         $("#measurement-table", window.document).append(htmlNewRow);
        //     }
        // }

        function evaluateSolution(){
            var x = document.getElementById('evaluate-solution');
            var y = document.getElementById('options');
            var z = document.getElementById('form-options-1');
            if (x.style.display == 'none') {
                x.style.display = 'block';
                y.style.display = 'none';
                z.style.display = 'block';

            }
            else {
                x.style.display = 'none';
                y.style.display = 'inline-block';
                z.style.display = 'none';

            }
        
           for (i = 0; i < objectiveNames.length; i++) {
                var htmlNewRow = ""
                htmlNewRow += "<tr>"
                htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>"
                htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
                htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
                // htmlNewRow += "<td id='record-data-buttons'>"
                htmlNewRow += "</td></tr>"
                $("#measurement-table", window.document).append(htmlNewRow);
            }
        

        }




        function nextEvaluation2() {
            var saved_timestamp = localStorage.getItem("saved_timestamp").split(",");
            noError = true;

            callNewSolution = false;
            callNextEvaluation = true;
            callRefineSolution = false;

            var solutionName = document.getElementById("solution_name").value;





            var tableParam = $("#measurement-table tbody");
                

            tableParam.find('tr').each(function(index) {
                var $paramCols = $(this).find("td");

                // 获取当前行的第二列数据
                var objElement = $paramCols.eq(2).text();

                // 将第二列数据填充到objectiveMeasurements对应的位置
                objectiveMeasurements[index] = objElement;
            });

            console.log("chatgpt",objectiveMeasurements);
            console.log("Solution name: " , solutionName);

            for (let i = 0; i < objectiveMeasurements.length; i++) {
                var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                if (validObj1 == false){
                    noError = false;
                    break
                }

            }

            if (noError) {
                // solutionName.push(solutionNameElement);
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                // localStorage.setItem("solution-list", solutionList);
                // localStorage.setItem("objectives-input", objectivesInput);
                // localStorage.setItem("bad-solutions", badSolutions);
                // localStorage.setItem("saved-solutions", savedSolutions);
                // localStorage.setItem("saved-objectives", savedObjectives);

                console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)

                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("solutionList",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)


                $.ajax({
                    url: "./cgi/newSolution_u_forsamplesize2.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),

                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),

                            'new-solution'       :String(callNewSolution),
                            'next-evaluation'    :String(callNextEvaluation),
                            'refine-solution'    :String(callRefineSolution),

                            'solution-name'      :String(solutionName),
                            'solution-name-list'      :String(solutionNameList),
                            'objective-measurements'   :String(objectiveMeasurements)},
                beforeSend: function() {
                    // 显示 loading 动画和文字
                    $('#loadingContainer').show();
                    },
                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution;
                        objectivesInput = result.objectives;
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        solutionNameList = result.solutionNameList;
                        
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("solution-name-list", solutionNameList);

                        console.log("Success-nextevaluation");
                        console.log(result.solution);
                        console.log(result.saved_objectives);

                        //记录时间
                        var date = new Date();
                        var formattedTimestamp = date.getFullYear() + "-" + 
                                                ("0" + (date.getMonth() + 1)).slice(-2) + "-" +
                                                ("0" + date.getDate()).slice(-2) + " " +
                                                ("0" + date.getHours()).slice(-2) + ":" +
                                                ("0" + date.getMinutes()).slice(-2) + ":" +
                                                ("0" + date.getSeconds()).slice(-2);

                        saved_timestamp.push(formattedTimestamp);
                        localStorage.setItem("saved_timestamp", saved_timestamp);
                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp);
                        // console.log(result.test2);
                        console.log("Success-nextevaluation-reply-ends");

                        var url = "optimise_withnewsolution.php";
                        location.href = url;
                $('#loadingContainer').hide();

                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                        console.log(parameterBounds);
                    console.log("Current solutions: " + solutionList);
                    console.log("Objectives input: " + objectivesInput);
                    console.log("Bad solutions: " + badSolutions);
                    console.log("Saved solutions: " + savedSolutions);
                    console.log("Saved objectives: " + savedObjectives);
                    console.log("objectiveMeasurements",objectiveMeasurements)

                    }
                });
            }
            else {
                alert("Invalid entry");
            }  
}



        function nextEvaluation() {
            var saved_timestamp = localStorage.getItem("saved_timestamp").split(",");


            noError = true;

            callNewSolution = false;
            callNextEvaluation = true;
            callRefineSolution = false;

            var solutionName = document.getElementById("solution_name").value;


 


            var tableParam = $("#measurement-table tbody");
                

            tableParam.find('tr').each(function(index) {
                var $paramCols = $(this).find("td");

                // 获取当前行的第二列数据
                var objElement = $paramCols.eq(2).text();

                // 将第二列数据填充到objectiveMeasurements对应的位置
                objectiveMeasurements[index] = objElement;
            });

            console.log("chatgpt",objectiveMeasurements);
            console.log("Solution name: " , solutionName);

            for (let i = 0; i < objectiveMeasurements.length; i++) {
                var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                if (validObj1 == false){
                    noError = false;
                    break
                }

            }



            // var validObj1 = (!isNaN(parseFloat(objElement[i])) && isFinite(objElement[i])
            //     && parseFloat(obj[i]) >= objectiveBounds[2i] && parseFloat(obj[i]) <= objectiveBounds[2i+1]);


            // ///          var validObj1 = (!isNaN(parseFloat(obj1)) && isFinite(obj1)
            // ////    && parseFloat(obj1) >= objectiveBounds[0] && parseFloat(obj1) <= objectiveBounds[1]);


            // if (validObj1 && validObj2) {
            //     noError = true;
            // }
            // else {
            //     noError = false;
            // }

            // if (/^[A-Za-z0-9]+$/.test(solutionName) == false){
            //     noError = false;
            // }

            if (noError) {
                // solutionName.push(solutionNameElement);
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                // localStorage.setItem("solution-list", solutionList);
                // localStorage.setItem("objectives-input", objectivesInput);
                // localStorage.setItem("bad-solutions", badSolutions);
                // localStorage.setItem("saved-solutions", savedSolutions);
                // localStorage.setItem("saved-objectives", savedObjectives);

                console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)

                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("solutionList",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)

    
                $.ajax({
                    url: "./cgi/next-evaluation.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),

                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),

                            'new-solution'       :String(callNewSolution),
                            'next-evaluation'    :String(callNextEvaluation),
                            'refine-solution'    :String(callRefineSolution),

                            'solution-name'      :String(solutionName),
                            'solution-name-list'      :String(solutionNameList),
                            'objective-measurements'   :String(objectiveMeasurements)},
	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution;
                        objectivesInput = result.objectives;
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        solutionNameList = result.solutionNameList;

			            DisplaySolutionText = 1;
                        
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("solution-name-list", solutionNameList);

                        console.log("Success-nextevaluation");
                        console.log(result.solution);
                        console.log(result.saved_objectives);

                        // console.log(result.test2);
                        console.log("Success-nextevaluation-reply-ends");
                        //记录时间
                        var date = new Date();
                        var formattedTimestamp = date.getFullYear() + "-" + 
                                                ("0" + (date.getMonth() + 1)).slice(-2) + "-" +
                                                ("0" + date.getDate()).slice(-2) + " " +
                                                ("0" + date.getHours()).slice(-2) + ":" +
                                                ("0" + date.getMinutes()).slice(-2) + ":" +
                                                ("0" + date.getSeconds()).slice(-2);

                        saved_timestamp.push(formattedTimestamp);
                        localStorage.setItem("saved_timestamp", saved_timestamp);
                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp);

                        var url = "optimise_withnewsolution.php";
                        location.href = url;
                        $('#loadingContainer').hide();

                        },
                        error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                        console.log(parameterBounds);
                        console.log("Current solutions: " + solutionList);
                        console.log("Objectives input: " + objectivesInput);
                        console.log("Bad solutions: " + badSolutions);
                        console.log("Saved solutions: " + savedSolutions);
                        console.log("Saved objectives: " + savedObjectives);
                        console.log("objectiveMeasurements",objectiveMeasurements)

                    }
                });
            }
            else {
                alert("Invalid entry");
            }  
        }

        function refineSolution() {
            noError = true;

            callNewSolution = false;
            callNextEvaluation = false;
            callRefineSolution = true;

            var solutionName = document.getElementById("solution_name").value;


            
            var tableParam = $("#measurement-table tbody");
                

                tableParam.find('tr').each(function(index) {
                    var $paramCols = $(this).find("td");
    
                    // 获取当前行的第二列数据
                    var objElement = $paramCols.eq(2).text();
    
                    // 将第二列数据填充到objectiveMeasurements对应的位置
                    objectiveMeasurements[index] = objElement;
                });
    
                console.log("chatgpt2",objectiveMeasurements);
    
    //////////////自己加的
    
                for (let i = 0; i < objectiveMeasurements.length; i++) {
                    var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                    && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                    if (validObj1 == false){
                        noError = false;
                        break
                    }
    
                }
    
    

            // if (/^[A-Za-z0-9]+$/.test(solutionName) == false){
            //     noError = false;
            // }

            if (noError) {
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)
                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("solutionList",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)
    
                $.ajax({
                    url: "./cgi/refine-solution.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),

                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),

                            'new-solution'       :String(callNewSolution),
                            'next-evaluation'    :String(callNextEvaluation),
                            'refine-solution'    :String(callRefineSolution),

                            'solution-name'      :String(solutionName),
                            'solution-name-list'      :String(solutionNameList),
                            'objective-measurements'   :String(objectiveMeasurements)},

	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution;
                        objectivesInput = result.objectives;
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        solutionNameList = result.solutionNameList;

                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("solution-name-list", solutionNameList);

                        // 记录时间
                        var date = new Date();
                        var formattedTimestamp = date.getFullYear() + "-" + 
                                                ("0" + (date.getMonth() + 1)).slice(-2) + "-" +
                                                ("0" + date.getDate()).slice(-2) + " " +
                                                ("0" + date.getHours()).slice(-2) + ":" +
                                                ("0" + date.getMinutes()).slice(-2) + ":" +
                                                ("0" + date.getSeconds()).slice(-2);

                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp, true); // 传递一个额外参数来标识 refine 操作

                        console.log("Success-refineevaluation");
                        var url = "optimise_withnewsolution.php";
                        location.href = url;
                        $('#loadingContainer').hide();
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                    }
                });
            }
            else {
                alert("Invalid entry");
            }  
        }


        function finishSolutions() {
            noError = true;
            
            if (savedSolutions.length/num_parameters < 3) {
                noError = false;
            }
            var saved_timestamp = localStorage.getItem("saved_timestamp").split(",");

            if (noError) {
                $.ajax({
                    url: "./cgi/finish-solutions.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),
                            'solution-name-list'      :String(solutionNameList),


                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),
                            'objectives-input'   :String(objectivesInput)
                            // 'saved_timestamp'    :String(saved_timestamp)
                        },

	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        // solutionList = result.solution;
                        // badSolutions = result.bad_solutions;
                        objectivesInput = result.objectives;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        objectivesNormalised = result.objectives_normalised;
                        // bestSolutions = result.best_solutions;
                        // solutionNameList = result.solutionNameList;
                        BestSolutionIndex = result.BestSolutionIndex;
                        // saved_timestamp = result.saved_timestamp;
			// console.log(solutionNameIndex);
                        console.log(objectivesInput);
                        console.log(savedSolutions);
                        console.log(objectivesNormalised);
                        // console.log(bestSolutions)
                        // localStorage.setItem("solution-list", solutionList);
                        // localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("objectives-normalised", objectivesNormalised);
                        // localStorage.setItem("best-solutions", bestSolutions);
                        localStorage.setItem("solution-name-list", solutionNameList);
                        localStorage.setItem("BestSolutionIndex", BestSolutionIndex);
                        localStorage.setItem("saved_timestamp", saved_timestamp);

                        $.ajax({
                            url: "results.php",
                            type: "post",
                            data: {
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'solution-list'  :String(solutionList),
                            'saved_timestamp'  :String(saved_timestamp)                           
                            },
                            success: function(response) {
                                var url = "results.php";
                                window.location.href = url;
                            },
                            error: function(response) {
                                console.log("Error sending data");
                            }
                        });
                        // var url = "results.php";
                        // location.href = url;
                    console.log("solutionNameList",solutionNameList);
                    console.log("Success");
                    $('#loadingContainer').hide();
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                    }
                });
            }
            else {
                alert("Please ensure you have evaluated at least 3 solutions");
            }  
        }

    </script>
</body>
</html>
