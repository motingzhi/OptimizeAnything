<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="background">
    
    <div style="display: flex; justify-content: space-between;">
        <h1>3. Results</h1>
        <form action="help.php#results">
            <input type="submit" value="Help" class="button" id="help-button" style="color: white; background-color: #0173bc;"/>
        </form>
    </div>

    <p><i>Here are the best options we found</i></p>
    
    <p><b>Option 1</b></p>
    <p class="generatedSolution1-name"></p>
    <ul id="generatedSolution1" style="list-style-type: none;"></ul>
    <!-- <p id="option_1_text" style="font-style: italic"></p> -->
    <br>
    <p><b>Option 2</b></p>
    <p class="generatedSolution2-name"></p>
    <ul id="generatedSolution2" style="list-style-type: none;"></ul>
    <!-- <p id="option_2_text" style="font-style: italic"></p> -->
    <br>
    <p><b>Option 3</b></p>
    <p class="generatedSolution3-name"></p>
    <ul id="generatedSolution3" style="list-style-type: none;"></ul>
    <!-- <p id="option_3_text" style="font-style: italic"></p> -->

   
  





    <br>
    <!-- <div class="restart-button" style="text-align: left;">
        <form action="/Demo/define.php">
            <button id="restart-button" class="button" type="submit">Restart</button>
        </form>
    </div> -->

    <div style="display: flex; justify-content: space-between;">
        <button class="button" id="back-button" onclick="history.back()">Go Back</button>
        <form action="define.php"><button id="restart-button" class="button" type="submit">Restart</button></form>
    </div>

    </div>

    <script>
        var parameterNames = localStorage.getItem("parameter-names").split(",");
        var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
        var objectiveNames = localStorage.getItem("objective-names").split(",");
        var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
        var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");
        // var goodSolutions = localStorage.getItem("good-solutions").split(",");
        // var badSolutions = localStorage.getItem("bad-solutions").split(",");
        // var solutionList = localStorage.getItem("solution-list").split(",");
        var savedSolutions = localStorage.getItem("saved-solutions").split(",");
        var savedObjectives = localStorage.getItem("saved-objectives").split(",");
        // var objectivesInput = localStorage.getItem("objectives-input").split(",");
        var objectivesNormalised = localStorage.getItem("objectives-normalised").split(",");
        var bestSolutions = localStorage.getItem("best-solutions").split(",");
        var solutionNameList = localStorage.getItem("solution-name-list").split(",");
        var solutionNameList = localStorage.getItem("solution-name-list").split(",");
        var solutionNameIndex = localStorage.getItem("solution-name-index").split(",");

        console.log("Saved solutions: " + savedSolutions);
        console.log("Saved objectives: " + savedObjectives);
        console.log("Normalised objective inputs: " + objectivesNormalised);
        console.log("Best solutions: " + bestSolutions);


        var paras1 = document.getElementsByClassName("generatedSolution1-name");
        var paras2 = document.getElementsByClassName("generatedSolution2-name");
        var paras3 = document.getElementsByClassName("generatedSolution3-name");

        paras1[0].innerHTML = solutionNameList[solutionNameIndex[0]];
        paras2[0].innerHTML = solutionNameList[solutionNameIndex[1]];
        paras3[0].innerHTML = solutionNameList[solutionNameIndex[2]];

        // var generatedSolution1 = [];
      
        // // console.log(solutionList);

        // ///以下为了多parameter的情况：
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolution1[i] = parameterNames[i] + " : " + bestSolutions[parameterNames.length*i+6];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolutionUI1 = document.getElementById("generatedSolution1");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution1.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolutionUI1.appendChild(listItem);
        // });
        


        var generatedSolution1 = [];
      

        ///以下为了多parameter的情况：
        for (var i = 0; i<parameterNames.length; i++) {
            generatedSolution1[i] = parameterNames[i] + " : " + bestSolutions[i];
        }

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI1 = document.getElementById("generatedSolution1");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution1.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI1.appendChild(listItem);
        });
      



        var generatedSolution2 = [];
      

        ///以下为了多parameter的情况：
        for (var i = 0; i<parameterNames.length; i++) {
            generatedSolution2[i] = parameterNames[i] + " : " + bestSolutions[parameterNames.length+i];
        }

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI2 = document.getElementById("generatedSolution2");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution2.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI2.appendChild(listItem);
        });
        


        















        var generatedSolution3 = [];
      

        ///以下为了多parameter的情况：
        for (var i = 0; i<parameterNames.length; i++) {
            generatedSolution3[i] = parameterNames[i] + " : " + bestSolutions[parameterNames.length*3+i];
        }

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI3 = document.getElementById("generatedSolution3");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution3.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI3.appendChild(listItem);
        });
        


      
    </script>
</body>
</html>

