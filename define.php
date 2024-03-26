<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
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
        border-top: 8px solid #7EAB55;
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
    <div id="background">

    <div style="display: flex; justify-content: space-between;">
        <h1>1. Specify</h1>
        <form action="help.php#define">
            <input type="submit" value="Help" class="button" id="help-button" style="color: white; background-color: #0173bc;"/>
        </form>
    </div>
    <h2>What you can change? - Specify variables</h2>
    <p><i>An optimization scenario is shown below for optimizing the travel for a holiday, which you can edit to implement your optimisation.</i></p>

    <p><i>Describe each variable that you want to change. Examples: “destination distance”, “number of days” amd "number of flight connections".</i></p>
    
    <div id="parameter-table-div" style="text-align: center;">
        <table id="parameter-table" class="parameter-table" width="100%">
            <caption><b>Variables</b></caption>
            <thead>  
                <tr>  
                <th id="record-parameter-name" width="40%"> Name </th>   
                <th id="record-parameter-lower-bound"> Minimum </th>  
                <th id="record-parameter-upper-bound"> Maximum </th>  
                </tr>  
            </thead>  
            <tbody>
            <tr>
                <td contenteditable="true" class="record-data" id="record-parameter-name">Destination distance (km)</td>
                <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">500</td>
                <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">3000</td>
            </tr>
            <tr>
                <td contenteditable="true" class="record-data" id="record-parameter-name">Number of days</td>
                <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">3</td>
                <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">14</td>
            </tr>
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="record-parameter-name">Number of flight connections</td>
                <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">0</td>
                <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">3</td>
            </tr> -->
            </tbody>
        </table>
        <button class="button" id="add-record-button" onclick="addDesignParametersTable()" style="color: black; background-color: #D6EEEE;">Add More Variables</button>
    </div>

    <!-- <div id="progressBar"><div class="progress"></div> -->
    <br>

    <div id="loadingContainer">
    <div id="loadingIcon"></div>
    <div id="loadingText">Loading...</div>
    </div>

    <div style="text-align: right;">
        <button class="button" id="finish-objectives-button" style="width: 20%;" onclick="finishObjs()">Next</button>
    </div>
    

    </div>
    
    <script>
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


        function finishObjs() {

            var noError = true;
            var parameterNames = [];
            var parameterBounds = [];
            var objectiveNames = [];
            var objectiveBounds = [];
            var objectiveMinMax = [];
    
            /* var participantID = localStorage.getItem("id");
            var conditionID = localStorage.getItem("exp-condition");
            var applicationID = localStorage.getItem("app"); */
            
            var tableParam = $("#parameter-table tbody");
                
            tableParam.find('tr').each(function() {
                var $paramCols = $(this).find("td");
                var paramRowEntries = [];
    
                $.each($paramCols, function() {
                    paramRowEntries.push($(this).text());
                });
                
                var paramName = paramRowEntries[0];
                console.log("haha" + paramName);
                parameterNames.push(paramName);

                // if (/^[A-Za-z0-9]+$/.test(paramName)){
                //     parameterNames.push(paramName);
                // }
                // else {
                //     noError = false;
                // }
    
                var paramLowerBound = paramRowEntries[1];
                var paramUpperBound = paramRowEntries[2];
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

            // // Find all the objective names and bounds
            // var tableObjs = $("#objective-table tbody");
                
            // tableObjs.find('tr').each(function() {
            //     var $objCols = $(this).find("td");
            //     var objRowEntries = [];

            //     $.each($objCols, function() {
            //         objRowEntries.push($(this).text());
            //     });
                
            //     var objName = objRowEntries[0];
            //     objectiveNames.push(objName);

            //     // if (/^[A-Za-z0-9]+$/.test(objName)){
            //     //     objectiveNames.push(objName);
            //     // }
            //     // else {
            //     //     noError = false;
            //     // }

            //     // console.log("objRowEntries[3]",objRowEntries2[3]);

    
            //     var objLowerBound = objRowEntries[1];
            //     var objUpperBound = objRowEntries[2];
            //     var validLowerBound = (!isNaN(parseFloat(objLowerBound)) && isFinite(objLowerBound));
            //     var validUpperBound = (!isNaN(parseFloat(objUpperBound)) && isFinite(objUpperBound));

            //     if (validLowerBound && validUpperBound){
            //         if (parseFloat(objLowerBound) < parseFloat(objUpperBound)){
            //             var rowBounds = [parseFloat(objLowerBound), parseFloat(objUpperBound)];
            //             objectiveBounds.push(rowBounds);
            //         }
            //         else {
            //            noError = false;
            //         }
            //     }
            //     else {
            //         noError = false;
            //     }
            
            //     var selectedOption = $(this).find('select option:selected').text();
            //     objectiveMinMax.push(selectedOption);

            // });

            // var i = 1;
            // var z = 
            // Store whether each objective is to be minimised or maximised in a list  这部分可以移到
            // var min_max_1 = document.getElementById("min-max-1").value;
            // var min_max_2 = document.getElementById("min-max-2").value;
            // objectiveMinMax.push(min_max_1, min_max_2);





            // console.log(parameterNames);
            // console.log(parameterBounds);
            // console.log(objectiveNames);
            // console.log(objectiveBounds);
            console.log("objectiveMinMax",objectiveMinMax);

            if (parameterBounds.length != parameterNames.length && parameterBounds.length <= 1){
                noError = false;
            }

            // if (objectiveBounds.length != objectiveNames.length){
            //     noError = false;
            // }
    
            if (noError){
                localStorage.setItem("parameter-names", parameterNames);
                localStorage.setItem("parameter-bounds", parameterBounds);
                // localStorage.setItem("objective-names", objectiveNames);
                // localStorage.setItem("objective-bounds", objectiveBounds);
                // localStorage.setItem("objective-min-max", objectiveMinMax);
    
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
                    // var progressBar = $('#progressBar');
                    // progressBar.empty();                    
                    // submitReturned = true;
                    console.log("Success");
                    console.log(result.success)
                    console.log("result.parameterNames.length");
                    console.log(result.parameterNames.length)
                    console.log("result.parameterBounds.length");
                    console.log(result.parameterBounds.length)
                    console.log(result.objectiveNames)
                    console.log(result.objectiveBounds)
                    //[Log] ["Cost ($)", "Satisfaction (%)", "Goal"] (3) (define.php, line 268)
                    //[Log] ["100", "1000", "0", "100", "50", "600"] (6) (define.php, line 269)
                    var url = "define-2.php";
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
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-lower-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'></td>"
            htmlNewRow += "<td contenteditable='true' class='record-data' id='record-objective-upper-bound'><select id='min-max-3' style='font-family: calibri; font-size: medium;'><option value='minimise' selected='selected'>minimise</option><option value='maximise'>maximise</option></select></td>"
            htmlNewRow += "</td></tr>"
            $("#objective-table", window.document).append(htmlNewRow);  
        }
    </script>
    
    </body>
</html>






