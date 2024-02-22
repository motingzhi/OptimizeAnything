<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div id="background">
    
    <div style="display: flex; justify-content: space-between;">
        <h1>One question before we start...</h1>
        <form action="help.php#existing-solutions">
            <input type="submit" value="Help" class="button" id="help-button" style="color: white; background-color: #0173bc;"/>
        </form>
    </div>
    <p><i>Are there known bad solutions we should include?</i></p>
    
    <div class="tooltip">For example
        <span class="tooltiptext" style="display: flex; justify-content: space-between; padding: 0px 5px;">
                <p id="parameter1_example"></p>
                <p id="parameter2_example"></p>
                <!-- <p class="parameter3"></p><p>1</p> -->
        </span>
    </div>
    <br>
    <br>
    <br>
    <div id="buttons" style="display:block">
        <div class="add-existing-solutions" style=" margin-right:2%; float: left;">
            <button id="add-existing-solutions" class="button" onclick="addExistingSolutions()">Yes, some</button>
        </div>

        <div class="start-button" style=" float: left;">
            <button id="start" class="button" onclick="finishSolutions()">No let's start</button>
        </div>

        <!-- <div class="start-button">
            <form action="/Demo/optimise.php" id="start-form">
                <button id="start" type="submit">No, let's start</button>
            </form>
        </div> -->

        <div class="clearfix" style="clear: both;"></div>
    </div>




    <div id="add-solutions" style="display:none">
        <div id="table-container"></div>
        <!-- <table id="good-solutions-table" class="good-solutions-table" width="100%">
            <caption><b>Good Solutions</b></caption>
            <thead>  
                <tr>  
                <th class="parameter1"></th>  
                <th class="parameter2"></th> -->  
                <!-- <th class="parameter3"></th>  -->
                <!-- <th class="delete"> Delete </th>   
                </tr>  
            </thead>  
            <tbody>
            </tbody>
        </table>

        <div style="text-align: center;">
            <button class="button" id="add-record-button" onclick="addGoodSolutionsTable()">Add Good Solution</button>
        </div> 
        <br> -->
        <!-- <table id="bad-solutions-table" class="bad-solutions-table" width="100%"> -->
            <!-- <caption><b>Bad Solutions</b></caption>
            <thead>  
                <tr>  
                <th class="parameter1"></th>  
                <th class="parameter2"></th>   -->
                <!-- <th class="parameter3"></th>  -->
                <!-- <th class="delete"> Delete </th>    -->
                <!-- </tr>  
            </thead>  
            <tbody>
            </tbody>
        </table> -->

        <div style="text-align: center;">
            <button class="button" id="add-record-button" onclick="addBadSolutionsTable()" style="color: black; background-color: #D6EEEE;">Add Bad Solution</button>
        </div>
        <br>

        <div style="text-align: right;">
            <button class="button" id="finish-solutions-button" onclick="finishSolutions()">Finish</button>
        </div>
        
        <!--
        <div style="display: flex; justify-content: space-between;">
            <button class="button" id="back-button" onclick="addExistingSolutions()">Back</button>
            <button class="button" id="finish-solutions-button" onclick="finishSolutions()">Finish</button>
        </div>
        -->
    </div>

    <style>
        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 400px;
            background-color: #6a6e73;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            
            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            top: -5px;
            left: 105%;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }

    </style>
    <script>
        var parameterNames = localStorage.getItem("parameter-names").split(",");
        var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
        var objectiveNames = localStorage.getItem("objective-names").split(",");
        var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
        var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");
	var solutionNameList =  "";



        var table;


        function addExistingSolutions() {
            var x = document.getElementById('add-solutions');
            var y = document.getElementById('buttons')
            if (x.style.display == 'none') {
                x.style.display = 'block';
                y.style.display = 'none';
            }
            else {
                x.style.display = 'none';
                y.style.display = 'block';
            }
            // 获取包含表格的容器元素
            var tableContainer = document.getElementById('table-container');

            // 创建表格元素
            table = document.createElement('table');

            // 创建表头元素
            var thead = document.createElement('thead');
            var tr = document.createElement('tr');

            // 遍历参数名数组，为每个参数创建一个表头单元格
            parameterNames.forEach(function(parameterName) {
                var th = document.createElement('th');
                th.textContent = parameterName;
                tr.appendChild(th);
            });

            // 将表头行添加到表头
            thead.appendChild(tr);

            // 将表头添加到表格
            table.appendChild(thead);

            // 将表格添加到容器中
            tableContainer.appendChild(table);

        }

        // function addGoodSolutionsTable(){
        //     var htmlNewRow = ""
        //     htmlNewRow += "<tr>"
        //     htmlNewRow += "<td contenteditable='true' class='record-data' id='parameter1'></td>"
        //     htmlNewRow += "<td contenteditable='true' class='record-data' id='parameter2'></td>"
        //     // htmlNewRow += "<td contenteditable='true' class='record-data' id='parameter3'></td>"
        //     htmlNewRow += "<td id='record-data-buttons'>"
        //     htmlNewRow += "<button class='record-delete' id='record-delete'><img src='./Pictures/delete.png' style='width: 20px'></button>"
        //     htmlNewRow += "</td></tr>"
        //     $("#good-solutions-table", window.document).append(htmlNewRow);  
        //     $(window.document).on('click', ".record-delete", deleteParameterTable);
        // }

        // for (i = 0; i < parameterNames.length; i++) {
        //         var htmlNewRow = ""
        //         htmlNewRow += "<tr>"
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + parameterNames[i]  +  " </td>"
        //         htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement'> " + "Enter measurement (" + parameterBounds[2*i] + "-" + parameterBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
        //         // htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' placeholder='Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")'> </td>"
        //         // htmlNewRow += "<td id='record-data-buttons'>"
        //         htmlNewRow += "</td></tr>"
        //         $("#bad-solutions-table", window.document).append(htmlNewRow);
        //     }




        function addBadSolutionsTable(){

            // 创建新的表格行
            var newRow = document.createElement('tr');

            // 使用与列数相同的循环来创建空的单元格，并将它们添加到新行中
            for (var i = 0; i < parameterNames.length; i++) {
                var td = document.createElement('td');
                td.textContent =  "(" + parameterBounds[2*i] + "-" + parameterBounds[2*i+1] + ")" ;
                td.setAttribute('contenteditable', true);
                newRow.appendChild(td);
            }

            // 将新行添加到表格主体中
            table.appendChild(newRow);

        }

        function deleteParameterTable(){
            $(this).parents('tr').remove();
        }

        function finishSolutions() {
            var noError = true;
            var newSolution = true;
            var nextEvaluation = false;
            var refineSolution = false;
            var goodSolutions = [];
            var badSolutions = [];
            // Register good solutions
            // var tableGoodSols = $("#good-solutions-table tbody");
            // tableGoodSols.find('tr').each(function() {
            //     var $goodSolsCols = $(this).find("td");
            //     var goodSolsRowEntries = [];
    
            //     $.each($goodSolsCols, function() {
            //         goodSolsRowEntries.push($(this).text());
            //     });
    
            //     var goodSolParam1 = goodSolsRowEntries[0];
            //     var goodSolParam2 = goodSolsRowEntries[1];
            //     // var goodSolParam3 = goodSolsRowEntries[2];
            //     console.log(goodSolParam1);
            //     var validGoodSolParam1 = (!isNaN(parseFloat(goodSolParam1)) && isFinite(goodSolParam1) && parseFloat(goodSolParam1) >= parseFloat(parameterBounds[0]) && parseFloat(goodSolParam1) <= parseFloat(parameterBounds[1]));
            //     var validGoodSolParam2 = (!isNaN(parseFloat(goodSolParam2)) && isFinite(goodSolParam2) && parseFloat(goodSolParam2) >= parseFloat(parameterBounds[2]) && parseFloat(goodSolParam2) <= parseFloat(parameterBounds[3]));
            //     // var validGoodSolParam3 = (!isNaN(parseFloat(goodSolParam3)) && isFinite(goodSolParam3));

            //     if (validGoodSolParam1 && validGoodSolParam2 /*&& validGoodSolParam3*/){
            //         var rowBounds = [parseFloat(goodSolParam1), parseFloat(goodSolParam2)/*, parseFloat(goodSolParam3)*/];
            //         goodSolutions.push(rowBounds);
            //     }
            //     else {
            //         noError = false;
            //     }
            // });
            
            // Register bad solutions
            var $table = $(table);
            $table.find('tr').slice(1).each(function() {
                var $badSolsCols = $(this).find("td");
                var badSolsRowEntries = [] ;
                var badSolParam  = [] ;
                $.each($badSolsCols, function() {
                    badSolsRowEntries.push($(this).text());
                    console.log(badSolsRowEntries);
                });
                
                for (var i = 0; i < parameterNames.length; i++) {
                    badSolParam.push(badSolsRowEntries[i]);
                }        

                var validBadSolParam = true;
                console.log("badSolParam",badSolParam);

                for (var i = 0; i < parameterNames.length; i++) {
                    validBadSolParam = (!isNaN(parseFloat(badSolParam[i])) && isFinite(badSolParam[i]) && parseFloat(badSolParam[i]) >= parseFloat(parameterBounds[2*i]) && parseFloat(badSolParam[i]) <= parseFloat(parameterBounds[2*i+1]));
                    if (validBadSolParam == false){
                        break;
                }}
               
                var rowBounds = [];
                if (validBadSolParam == true ){
                    for (var i = 0; i < parameterNames.length; i++) {
                        rowBounds.push(parseFloat(badSolParam[i]));
                    } 
                    badSolutions.push(rowBounds);
                }
                else {
                    noError = false;
                }
                for (var i = 0; i < parameterNames.length; i++) {
                        rowBounds.push(parseFloat(badSolParam[i]));
                    } 
                badSolutions.push(rowBounds);
                console.log("badSolutions",badSolutions);
            });

            //console.log(goodSolutions);
            //console.log(badSolutions);

            if (noError){
                // localStorage.setItem("parameter-names", parameterNames);
                // localStorage.setItem("parameter-bounds", parameterBounds);
                // localStorage.setItem("objective-names", objectiveNames);
                // localStorage.setItem("objective-bounds", objectiveBounds);
                localStorage.setItem("objective-min-max", objectiveMinMax);
                localStorage.setItem("good-solutions", goodSolutions);
                localStorage.setItem("bad-solutions", badSolutions);
                localStorage.setItem("new-solution", newSolution);
                localStorage.setItem("next-evaluation", nextEvaluation);
                localStorage.setItem("solution-name-list", solutionNameList);

                $.ajax({
                    url: "./cgi/newSolution_u.py",
                    type: "post",
                    dataType: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),
                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'new-solution'       :String(newSolution),
                            'next-evaluation'    :String(nextEvaluation),
 			    'solution-name-list'      :String(solutionNameList),
                            'refine-solution'    :String(refineSolution)},
                    success: function(result) {
                        submitReturned = true;
                        solution = result.solution;
                        objectivesInput = result.objectives;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        localStorage.setItem("solution-list", solution);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        // console.log(parameterNames);
                        // console.log(solution);
                        // console.log("Result_Message");
                        // console.log(result.message);
                        // console.log("Result");
                        // console.log(result);
                        // console.log("Full results");
                        // console.log(result.tester);
                        // console.log(result.solution);
                        // console.log(result.objectives);
                        // console.log(result.bad_solutions);
                        // console.log(result.saved_solutions);
                        // console.log(result.xx);
                        // console.log(result.parameterNames);
                        var url = "optimise_withnewsolution.php";
                        location.href = url;
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                        console.log("Error in finishing experiment: " + result);
                        console.log("Error in finishing experiment: " + result.success);
                    }
                });
            }
            else {
                alert("Invalid entry");
            } 
        }
    </script>
</body>
</html>
    
    

<!-- 学会了用端口debug -->
