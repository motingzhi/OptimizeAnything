<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

    <div id="background">
    
    <div style="display: flex; justify-content: space-between;">
        <h1>2. Optimise</h1>
        <form action="/help.php#optimisation">
            <input type="submit" value="Help" class="button" id="help-button" style="color: white; background-color: #0173bc;"/>
        </form>
    </div>

    <p><i>Let AI suggest solutions with you. Please evaluate at least 3 solutions to proceed.</i></p>
    
    <p><b>Solution idea</b><p>

    <p class="parameter_1_mobo"></p>
    <p class="parameter_2_mobo"></p>
    <!-- <p class="generatedSolution"></p> -->


    <!-- 自己改的 -->
    <ul id="generatedSolution" style="list-style-type: none;"></ul>

    <!-- 自己改的 -->


    
    <div id="options" style="display: inline-block; margin: 0 auto;">
        <button class="button" id="evaluate-button" style="width: 40%;" onclick="evaluateSolution()">I want to evaluate this</button>
        <button class="button" id="skip-button" style="width: 40%;" onclick="newSolution()">Skip. I know it's not good</button>
    </div>
    <br>
    <div id="evaluate-solution" style="display: none;">
        <label for="solution_name">Solution name: </label>
        <input size="40" id = "solution_name" placeholder="Give a memorable name to this idea"><br><br>



        <!-- 原有的用来显示measurement的代码  -->

        <!-- <label for="obj1" class="objective_1_name"></label>
        <input size="30" type="text" id="obj1" name="obj1" placeholder="Enter measurement"><br>
        <label for="obj2" class="objective_2_name"></label>
        <input size="30" type="text" id="obj2" name="obj2" placeholder="Enter measurement"><br>
        <br> -->

        <!-- 原有的用来显示measurement的代码  -->



        <!-- 我新加的 -->

        <table id="measurement-table" class="measurement-table" width="100%">
            <!-- <thead>  
                <tr>  
                <th id="record-parameter-name" width="40%"> Name </th>   
                <th id="record-parameter-lower-bound"> Minimum </th>  
                <th id="record-parameter-upper-bound"> Maximum </th>  
                </tr>  
            </thead>   -->
            
            <tbody>
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="display-measurement-name"></td>
                <td contenteditable="true" class="record-data" id="record-measurement"></td>
            </tr> -->
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="display-measurement-name"></td>
                <td contenteditable="true" class="record-data" id="record-measurement"></td>
            </tr> -->
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="record-parameter-name">Number of flight connections</td>
                <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">0</td>
                <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">3</td>
            </tr> -->
            </tbody>
        </table>




        <!-- 我新加的 -->





        <div id="form-options" style="display: inline-block; margin: 0 auto;">
            <button class="button" id="next-button" onclick="nextEvaluation()">Give me the next one</button>
            <button class="button" id="skip-button" onclick="refineSolution()">I want to refine this</button>
        </div>
    </div>
    <br>
    <div id="done-button" class="done-button" style="text-align: right;">
        <button class="button" id="done" onclick="finishSolutions()">I'm done</button>    
    </div>

    </div>

    <script>
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

        
        try {
        var objectiveMeasurements = localStorage.getItem("objective-Measurements").split(",");
        } catch (err) {
        // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
        var objectiveMeasurements = [];
        }
        // 现在，objectiveMeasurements 包含 localStorage 中 "saved-objectives" 的值，如果不存在则为一个空数组


        
        var num_parameters = parameterNames.length

        try {var objectivesInput = localStorage.getItem("objectives-input").split(",");}
        catch(err) {}
        
        // console.log("Current solutions: " + solutionList);
        // console.log("Objectives input: " + objectivesInput);
        // console.log("Bad solutions: " + badSolutions);
        // console.log("Saved solutions: " + savedSolutions);
        // console.log("Saved objectives: " + savedObjectives);

//////////////////自己加的 
////用 var = [] 定义一个数组，必须要有[]
        var generatedSolution = [];
      
        console.log(solutionList);


        for (var i = 0; i<parameterNames.length; i++) {
            generatedSolution[i] = parameterNames[i] + " =  " + solutionList[i];
        }

        console.log(generatedSolution);

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI = document.getElementById("generatedSolution");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI.appendChild(listItem);
        });
        
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolutionUI[i].innerHTML = parameterNames[i] + " =  " + solutionList[solutionList.length-2];
        // }



//////////////////自己加的

        // var paras1 = document.getElementsByClassName("parameter_1_mobo");
        // var paras2 = document.getElementsByClassName("parameter_2_mobo");
        // console.log(parameterNamesLength);
        // var paras3 = document.getElementsByClassName("parameter_3_mobo");
        
        // for (i = 0; i < paras1.length; i++) {
        //     paras1[i].innerHTML = parameterNames[0] + " =  " + solutionList[solutionList.length-2];
        //     paras2[i].innerHTML = parameterNames[1] + " =  " + solutionList[solutionList.length-1];
        //     //  paras3[i].innerHTML = parameterNames[2] + " =  " + solutionList[solutionList.length-1];
        // }


        // paras1[0].innerHTML = parameterNames[0] + " =  " + solutionList[solutionList.length-2];
        // paras2[0].innerHTML = parameterNames[1] + " =  " + solutionList[solutionList.length-1];
            //  paras3[i].innerHTML = parameterNames[2] + " =  " + solutionList[solutionList.length-1];




        if (savedSolutions.length >= num_parameters*3) {
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

        function newSolution() {
            callNewSolution = true;
            callNextEvaluation = false;
            callRefineSolution = false;
            // badSolutions.push(solutionList[solutionList.length-2], solutionList[solutionList.length-1])
            // Placeholders
            solutionName = "";
            objectiveMeasurements = "";

            //console.log("Sending AJAX request to server...");


            //localStorage.setItem("objective-measurements", objectiveMeasurements);




            $.ajax({
                url: "../Demo/cgi/newSolution.py",

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

                success: function(result) {
                    submitReturned = true;
                    solutionList = result.solution;
                    objectivesInput = result.objectives;
                    badSolutions = result.bad_solutions;
                    savedSolutions = result.saved_solutions;
                    savedObjectives = result.saved_objectives;
                    console.log("Success-newSolution_Reply_list");
                    console.log(result.solution[0]);
                    console.log(result.tester);
                    console.log(result.solution_normalised);
                    localStorage.setItem("solution-list", solutionList);
                    localStorage.setItem("objectives-input", objectivesInput);
                    localStorage.setItem("bad-solutions", badSolutions);
                    localStorage.setItem("saved-solutions", savedSolutions);
                    localStorage.setItem("saved-objectives", savedObjectives);
                    console.log(result);
                    console.log("Success-newSolution_Reply_list_ends");
                    var url = "optimise_copy.php";
                    location.href = url;
            
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

        function evaluateSolution() {


          ////////我加的  


          var x = document.getElementById('evaluate-solution');
            var y = document.getElementById('options')
            if (x.style.display == 'none') {
                x.style.display = 'block';
                y.style.display = 'none';
            }
            else {
                x.style.display = 'none';
                y.style.display = 'inline-block';
            }
        
           for (i = 0; i < objectiveNames.length; i++) {
                var htmlNewRow = ""
                htmlNewRow += "<tr>"
                htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>"
                htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
                // htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' placeholder='Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")'> </td>"
                // htmlNewRow += "<td id='record-data-buttons'>"
                htmlNewRow += "</td></tr>"
                $("#measurement-table", window.document).append(htmlNewRow);
            }

 

          ////////我加的  

            // var obj1_name = document.getElementsByClassName("objective_1_name");
            // var obj2_name = document.getElementsByClassName("objective_2_name");

            // for (i = 0; i < paras1.length; i++) {
            //     obj1_name[i].innerHTML = objectiveNames[0] + " = ";
            //     obj2_name[i].innerHTML = objectiveNames[1] + " = ";
            // }
            
            // document.getElementById("obj1").placeholder = "Enter measurement ("+objectiveBounds[0]+"-"+objectiveBounds[1]+")";
            // document.getElementById("obj2").placeholder = "Enter measurement ("+objectiveBounds[2]+"-"+objectiveBounds[3]+")";


        }



        function nextEvaluation() {


            noError = true;

            callNewSolution = false;
            callNextEvaluation = true;
            callRefineSolution = false;

            var solutionName = document.getElementById("solution_name").value;


//////////////自己加的

            var tableParam = $("#measurement-table tbody");
                
            tableParam.find('tr').each(function() {
                var $paramCols = $(this).find("td");
                var paramRowEntries = [];
    
                $.each($paramCols, function() {
                    paramRowEntries.push($(this).text());
                });
                
                var objElement = paramRowEntries[1];
                objectiveMeasurements.push(objElement);
                // console.log("haha" + objectiveMeasurements);

                                
            });

            // console.log("test33",objectiveMeasurements)

//////////////自己加的

            // for (let i = 0; i < objectiveMeasurements.length; i++) {
            //     var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
            //     && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
            //     if (validObj1 == false){
            //         noError = false;
            //         break
            //     }

            // }



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
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                console.log("test34",objectiveMeasurements)
    
                $.ajax({
                    url: "../Demo/cgi/test_copy.py",
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
                            'objective-measurements'   :String(objectiveMeasurements)},

                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution;
                        objectivesInput = result.objectives;
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);

                        console.log("Success-nextevaluation");
                        console.log(result.solution_normalised);
                        console.log(result.test2);
                        console.log("Success-nextevaluation-reply-ends");

                        var url = "optimise_copy.php";
                        location.href = url;
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                        console.log(parameterBounds);
                    console.log("Current solutions: " + solutionList);
                    console.log("Objectives input: " + objectivesInput);
                    console.log("Bad solutions: " + badSolutions);
                    console.log("Saved solutions: " + savedSolutions);
                    console.log("Saved objectives: " + savedObjectives);
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


            
            var obj1 = document.getElementById("obj1").value;
            var obj2 = document.getElementById("obj2").value;
            
            console.log(solutionName, obj1, obj2);

            var validObj1 = (!isNaN(parseFloat(obj1)) && isFinite(obj1)
                && parseFloat(obj1) >= objectiveBounds[0] && parseFloat(obj1) <= objectiveBounds[1]);
            var validObj2 = (!isNaN(parseFloat(obj2)) && isFinite(obj2)
                && parseFloat(obj2) >= objectiveBounds[2] && parseFloat(obj2) <= objectiveBounds[3]);

            if (validObj1 && validObj2) {
                noError = true;
            }
            else {
                noError = false;
            }

            // if (/^[A-Za-z0-9]+$/.test(solutionName) == false){
            //     noError = false;
            // }

            if (noError) {
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-1", obj1);
                localStorage.setItem("objective-2", obj2);
    
                $.ajax({
                    url: "../Demo/cgi/test_copy.py",
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
                            'objective-1'        :String(obj1),
                            'objective-2'        :String(obj2) },

                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution
                        objectivesInput = result.objectives
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;

                        new_x = result.new_x
                        console.log(new_x)
                        console.log(result.solution)
                        console.log(result.objectives)
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);

                        console.log("Success-refineevaluation");
                        var url = "optimise_copy.php";
                        location.href = url;
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

            if (noError) {
                $.ajax({
                    url: "../Demo/cgi/finish-solutions.py",
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
                            'objectives-input'   :String(objectivesInput)},

                    success: function(result) {
                        submitReturned = true;
                        // solutionList = result.solution;
                        // badSolutions = result.bad_solutions;
                        objectivesInput = result.objectives;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        objectivesNormalised = result.objectives_normalised;
                        bestSolutions = result.best_solutions;
                        console.log(objectivesInput);
                        console.log(savedSolutions);
                        console.log(objectivesNormalised);
                        console.log(bestSolutions)
                        // localStorage.setItem("solution-list", solutionList);
                        // localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("objectives-normalised", objectivesNormalised);
                        localStorage.setItem("best-solutions", bestSolutions);

                        console.log("Success");
                        var url = "results.php";
                        location.href = url;
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


