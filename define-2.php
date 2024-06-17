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

            box-shadow: 0 -2px 4px rgba(0,0,0,0.1); Shadow for the bottom bar
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
<div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>1. Specify</h1>
            <form action="help.php#define">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>
        </div>
    </div>

    
    <div class="centered-content">
<<<<<<< Updated upstream
        <h2 style="margin-top: 20px;">What makes an alternative desirable? - Specify objectives</h2>
        <p><i>Describe your objectives. You can include also subjective measurements, even opinions. Examples: “cost”, “travel time”.</i></p>
=======
        <h2 style="margin-top: 20px;">Specify objectives of optimization</h2>
        <p><i>Describe your objectives for optimization. You can include also subjective measurements, even opinions.</i></p>
        <p><i>Here is a pre-filled example for the travel scenario, objectives are “Cost”, “Satisfaction”. You can modify those values in the form directly to your own objective</i></p>


>>>>>>> Stashed changes
        <h5 style="margin-bottom: 20px;">Objectives</h5>
        <table class="table table-bordered" id="objective-table" >
            <thead>  
                <tr>  
<<<<<<< Updated upstream
                <th id="record-objective-name" width="40%"> Name </th>   
=======
                <th id="record-objective-name" width="40%"> Name </th> 
                <th id="record-objective-unit" width="40%"> Unit(if have) </th>     
>>>>>>> Stashed changes
                <th id="record-objective-lower-bound"> Minimum </th>  
                <th id="record-objective-upper-bound"> Maximum </th> 
                <th id="record-objective-min-max"> Minimise or Maximise </th>  
                </tr>  
            </thead>  
            <tbody>
            <tr>
<<<<<<< Updated upstream
                <td contenteditable="true" class="record-data" id="record-objective-name">Cost ($)</td>
=======
                <td contenteditable="true" class="record-data" id="record-objective-name">Cost</td>
                <td contenteditable="true" class="record-data" id="record-objective-unit">euro</td>
>>>>>>> Stashed changes
                <td contenteditable="true" class="record-data" id="record-objective-lower-bound">100</td>
                <td contenteditable="true" class="record-data" id="record-objective-upper-bound">1000</td>
                <td contenteditable="false" class="record-data" id="record-objective-min-max">
                    <select id="min-max-1" style="font-family: calibri; font-size: medium;">
                        <option value="minimise" selected="selected">minimise</option>
                        <option value="maximise">maximise</option>
                    </select>
                </td>
            </tr>
            <tr>
<<<<<<< Updated upstream
                <td contenteditable="true" class="record-data" id="record-objective-name">Satisfaction (%)</td>
=======
                <td contenteditable="true" class="record-data" id="record-objective-name">Satisfaction</td>
                <td contenteditable="true" class="record-data" id="record-objective-unit">%</td>
>>>>>>> Stashed changes
                <td contenteditable="true" class="record-data" id="record-objective-lower-bound">0</td>
                <td contenteditable="true" class="record-data" id="record-objective-upper-bound">100</td>
                <td contenteditable="false" class="record-data" id="record-objective-min-max">
                    <select id="min-max-2" style="font-family: calibri; font-size: medium;">
                        <option value="minimise">minimise</option>
                        <option value="maximise" selected="selected">maximise</option>
                    </select>
                </td>
            </tr>
        </tbody>
        </table>
        <button class="btn btn-primary" id="add-record-button" onclick="addDesignObjectivesTable()" >Add Objective</button>
    </div>
    <!-- <div id="progressBar"><div class="progress"></div> -->
    <br>

    <div id="loadingContainer">
    <div id="loadingIcon"></div>
    <div id="loadingText">Loading...</div>
    </div>

    <div class="bottom-bar">
    <div class="container d-flex justify-content-between">
        <button class="btn btn-outline-success" id="back-button" style="width: 20%;" onclick="goBack()">Back</button>
        <button class="btn btn-success" id="finish-objectives-button" style="width: 20%;" onclick="finishObjs()">Ready</button>

    </div>
    </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        var solutionNameList =  "";
<<<<<<< Updated upstream
        window.onbeforeunload = function() {
            localStorage.removeItem('objectives');
        };
=======
>>>>>>> Stashed changes

        // function updateProgress() {
        //     var progressBar = document.querySelector('#progressBar .progress');
        //     var percentComplete = (performance.now() - startTime) / estimatedLoadTime * 100;
        //     progressBar.style.width = Math.min(percentComplete, 100) + '%';

        //     if (percentComplete < 100) {
        //         // 如果进度条未满，则继续更新进度条
        //         requestAnimationFrame(updateProgress);
        //     }
        // }

        // // 获取开始加载页面的时间
        // var startTime = performance.now();

        // // 预估页面加载时间，这里设置为5秒
        // var estimatedLoadTime = 500;
        // // function updateProgress(event) {
        // // if (event.lengthComputable) {
        // //   var progressBar = $('#progressBar .progress');
        // //   var percentComplete = (event.loaded / event.total) * 100;
        // //   progressBar.css('width', percentComplete + '%');
        // //   console.log("jiji");
        // // }}

        // // 监听页面加载完成事件
        // window.addEventListener('load', function() {
        // // 更新进度条
        //     updateProgress();
        // });
        function goBack() {
<<<<<<< Updated upstream
            saveFormData();
            location.href = "define.php";
        }

        function saveFormData() {
            const table = document.getElementById('objective-table').getElementsByTagName('tbody')[0];
            const objectives = [];
            for (let row of table.rows) {
                const cells = [];
                for (let cell of row.cells) {
                    if (cell.querySelector('select')) {
                        cells.push(cell.querySelector('select').value);
                    } else {
                        cells.push(cell.innerText);
                    }
                }
                objectives.push(cells);
            }
            localStorage.setItem('objectives', JSON.stringify(objectives));
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            loadFormData();
        });

        function loadFormData() {
            const table = document.getElementById('objective-table').getElementsByTagName('tbody')[0];
            const storedObjectives = JSON.parse(localStorage.getItem('objectives'));

            if (storedObjectives) {
                table.innerHTML = '';
                storedObjectives.forEach(row => {
                    const newRow = table.insertRow();
                    row.forEach((cellText, index) => {
                        const newCell = newRow.insertCell(index);
                        newCell.contentEditable = 'true';
                        newCell.className = 'record-data';
                        if (index === 3) { // select element
                            const select = document.createElement('select');
                            select.style.fontFamily = 'calibri';
                            select.style.fontSize = 'medium';
                            const option1 = document.createElement('option');
                            option1.value = 'minimise';
                            option1.text = 'minimise';
                            const option2 = document.createElement('option');
                            option2.value = 'maximise';
                            option2.text = 'maximise';
                            select.add(option1);
                            select.add(option2);
                            select.value = cellText;
                            newCell.appendChild(select);
                        } else {
                            newCell.innerText = cellText;
                        }
                    });
                });
            }
        }

        function finishObjs() {

            saveFormData();
=======
            // saveFormData();
            location.href = "define.php";
        }

        // function saveFormData() {
        //     const table = document.getElementById('objective-table').getElementsByTagName('tbody')[0];
        //     const objectives = [];
        //     for (let row of table.rows) {
        //         const cells = [];
        //         for (let cell of row.cells) {
        //             if (cell.querySelector('select')) {
        //                 cells.push(cell.querySelector('select').value);
        //             } else {
        //                 cells.push(cell.innerText);
        //             }
        //         }
        //         objectives.push(cells);
        //     }
        //     localStorage.setItem('objectives', JSON.stringify(objectives));
        // }

        // document.addEventListener('DOMContentLoaded', (event) => {
        //     loadFormData();
        // });

        // function loadFormData() {
        //     const table = document.getElementById('objective-table').getElementsByTagName('tbody')[0];
        //     const storedObjectives = JSON.parse(localStorage.getItem('objectives'));

        //     if (storedObjectives) {
        //         table.innerHTML = '';
        //         storedObjectives.forEach(row => {
        //             const newRow = table.insertRow();
        //             row.forEach((cellText, index) => {
        //                 const newCell = newRow.insertCell(index);
        //                 newCell.contentEditable = 'true';
        //                 newCell.className = 'record-data';
        //                 if (index === 3) { // select element
        //                     const select = document.createElement('select');
        //                     select.style.fontFamily = 'calibri';
        //                     select.style.fontSize = 'medium';
        //                     const option1 = document.createElement('option');
        //                     option1.value = 'minimise';
        //                     option1.text = 'minimise';
        //                     const option2 = document.createElement('option');
        //                     option2.value = 'maximise';
        //                     option2.text = 'maximise';
        //                     select.add(option1);
        //                     select.add(option2);
        //                     select.value = cellText;
        //                     newCell.appendChild(select);
        //                 } else {
        //                     newCell.innerText = cellText;
        //                 }
        //             });
        //         });
        //     }
        // }

        function finishObjs() {

            // saveFormData();
>>>>>>> Stashed changes

            var noError = true;
            var parameterNames = localStorage.getItem("parameter-names").split(",");
            var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
            var objectiveNames = [];
            var objectiveBounds = [];
            var objectiveMinMax = [];
<<<<<<< Updated upstream
    
=======
            var badSolutions = [];

>>>>>>> Stashed changes
            /* var participantID = localStorage.getItem("id");
            var conditionID = localStorage.getItem("exp-condition");
            var applicationID = localStorage.getItem("app"); */
            
            // var tableParam = $("#parameter-table tbody");
                
            // tableParam.find('tr').each(function() {
            //     var $paramCols = $(this).find("td");
            //     var paramRowEntries = [];
    
            //     $.each($paramCols, function() {
            //         paramRowEntries.push($(this).text());
            //     });
                
            //     var paramName = paramRowEntries[0];
            //     console.log("haha" + paramName);
            //     parameterNames.push(paramName);

            //     // if (/^[A-Za-z0-9]+$/.test(paramName)){
            //     //     parameterNames.push(paramName);
            //     // }
            //     // else {
            //     //     noError = false;
            //     // }
    
            //     var paramLowerBound = paramRowEntries[1];
            //     var paramUpperBound = paramRowEntries[2];
            //     var validLowerBound = (!isNaN(parseFloat(paramLowerBound)) && isFinite(paramLowerBound));
            //     var validUpperBound = (!isNaN(parseFloat(paramUpperBound)) && isFinite(paramUpperBound));

            //     if (validLowerBound && validUpperBound){
            //         if (parseFloat(paramLowerBound) < parseFloat(paramUpperBound)){
            //             var rowBounds = [parseFloat(paramLowerBound), parseFloat(paramUpperBound)];
            //             parameterBounds.push(rowBounds);
            //         }
            //         else {
            //            noError = false;
            //         }
            //     }
            //     else {
            //         noError = false;
            //     }
            // });

            // Find all the objective names and bounds
            var tableObjs = $("#objective-table tbody");
                
            tableObjs.find('tr').each(function() {
                var $objCols = $(this).find("td");
                var objRowEntries = [];

                $.each($objCols, function() {
                    objRowEntries.push($(this).text());
                });
                
                var objName = objRowEntries[0];
<<<<<<< Updated upstream
                objectiveNames.push(objName);

=======
                var unit = objRowEntries[1];
                if ((unit === "None") || (unit === "")) {
                    objectiveNames.push(objName);
                }
                else {
                    objectiveNames.push(objName+"/"+unit);

                }
>>>>>>> Stashed changes
                // if (/^[A-Za-z0-9]+$/.test(objName)){
                //     objectiveNames.push(objName);
                // }
                // else {
                //     noError = false;
                // }

                // console.log("objRowEntries[3]",objRowEntries2[3]);

    
<<<<<<< Updated upstream
                var objLowerBound = objRowEntries[1];
                var objUpperBound = objRowEntries[2];
=======
                var objLowerBound = objRowEntries[2];
                var objUpperBound = objRowEntries[3];
>>>>>>> Stashed changes
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

            // var i = 1;
            // var z = 
            // Store whether each objective is to be minimised or maximised in a list  这部分可以移到
            // var min_max_1 = document.getElementById("min-max-1").value;
            // var min_max_2 = document.getElementById("min-max-2").value;
            // objectiveMinMax.push(min_max_1, min_max_2);

            var noError = true;
            var newSolution = true;
            var nextEvaluation = false;
            var refineSolution = false;
            var goodSolutions = [];
            var badSolutions = [];



            // console.log(parameterNames);
            // console.log(parameterBounds);
            // console.log(objectiveNames);
            // console.log(objectiveBounds);
            console.log("objectiveMinMax",objectiveMinMax);

            // if (parameterBounds.length != parameterNames.length && parameterBounds.length <= 1){
            //     noError = false;
            // }

            if (objectiveBounds.length != objectiveNames.length){
                noError = false;
            }
    
            if (noError){
                // localStorage.setItem("parameter-names", parameterNames);
                // localStorage.setItem("parameter-bounds", parameterBounds);
                localStorage.setItem("objective-names", objectiveNames);
                localStorage.setItem("objective-bounds", objectiveBounds);
                localStorage.setItem("objective-min-max", objectiveMinMax);
                localStorage.setItem("good-solutions", goodSolutions);
                localStorage.setItem("new-solution", newSolution);
                localStorage.setItem("next-evaluation", nextEvaluation);
                localStorage.setItem("solution-name-list", solutionNameList);
<<<<<<< Updated upstream
    
=======
                localStorage.setItem("bad-solutions", badSolutions);

>>>>>>> Stashed changes
                // localStorage.setItem("tutorial-done", true);
    
                // $.ajax({
                //     /* url: "./cgi/start_log.py",
                //     type: "post",
                //     datatype: "json",
                //     data: { 'participant_id'    :String(participantID),
                //             'application_id'    :String(applicationID),
                //             'condition_id'      :String(conditionID) },*/
                //     success: function(result) {
                //     submitReturned = true;
                    
                //     var url = "confirm-definitions.php";
                //     location.href = url;
                //     },
                //     error: function(result){
                //         console.log("Error in finishing experiment: " + result.message);
                //     }

                $.ajax({
<<<<<<< Updated upstream
                url: "./cgi/newSolution_u.py",
=======
                url: "./cgi/newSolution_u_copy.py",
>>>>>>> Stashed changes
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
                    var url = "optimise_withnewsolution.php";
                    location.href = url;
                    $('#loadingContainer').hide();
                },
                error: function(result){
                    console.log("Error");
                }
                // complete: function() {
                // // 隐藏 loading 动画和文字
                
                // }

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
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-lower-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-parameter-upper-bound'></td>"
            // htmlNewRow += "<td id='record-data-buttons'>"
            htmlNewRow += "</td></tr>"
            $("#parameter-table", window.document).append(htmlNewRow);  
        }

        function addDesignObjectivesTable(){
            var htmlNewRow = ""
            htmlNewRow += "<tr>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-name'></td>"
<<<<<<< Updated upstream
=======
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-unit'></td>"
>>>>>>> Stashed changes
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-lower-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'><select id='min-max-3' style='font-family: calibri; font-size: medium;'><option value='minimise' selected='selected'>minimise</option><option value='maximise'>maximise</option></select></td>"
            htmlNewRow += "</td></tr>"
            $("#objective-table", window.document).append(htmlNewRow);  


        }
<<<<<<< Updated upstream
        document.getElementById('objective-table').addEventListener('input', saveFormData);
        document.getElementById('objective-table').addEventListener('change', saveFormData);
=======
        // document.getElementById('objective-table').addEventListener('input', saveFormData);
        // document.getElementById('objective-table').addEventListener('change', saveFormData);
>>>>>>> Stashed changes
    </script>
    
    </body>
</html>


<<<<<<< Updated upstream





=======
>>>>>>> Stashed changes
