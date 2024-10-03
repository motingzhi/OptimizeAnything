<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $randomizerstatus = json_encode($_POST['randomizerstatus']);
//     $ismanual = json_encode($_POST['ismanual']);

//     $stmt = $conn->prepare("UPDATE data SET randomizerstatus = ?,  ismanual = ? WHERE prolific_ID = ?");
//     if ($stmt === false) {
//         die("Prepare failed: " . $conn->error);
//     }
//     $stmt->bind_param("sss", $randomizerstatus,  $ismanual, $userID);
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
                max-height: calc(100vh/1.5); /* 计算中间内容的最大高度减去top-bar和bottom-bar的高度 */
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
            max-width: 90%;
            width: 70%;
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

                <label>1. To compare the optimization effect between humans and AI, now you are assigned to the group conducting optimization manually. So you will need to propose different solutions by yourself.                    
                </label></br>
                <div id="RequirementDisplay"></div>                           
                </div>
            </div>


    </div>
    <div id="dataDisplay"></div>

    <div class="container">
        <div class="card custom-card">
            <div class="card-body2">
                <p class="card-title">Propose NEW Solution by Yourself</p>

                <table class="table table-bordered" id="variable-table">
                    <thead>  
                        <tr>  
                            <th id="record-parameter-name" width="30%"> Variable </th>   
                            <th id="record-parameter-bound" width="30%"> Ranges of your variable </th>  
                            <th id="record-parameter-vale" width="30%"> Enter value </th>  
                        </tr>  
                    </thead>  
                    <tbody>
                    </tbody>
                </table>

               

            </div>
        </div>
    </div>
<!-- 
    <h5><b>New Alternative</b></h5>
    <ul id="generatedSolution" style="list-style-type: none;"></ul> -->

    <!-- 自己改的 -->


    
    <!-- <div id="options" style="display: inline-block; margin: 0 auto;">
        <button class="btn btn-primary" id="evaluate-button" style="width: 40%;" onclick="evaluateSolution()">I want to evaluate this</button>
        <button class="btn btn-outline-primary" id="skip-button" style="width: 40%;" onclick="newSolution()">Skip. I know it's not good</button>
    </div> -->

    <br>
    <div class="container">
        <div id="evaluate-solution" style="display: block;">
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


    </div>

    


    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>
    </div>
 
</div>
<!-- <div class="bottom-bar">


        
        <div id="form-options-2" style="display: inline-block; margin: 0 auto; text-align: center;  ">
                <button class="btn btn-outline-success" id="next-button" onclick="submitManual()">Submit</button>
        </div>


        <br>
        <div id="done-button"  style="text-align: right;">
            <button class="btn btn-success" id="done" onclick="finishSolutions()">I'm done</button>    
        </div>
</div> -->


<div class="bottom-bar">
    <div style="display: table; width: 100%; height: 100%;">
        <div style="display: table-cell; text-align: center; vertical-align: middle;">
            <div id="form-options-2" style="display: inline-block; margin-right: 10px;">
                <button class="btn btn-outline-success" id="next-button" onclick="submitManual()">Submit</button>
            </div>
            <div id="done-button" style="display: inline-block;">
                <button class="btn btn-success" id="done" onclick="finishSolutions()">I'm done</button>
            </div>
        </div>
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
    //     var ismanual = localStorage.getItem("ismanual"); 

        try {
        var objectiveMeasurements = localStorage.getItem("objective-Measurements").split(",");
        } catch (err) {
        // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
        var objectiveMeasurements = [];
        }
        // 现在，objectiveMeasurements 包含 localStorage 中 "saved-objectives" 的值，如果不存在则为一个空数组


        
        var num_parameters = parameterNames.length;

        try {var objectivesInput = localStorage.getItem("objectives-input").split(",");}
        catch(err) {objectivesInput = []}

        try {var solutionNameList = localStorage.getItem("solution-name-list").split(",");}
        catch(err) {solutionNameList= []}

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

        // 检查 savedSolutions 是否为空字符串，如果为空，则将其长度设置为 0
        if (savedSolutions == ""  ){
            count = 0;
        } else{
            count = savedSolutions.length/parameterNames.length;
        }

    displayDiv.innerHTML =  "You have evaulated " + count + " solutions." + "<br>";

    var RequirementDisplay = document.getElementById("RequirementDisplay");
    RequirementDisplay.innerHTML =  "2. Please evaluate at least <strong>" + parseInt(2*(parameterNames.length+1)+2*parameterNames.length) + " solutions</strong> to proceed."  + " Then, you will see the Done button; you can either choose to continue until you think you have optimal solutions or directly finish."
+ 
"<br><br>3. How to evaluate a solution:<br>" +"You estimate the objective's measurement value based on the values of new solutions." + "<a href='material_1.php' target='_blank'> Back to the tutorial</a>";
   
    $('#variable-table tbody').empty();
                // Add rows based on parameterNames and parameterBounds
    for (let i = 0; i < parameterNames.length; i++) {
        let lowerBound = parameterBounds[2 * i];
        let upperBound = parameterBounds[2 * i + 1];
        let htmlNewRow = "<tr>";


        
        htmlNewRow += `<td contenteditable='false' class='record-data' id='record-parameter-name'>${parameterNames[i]}</td>`;
        htmlNewRow += "<td contenteditable='false' class='record-data' id='record-parameter-bound'> " + "(" + lowerBound + "-" + upperBound  + ")"+ " </td>" // placeholder的效果怎么做
        htmlNewRow += `<td contenteditable='true' class='record-data' id='record-parameter-value'></td>`;
        htmlNewRow += "</td></tr>";

        $("#variable-table tbody").append(htmlNewRow);

    }

    $('#measurement-table tbody').empty();

    
    for (i = 0; i < objectiveNames.length; i++) {
        var htmlNewRow = "" 
        htmlNewRow += "<tr>" 
        htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>" 
        htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "(" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>" // placeholder的效果怎么做
        htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
        htmlNewRow += "</td></tr>"
        $("#measurement-table", window.document).append(htmlNewRow);
    }






    // if (savedSolutions.length/parameterNames.length < 2*(parameterNames.length+1)-1)
    // {
    //     var x = document.getElementById('evaluate-solution');
    //     var y = document.getElementById('options');
    //     var z = document.getElementById('form-options-1');
    //     var z2 = document.getElementById('form-options-2');
    //     var z3 = document.getElementById('form-options-3');

    //         if (x.style.display == 'none') {
    //             x.style.display = 'block'
    //             z2.style.display = 'block'

    //             y.style.display = 'none'
    //             z.style.display = 'none'
    //             z3.style.display = 'none'

    //         }
    //         else {
    //             x.style.display = 'none'
    //             z2.style.display = 'none'
    //             z3.style.display = 'none'

    //             y.style.display = 'inline-block'
    //             z.style.display = 'block'
    //         }
        
    //        for (i = 0; i < objectiveNames.length; i++) {
    //             var htmlNewRow = "" 
    //             htmlNewRow += "<tr>" 
    //             htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>" 
    //             htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "(" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>" // placeholder的效果怎么做
    //             htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
    //             htmlNewRow += "</td></tr>"
    //             $("#measurement-table", window.document).append(htmlNewRow);
    //         }
    // }
    
    // if (savedSolutions.length/parameterNames.length > 2*(parameterNames.length+1)-1) {

     

    //     var x = document.getElementById('evaluate-solution');
    //     var y = document.getElementById('options');
    //     var z = document.getElementById('form-options-1');
    //     var z2 = document.getElementById('form-options-2');
    //     var z3 = document.getElementById('form-options-3');

    //     x.style.display = 'none';
    //     z.style.display = 'none';
    //     z2.style.display = 'none';
    //     z3.style.display = 'none';


    // }

    // if (savedSolutions.length/parameterNames.length == 2*(parameterNames.length+1)-1) {

    //     var x = document.getElementById('evaluate-solution');
    //     var y = document.getElementById('options');
    //     var z = document.getElementById('form-options-1');
    //     var z2 = document.getElementById('form-options-2');
    //     var z3 = document.getElementById('form-options-3');

    //     if (x.style.display == 'none') {
    //             x.style.display = 'block'
    //             z3.style.display = 'block'

    //             y.style.display = 'none'
    //             z.style.display = 'none'
    //             z2.style.display = 'none'

    //         }
    //         else {
    //             x.style.display = 'none'
    //             z2.style.display = 'none'
    //             z3.style.display = 'none'

    //             y.style.display = 'inline-block'
    //             z.style.display = 'block'
    //         }
        
    //        for (i = 0; i < objectiveNames.length; i++) {
    //             var htmlNewRow = "" 
    //             htmlNewRow += "<tr>" 
    //             htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>" 
    //             htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>" // placeholder的效果怎么做
    //             htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
    //             htmlNewRow += "</td></tr>"
    //             $("#measurement-table", window.document).append(htmlNewRow);
    //         }

    // }






      var solutionsevaulted = parseInt(savedSolutions.length/parameterNames.length);

        
      var generatedSolution = [];
      






    
// 隔几个插入一个consistency check:

// if (savedSolutions.length/parameterNames.length = 2*(parameterNames.length+1)+1) {

// }


        if (savedSolutions.length/parameterNames.length >= 2*(parameterNames.length+1)+ 2*parameterNames.length) {
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

        function executeDatabaseOperation(userID, savedSolutions, savedObjectives, timestamp, isRefine) {
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



        // function newSolution() {
        //     callNewSolution = true;
        //     callNextEvaluation = false;
        //     callRefineSolution = false;
        //     // badSolutions.push(solutionList[solutionList.length-2], solutionList[solutionList.length-1])
        //     // Placeholders
        //     objectiveMeasurements = "";
        //     solutionName = "";

        //     //console.log("Sending AJAX request to server...");
        //     console.log("objectiveMeasurements",objectiveMeasurements)
        //         console.log("parameterNames",parameterNames)
        //         console.log("parameterBounds",parameterBounds)
        //         console.log("objectiveNames",objectiveNames)
        //         console.log("objectiveBounds",objectiveBounds)
        //         console.log("objectiveMinMax",objectiveMinMax)

        //         console.log("badSolutions",badSolutions)
        //         console.log("goodSolutions",goodSolutions)
        //         console.log("current-solutions",solutionList)
        //         console.log("savedSolutions",savedSolutions)
        //         console.log("savedObjectives",savedObjectives)
        //         console.log("objectivesInput",objectivesInput)

        //     //localStorage.setItem("objective-measurements", objectiveMeasurements);




        //     $.ajax({
        //         // url: "./cgi/newSolution_u_copy.py",
        //         url: "./cgi/newSolution_u_2.py",

        //         type: "post",
        //         datatype: "json",
        //         data: { 'parameter-names'    :String(parameterNames),
        //                 'parameter-bounds'   :String(parameterBounds),
        //                 'objective-names'    :String(objectiveNames), 
        //                 'objective-bounds'   :String(objectiveBounds),
        //                 'objective-min-max'  :String(objectiveMinMax),
                        
        //                 'good-solutions'     :String(goodSolutions),
        //                 'bad-solutions'      :String(badSolutions),
        //                 'current-solutions'  :String(solutionList),
        //                 'saved-solutions'    :String(savedSolutions),
        //                 'saved-objectives'    :String(savedObjectives),
                        
        //                 'new-solution'       :String(callNewSolution),
        //                 'next-evaluation'    :String(callNextEvaluation),
        //                 'refine-solution'    :String(callRefineSolution),
                        
        //                 'solution-name'      :String(solutionName),
        //                 'objective-measurements'        :String(objectiveMeasurements)},
        //         beforeSend: function() {
        //         // 显示 loading 动画和文字
        //         $('#loadingContainer').show();
        //         },
        //         success: function(result) {
        //             submitReturned = true;
        //             solutionList = result.solution;
        //             objectivesInput = result.objectives;

        //             badSolutions = result.bad_solutions;
        //             savedSolutions = result.saved_solutions;
        //             savedObjectives = result.saved_objectives;
        //             console.log("Success-newSolution_Reply_list");
        //             console.log(result.solution);
        //             console.log(result.tester);
        //             console.log("train_x",result.train_x_actual);
        //             localStorage.setItem("solution-list", solutionList);
        //             localStorage.setItem("objectives-input", objectivesInput);
        //             localStorage.setItem("bad-solutions", badSolutions);
        //             localStorage.setItem("saved-solutions", savedSolutions);
        //             localStorage.setItem("saved-objectives", savedObjectives);
        //             console.log("Success-newSolution_Reply_list_ends");
        //             var url = "optimise_withnewsolution.php";
        //             location.href = url;
        //             $('#loadingContainer').hide();

            
        //         },
        //         error: function(result){
        //             console.log(parameterBounds);
        //             console.log("Error in finishing experiment: " + result.message);
        //             console.log("Current solutions: " + solutionList);
        //             console.log("Objectives input: " + objectivesInput);
        //             console.log("Bad solutions: " + badSolutions);
        //             console.log("Saved solutions: " + savedSolutions);
        //             console.log("Saved objectives: " + savedObjectives);
        //         }
               
        //     });
        // }

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

        // function evaluateSolution(){
        //     var x = document.getElementById('evaluate-solution');
        //     var y = document.getElementById('options');
        //     var z = document.getElementById('form-options-1');
        //     if (x.style.display == 'none') {
        //         x.style.display = 'block';
        //         y.style.display = 'none';
        //         z.style.display = 'block';

        //     }
        //     else {
        //         x.style.display = 'none';
        //         y.style.display = 'inline-block';
        //         z.style.display = 'none';

        //     }
        
        //    for (i = 0; i < objectiveNames.length; i++) {
        //         var htmlNewRow = ""
        //         htmlNewRow += "<tr>"
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>"
        //         htmlNewRow += "<td contenteditable='false' class='record-data' id='display-measurement-bounds'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' style='width: 25%;' placeholder=''> </td>"
        //         // htmlNewRow += "<td id='record-data-buttons'>"
        //         htmlNewRow += "</td></tr>"
        //         $("#measurement-table", window.document).append(htmlNewRow);
        //     }
        

        // }

        function submitManual() {
            var saved_timestamp = localStorage.getItem("saved_timestamp").split(",");
            noError = true;

            // callNewSolution = false;
            // callNextEvaluation = true;
            // callRefineSolution = false;

            var solutionName = document.getElementById("solution_name").value;





            var tableParam = $("#measurement-table tbody");
                

            tableParam.find('tr').each(function(index) {
                var $paramCols = $(this).find("td");

                // 获取当前行的第二列数据
                var objElement = $paramCols.eq(2).text();

                // 将第二列数据填充到objectiveMeasurements对应的位置
                objectiveMeasurements[index] = objElement;
            });

            // console.log("chatgpt",objectiveMeasurements);
            // console.log("Solution name: " , solutionName);

            for (let i = 0; i < objectiveMeasurements.length; i++) {
                var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                if (validObj1 == false){
                    noError = false;
                    break
                }

            }
            var solution = []

            var tableParam2 = $("#variable-table tbody");
                

            tableParam2.find('tr').each(function(index) {
                var $paramCols = $(this).find("td");

                // 获取当前行的第二列数据
                var solutionElement = $paramCols.eq(2).text();

                // 将第二列数据填充到objectiveMeasurements对应的位置
                solution[index] = solutionElement;
            });

            // console.log("chatgpt",objectiveMeasurements);
            // console.log("Solution name: " , solutionName);

            for (let i = 0; i < solution.length; i++) {
                var validObj1 = (!isNaN(parseFloat(solution[i])) && isFinite(solution[i])
                && parseFloat(solution[i]) >= parameterBounds[2*i] && parseFloat(solution[i]) <= parameterBounds[2*i+1]);
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
            
                submitReturned = true;


                
                solutionList = solutionList == "" ? solution : solutionList.concat(solution);
                savedSolutions = savedSolutions == "" ? solution : savedSolutions.concat(solution);
                savedObjectives = savedObjectives == "" ? objectiveMeasurements : savedObjectives.concat(objectiveMeasurements);
                solutionNameList = solutionNameList == "" ? solutionName : solutionNameList.concat(solutionName);


                // // Append the solution array to solutionList
                // solutionList.push(solution);
                // savedObjectives.push(solution);
                // savedObjectives.push(objectiveMeasurements);
                // solutionNameList.push(solutionName);
                // Save the updated solutionList back to localStorage
                // localStorage.setItem("solution-list", solutionList.join(","));

                // solutionList = result.solution;
                // // objectivesInput = result.objectives;
                // // badSolutions = result.bad_solutions;
                // savedSolutions = result.saved_solutions;
                // savedObjectives = result.saved_objectives;
                // solutionNameList = result.solutionNameList;
                
                localStorage.setItem("solution-list", solutionList);
                localStorage.setItem("objectives-input", objectivesInput);
                localStorage.setItem("bad-solutions", badSolutions);
                localStorage.setItem("saved-solutions", savedSolutions);
                localStorage.setItem("saved-objectives", savedObjectives);
                localStorage.setItem("solution-name-list", solutionNameList);

                console.log("Success-nextevaluation");
                // console.log(result.solution);
                // console.log(result.saved_objectives);
                var tester = 1;

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
                executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp,tester);
                // console.log(result.test2);
                console.log("Success-nextevaluation-reply-ends");

                var url = "optimise_manual.php";
                location.href = url;
                $('#loadingContainer').hide();

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
                
            }
            else {
                alert("Invalid entry");
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
                        var tester = 1;

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
                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp,tester);
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
                        var tester = 1;

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
                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp,tester);

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
                        var tester = 2;
                        // 记录时间
                        var date = new Date();
                        var formattedTimestamp = date.getFullYear() + "-" + 
                                                ("0" + (date.getMonth() + 1)).slice(-2) + "-" +
                                                ("0" + date.getDate()).slice(-2) + " " +
                                                ("0" + date.getHours()).slice(-2) + ":" +
                                                ("0" + date.getMinutes()).slice(-2) + ":" +
                                                ("0" + date.getSeconds()).slice(-2);

                        executeDatabaseOperation(userID, savedSolutions.slice(-1), savedObjectives.slice(-1), formattedTimestamp, tester); // 传递一个额外参数来标识 refine 操作

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

