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
    $savedsolutions = json_encode($_POST['saved-solutions']);
    $savedobjectives = json_encode($_POST['saved-objectives']);
    $solutionlist = json_encode($_POST['solution-list']);
    $saved_timestamp = json_encode(date("Y-m-d H:i:s"));

    $stmt = $conn->prepare("UPDATE data SET Savedsolutions = ?, Savedobjectives = ?, Solutionlist = ?, saved_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $savedsolutions, $savedobjectives, $solutionlist, $saved_timestamp, $userID);
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
<html>
<head>
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
            bottom: 20px;
            width: 100%;
            background: #f8f9fa; /* Light grey background similar to Bootstrap's default navbar */
            padding: 10px 0;
            /* box-shadow: none; */
             /* Shadow for the bottom bar */

            box-shadow: 0 -2px 4px rgba(0,0,0,0.1); Shadow for the bottom bar
        }

    </style>  
</head>
<body>
    <div id="background">

    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>3. Results</h1>
            <form action="tutorial_1.php">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>
        </div>
    </div>   
    
    <div class="centered-content">

        <p>Here are the best options we found</p>


        <div id="dataDisplay"></div>

        <div id="container"></div>
        <!-- <button class="btn btn-outline-primary" id="download-button">Download</button> -->

        <p>Please don't close this window yet!</p>

        <div style="display: flex; justify-content: space-between;">

            <!-- <button class="btn btn-outline-success" id="back-button" onclick="history.back()">Go Back</button> -->
            <form action="define.php"><button id="restart-button" class="btn btn-outline-success" type="submit">Restart</button></form>
            <form action="https://link.webropolsurveys.com/S/645BAC578DBFDD62"><button id="restart-button" class="btn btn-success" type="submit">Continue to the questionnaire</button></form>

        </div>
    
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
        var solutionList = localStorage.getItem("solution-list").split(",");
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
                  const width = 720;
                  const height = 500;
                  const marginTop = 25;
                  const marginRight = 40;
                  const marginBottom = 80;
                  const marginLeft = 80;

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
                  
                 // const x = d3.scaleLinear().domain([0,  objectiveBounds[1]]).range([0, width]);

                  const x = d3.scaleLinear()
      .domain([0,  objectiveBounds[1]]).nice()
      .range([marginLeft, width - marginRight]);

                const y = d3.scaleLinear()
      .domain([0,  objectiveBounds[3]]).nice()
      .range([height - marginBottom, marginTop]);
                
                 // const y = d3.scaleLinear().domain([0,  objectiveBounds[3]]).range([height, 0]);

                
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
                        .attr("y", function(d) { return y(d.Objective2) + 20; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Solution name"]; });

                    svg.append("g")
                    .selectAll("text")
                        .data(dataTable)
                        .enter().append("text")
                        .attr("x", function(d) { return x(d.Objective1) - 60; }) // x 方向偏移一定距离
                        .attr("y", function(d) { return y(d.Objective2) - 10; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Objective1"]; });

                    svg.append("g")
                    .selectAll("text")
                        .data(dataTable)
                        .enter().append("text")
                        .attr("x", function(d) { return x(d.Objective1) - 10 ; }) // x 方向偏移一定距离
                        .attr("y", function(d) { return y(d.Objective2) - 10; }) // y 方向偏移一定距离
                        .text(function(d) { return d["Objective2"]; });

                  svg.append("text")
                    .attr("x", 250)  // 使标签居中
                    .attr("y", 480)
                    .attr("text-anchor", "middle")
                    .text(objectiveNames[0]);
                
                // 添加 y 轴标签
                  svg.append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("x", -230)  // 使标签居中
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
            
            // n = i+1
            // var dataRow = data[i].join("<br>"); // 数组逐行显示
            displayDiv.innerHTML +=  solutionNameList[BestSolutionIndex[i]] + "</b><br>";

            // displayDiv.innerHTML += "<b>Option"+ n + ": " + solutionNameList[BestSolutionIndex[i]] + "</b><br>";
            // displayDiv.innerHTML +=  "<br>";

            for (var x = 0; x < parameterNames.length; x++) {
                displayDiv.innerHTML += parameterNames[x] + ": " + savedSolutions[BestSolutionIndex[i]*parameterNames.length+x] + "<br>";
            }
            // displayDiv.innerHTML +=  "<br>";

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
            // displayDiv.innerHTML +=  "<br>";
        }
       
        displayDiv.innerHTML +=  "<br>";
        document.getElementById('download-button').addEventListener('click', function() {
        var solutionList = localStorage.getItem("solution-list");
        var savedSolutions = localStorage.getItem("saved-solutions");
        var savedObjectives = localStorage.getItem("saved-objectives");

        var content = "Solution List:\n" + solutionList + "\n\n" +
                      "Saved Solutions:\n" + savedSolutions + "\n\n" +
                      "Saved Objectives:\n" + savedObjectives + "\n\n";

        var blob = new Blob([content], { type: 'text/plain' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'solutions.txt';

        document.body.appendChild(a);
        a.click();

        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    });

        
    </script>
</body>
</html>


