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
    $objective_timestamp = json_encode(date("Y-m-d H:i:s")); // 将时间戳转换为JSON格式/ 格式化时间戳为字符串

    $stmt = $conn->prepare("UPDATE data SET objectivename = ?, objectivebounds = ?, objective_timestamp = ? WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $objectiveNames, $objectiveBounds, $objective_timestamp, $userID);
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
    <title>Optimization Page 1</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .hidden {
            display: none;
        }
        .inline-input {
            width: auto;
            display: inline-block;
            min-width: 100px;
            max-width: 200px;
        }
        .colored-placeholder::placeholder {
            color: blue;
        }
    </style>
</head>
<body>
        <div class="card-body" id="secondCard">
                <div>
                    <p>You want to make 
                        <input type="text" id="defineWhat" class="form-control mb-2 inline-input" placeholder="what"> 
                        as 
                        <input type="text" id="defineGood" class="form-control mb-2 inline-input" placeholder="i.e.'low'/ 'high'"> 
                        as possible for 
                        <input type="text" id="defineFor" class="form-control mb-2 inline-input" readonly>
                        that you just input.
                    </p>
                </div>

            </div>

        <button class="btn btn-outline-success" id="back-button" onclick="history.back()" style="width: 20%;">Redefine</button>

        <button class="btn btn-success" id="confirm-definitions-button" onclick="confirmDefinitions()" style="width: 20%;">Confirm</button>

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

            console.log(parameterBounds);

            // parameterNames = document.getElementById('defineWhat').value;
            // objectiveNames = document.getElementById('defineGood').value;
            // objectiveMinMax = document.getElementById('defineFor').value;

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
                    //向下一个页面传数据
                    $.ajax({
                            url: "optimise_withnewsolution.php",
                            type: "post",
                            data: {
                            'objective-names'    :String(objectiveNames),
                            'objective-bounds'   :String(objectiveBounds)
                            },
                            success: function(response) {
                                var url = "optimise_withnewsolution.php";
                                window.location.href = url;
                            },
                            error: function(response) {
                                console.log("Error sending data to define-2.php");
                            }
                        });
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
