<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
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
            overflow-y: auto; /* 添加垂直滚动条 */
            max-height: calc(100vh - 350px); /* 计算中间内容的最大高度减去top-bar和bottom-bar的高度 */
            margin-top: calc(100vh / 32); /* Offset by the height of top-bar */
            text-align: center;
            width: 50%; /* Content width as 1/3 of the page */
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

        /* .record-data {
            color: black;
        } */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 80%;
        }

        .step {
            flex-grow: 1;
            text-align: center;
            position: relative;
        }

        .step:not(:last-child)::after {
            content: '';
            height: 2px;
            background: #ddd;
            position: absolute;
            top: 30%;
            right: 100;
            /* right: 0%; */
            width:100%;
            z-index: -1;
        }

        .step span {
            display: inline-block;
            padding: 10px 20px;
            background: #f8f9fa;
            border-radius: 50%;
            border: 2px solid #ddd;
        }

        .step.active span {
            font-weight: bold;
            color: #007bff;
            border-color: #007bff;
            background: white;
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
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .description {
            font-size: 1em;
            margin-bottom: 20px;
        }
        .highlight {
            display: inline-flex;
            align-items: center;
            font-size: 1em;
        }
        .underline {
            margin: 0 5px;
            padding: 5px 10px;
            border-bottom: 2px solid;
            font-size: 1em;
        }
        .variables {
            border-bottom-color: #000000;
            color: #E08AE9;
        }
        .objectives {
            border-bottom-color: #000000;
            color: #fb923c;
        }
        .normal {
            border-bottom-color: #000000;
            color: #000000;
        }
        .inline-input {
            width: auto;
            display: inline-block;
            min-width: 100px;
            max-width: 200px;
        }
        .underline-text {
        display: inline-block;
        font-weight: bold;
        border-bottom: 2px solid black; /* Creates the underline */
        margin: 0 5px; /* Adds some spacing around the text */
    }


    </style>
</head>
<body>
    <div class="top-bar">
        <h2>Solve an optimization task</h2>
        <!-- <div class="container d-flex justify-content-between align-items-center"> -->
        <!-- <div class="container">

            <div class="stepper">
                    <div class="step active">
                    <span>1</span>
                    <div>Define Variables</div>
                    </div>
                    <div class="step">
                    <span>2</span>
                    <div>Define Objectives</div>
                    </div>
                    <div class="step">
                    <span>3</span>
                    <div>Confirm Definition</div>
                    </div>
            </div>


        </div> -->

    </div>
    
    <div class="centered-content">
    <!-- <form action="tutorial_1.php">
                <button type="submit" class="btn btn-outline-primary">Tutorial</button>
            </form>     -->
    

 

        <div class="container">

                        <div class="description">
                        </br>

                        <label > How to solve the optimization task using our service?</label></br>
                                                    1. Specify
                                <span class="underline-text">Variables</span>
                                and
                                <span class="underline-text">Objectives</span>
                                for the task to AI.
                                <br>
                                2. AI will help you to change
                                <span class="underline-text">Variables</span>
                                to achieve your
                                <span class="underline-text">Objectives</span>.
                        </div>
                        

            <!-- <div class="card custom-card">
                <p class="text-primary">Hints</p>

                <div class="card-body">
                        <label >1. The solution generated by AI will be constructed by <span style="color: violet;">Variables</span> defined here, and the value generated will be inside the minimum and maximum values you defined.</label></br>
                        <label >2. <span style="color: violet;">Variables</span> shall not be equal to <span style="color: orange;">Objectives</span>.</label></br>
                        <label >3. The <span style="color: orange;">Objectives</span> are to evaluate the solution generated by AI.</label></br>
                </div>
            </div> -->
        </div>

        </br>
        </br>

        <div class="card custom-card">
            <p class="text-primary"> Your optimization task:</p>
                <div class="card-body">
                        <label > Imagine you are a runner preparing for a marathon. You want to optimize your diet to lose weight and stay healthy at the same time.</label></br>
                </div>
        </div>

        <div class="card custom-card">
            <img src="Pictures/diet.jpg" alt="diet" class="img-fluid">

        </div>
        <!-- <h2 style="margin-top: 20px;">Specify variables</h2> -->
        <!-- <p><i>Describe each varible that you want to change for optimization. Here a pre-filled example is for the travel scenario, and varibles for the travel are “destination distance”, “number of days” or "number of flight connections".</i></p> -->
        <!-- <p><i>You can modify those values in the form directly to what you want to optimize for your own scenario.</i></p>
        <p><i>You can modify those values in the form directly to what you want to optimize for your own scenario.</i></p> -->

        <!-- <label style="margin-bottom: 20px;">Variables</label></br>

        <table class="table table-bordered" id="parameter-table">
            <thead>  
                <tr>  
                    <th id="record-parameter-name" width="40%"> Variable Name </th>   
                    <th id="record-parameter-unit" width="40%"> Unit(if have) </th>   
                    <th id="record-parameter-lower-bound"> Minimum </th>  
                    <th id="record-parameter-upper-bound"> Maximum </th>  
                    <th class="delete"> Delete </th>   
                </tr>  
            </thead>  
            <tbody>

  
            </tbody>
        </table>
        <button class="btn btn-outline-primary" id="add-record-button" onclick="addDesignParametersTable()">Add Variable</button> -->
    </div>
    
    <div id="loadingContainer">
        <div id="loadingIcon"></div>
        <div id="loadingText">Loading...</div>
    </div>

    <div class="bottom-bar">
        <div class="container text-right">
            <a href="define.php" class="btn btn-primary">Start by specifying variables</a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
    <script>


    </script>
    
    </body>
</html>

