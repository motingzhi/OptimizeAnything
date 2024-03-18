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


    <div id="dataDisplay"></div>

    <div id="container"></div>

        
    <div style="display: flex; justify-content: space-between;">
        <button class="button" id="back-button" onclick="history.back()">Go Back</button>
        <form action="define.php"><button id="restart-button" class="button" type="submit">Restart</button></form>
    </div>
     
    </div>
    <script type="module">
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
        // var bestSolutions = localStorage.getItem("best-solutions").split(",");
        var solutionNameList = localStorage.getItem("solution-name-list").split(",");
        var BestSolutionIndex = localStorage.getItem("BestSolutionIndex").split(",");
    
        import * as d3 from "https://cdn.jsdelivr.net/npm/d3@7/+esm";
           
        if (objectiveNames.length <= 2)
            {
                  const width = 680;
                  const height = 480;
                  const marginTop = 25;
                  const marginRight = 20;
                  const marginBottom = 35;
                  const marginLeft = 40;

                    // 定义数据数组
                    var dataTable = [];
                    
                    
                    // 将数据组合成对象数组
                    for (var i = 0; i < BestSolutionIndex.length; i++) {
                        var dataRow = {
                            "Solution name": solutionNameList[BestSolutionIndex[i]],
                            "Objective1": savedObjectives[BestSolutionIndex[i]*objectiveNames.length],
                            "Objective2": savedObjectives[BestSolutionIndex[i]*objectiveNames.length+1]
                        };
                        dataTable.push(dataRow);
                    }
                
                  // var dataTable = [
                  //     { "Solution name": "Solution1", "Objective1": 200, "Objective2": 300 },
                  //     { "Solution name": "Solution2", "Objective1": 250, "Objective2": 350 },
                  //     { "Solution name": "Solution3", "Objective1": 180, "Objective2": 400 },
                  //     // 添加更多行数据...
                  // ];
                  
                  // Define the horizontal scale.
                  
                 const x = d3.scaleLinear().domain(0,  objectiveBounds[1]]).range([0, width]);
                 const y = d3.scaleLinear().domain(0,  objectiveBounds[3]]).range([height, 0]);

                
                  // // Define the vertical scale.
                  // const y = d3.scaleLinear()
                  //     .domain(d3.extent(dataTable, d => d.Objective2)).nice()
                  //     .range([objectiveBounds[2]-objectiveBounds[2]*0.1,  objectiveBounds[3]+objectiveBounds[3]*0.1]);
                
                  // Create the container SVG.
                  const svg = d3.create("svg")
                      .attr("width", width)
                      .attr("height", height)
                      .attr("viewBox", [0, 0, width, height])
                      .attr("style", "max-width: 100%; height: auto;");
                
                  // Add the axes.
                  svg.append("g")
                      .attr("transform", `translate(0,${height - marginBottom})`)
                      .call(d3.axisBottom(x));
                
                  svg.append("g")
                      .attr("transform", `translate(${marginLeft},0)`)
                      .call(d3.axisLeft(y));
                
                  //Append a circle for each data point.
                  svg.append("g")
                    .selectAll("circle")
                    .data(dataTable)
                    .join("circle")
                      // .filter(d => d.body_mass_g)
                      .attr("cx", d => x(d.Objective1))
                      .attr("cy", d => y(d.Objective2))
                      .attr("r", 3)
                      .attr("fill", "steelblue");
                
                
                  svg.append("g")
                    .selectAll("text")
                        .data(dataTable)
                        .enter().append("text")
                        .attr("x", function(d) { return x(d.Objective1) - 10; }) // x 方向偏移一定距离
                        .attr("y", function(d) { return y(d.Objective2) - 10; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Solution name"]; });

                    svg.append("g")
                    .selectAll("text")
                        .data(dataTable)
                        .enter().append("text")
                        .attr("x", function(d) { return x(d.Objective1) - 10; }) // x 方向偏移一定距离
                        .attr("y", function(d) { return y(d.Objective2) - 40; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Objective1"]; });

                    svg.append("g")
                    .selectAll("text")
                        .data(dataTable)
                        .enter().append("text")
                        .attr("x", function(d) { return x(d.Objective1) + 80 ; }) // x 方向偏移一定距离
                        .attr("y", function(d) { return y(d.Objective2) - 40; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Objective2"]; });

                  svg.append("text")
                    .attr("x", 250)  // 使标签居中
                    .attr("y", 470)
                    .attr("text-anchor", "middle")
                    .text(objectiveNames[0]);
                
                // 添加 y 轴标签
                  svg.append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("x", -250)  // 使标签居中
                    .attr("y", 20)
                    .attr("text-anchor", "middle")
                    .text(objectiveNames[1]);
                
                  
                  container.append(svg.node());

            // svg.append("g")
            //     .attr("transform", `translate(${marginLeft},0)`)
            //     .call(d3.axisLeft(y));
            
            // // Append the SVG element.
            // container.append(svg.node());
            }
    

    
    </script>

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
        // var bestSolutions = localStorage.getItem("best-solutions").split(",");
        var solutionNameList = localStorage.getItem("solution-name-list").split(",");
        var solutionNameList = localStorage.getItem("solution-name-list").split(",");
        var BestSolutionIndex = localStorage.getItem("BestSolutionIndex").split(",");

        console.log("Saved solutions: " + savedSolutions);
        console.log("Saved objectives: " + savedObjectives);
        console.log("Normalised objective inputs: " + objectivesNormalised);
        // console.log("Best solutions: " + bestSolutions);




        var displayDiv = document.getElementById("dataDisplay");
        // 循环显示每个数据
        for (var i = 0; i < BestSolutionIndex.length; i++) {
            // 显示 data[i]
            
            n = i+1
            // var dataRow = data[i].join("<br>"); // 数组逐行显示
            displayDiv.innerHTML += "Option"+ n + ": " + solutionNameList[BestSolutionIndex[i]] + "<br>";
            displayDiv.innerHTML +=  "<br>";

            for (var x = 0; x < parameterNames.length; x++) {
                displayDiv.innerHTML += parameterNames[x] + ": " + savedSolutions[BestSolutionIndex[i]*parameterNames.length+x] + "<br>";
            }
            displayDiv.innerHTML +=  "<br>";

            displayDiv.innerHTML += "Objectives: "+ "<br>";

            if (BestSolutionIndex.length == 1)
            {
                for (var x = 0; x < objectiveNames.length; x++) {
                    displayDiv.innerHTML += objectiveNames[x] + ": " + savedObjectives[BestSolutionIndex[i]*objectiveNames.length+x] + "<br>";
                }

            }
            else {
                for (var x = 0; x < objectiveNames.length; x++) {
                    displayDiv.innerHTML += objectiveNames[x] + ": " + savedObjectives[BestSolutionIndex[i]*objectiveNames.length+x] + "<br>";
                }                         
            }
            
            displayDiv.innerHTML +=  "<br>";
            displayDiv.innerHTML +=  "<br>";




  
        }
       
        displayDiv.innerHTML +=  "<br>";
        displayDiv.innerHTML +=  "<br>";
        


        // var generatedSolution1 = [];
      

        // ///以下为了多parameter的情况：
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolution1[i] = parameterNames[i] + " : " + bestSolutions[i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolutionUI1 = document.getElementById("generatedSolution1");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution1.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolutionUI1.appendChild(listItem);
        // });
      



        // var generatedSolution2 = [];
      

        // ///以下为了多parameter的情况：
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolution2[i] = parameterNames[i] + " : " + bestSolutions[parameterNames.length+i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolutionUI2 = document.getElementById("generatedSolution2");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution2.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolutionUI2.appendChild(listItem);
        // });
        

        // console.log(savedObjectives);


        















        // var generatedSolution3 = [];
      

        // ///以下为了多parameter的情况：
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolution3[i] = parameterNames[i] + " : " + bestSolutions[parameterNames.length*2+i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolutionUI3 = document.getElementById("generatedSolution3");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution3.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolutionUI3.appendChild(listItem);
        // });
        



        // var generatedSolution1objective = [];
        // // var generatedSolutionobjective = [];
        
        // ///以下为了多parameter的情况：
        // for (var i = 0; i<objectiveNames.length; i++) {
        //     generatedSolution1objective[i] = objectiveNames[i] + " : " + savedObjectives[BestSolutionIndex[0]*objectiveNames.length+i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolution1objectiveUI = document.getElementById("generatedSolution1objective");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution1objective.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolution1objectiveUI.appendChild(listItem);
        // });











        // var generatedSolution2objective = [];
        // // var generatedSolutionobjective = [];
        
        // ///以下为了多parameter的情况：
        // for (var i = 0; i<objectiveNames.length; i++) {
        //     generatedSolution2objective[i] = objectiveNames[i] + " : " + savedObjectives[BestSolutionIndex[1]*objectiveNames.length+i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolution2objectiveUI = document.getElementById("generatedSolution2objective");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution2objective.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolution2objectiveUI.appendChild(listItem);
        // });




        // var generatedSolution3objective = [];
        // // var generatedSolutionobjective = [];

        // ///以下为了多parameter的情况：
        // for (var i = 0; i<objectiveNames.length; i++) {
        //     generatedSolution3objective[i] = objectiveNames[i] + " : " + savedObjectives[BestSolutionIndex[2]*objectiveNames.length+i];
        // }

        // // 获取要填充数据的 <ul> 元素
        // var generatedSolution3objectiveUI = document.getElementById("generatedSolution3objective");

        // // 循环遍历数组并将每个元素添加为列表项
        // generatedSolution3objective.forEach(function(element) {
        // var listItem = document.createElement("li");
        // listItem.textContent = element;
        // generatedSolution3objectiveUI.appendChild(listItem);
        // });



        // var generatedSolutionobjective;
        // var generatedSolutionobjectiveUI;

        // for  (var x = 0; x<4; x++) {
        // ///以下为了多parameter的情况：
        //     for (var i = 0; i<objectiveNames.length; i++) {
        //         generatedSolutionobjective[x][i] = objectiveNames[i] + " : " + savedObjectives[solutionNameIndex[x]*objectiveNames.length+i];
        //     }

        //     // 获取要填充数据的 <ul> 元素
        //     generatedSolutionobjectiveUI[x] = document.getElementById("generatedSolutionobjective");

        //     // 循环遍历数组并将每个元素添加为列表项
        //     generatedSolutionobjective[x].forEach(function(element) {
        //     var listItem = document.createElement("li");
        //     listItem.textContent = element;
        //     generatedSolutionobjectiveUI[x].appendChild(listItem);
        //     });
        // }

      





        
    </script>
</body>
</html>


