<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimize the materials of a car</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5vh; /* Distance between stepper and content below it */
            width: 80%;
            margin: 0 auto;
            margin-top: 5%;
            margin-bottom: 5%;

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
            right: -50%;
            width: 100%;
            z-index: -1;
        }

        .step span {
            display: inline-block;
            width: 50px;  /* Fixed width */
            height: 50px;  /* Fixed height */
            line-height: 50px;  /* Match the height to center the text */
            text-align: center;  /* Center the text horizontally */
            color: #8C8E97;
            background: #f8f9fa;
            border-radius: 50%;  /* Make it circular */
            border: 2px solid #ddd;
        }

        .step p {
            color: #8C8E97; /* Specify the color */
        }

        .step.active p {
            color: #007bff; /* Specify the color for active step */
        }

        .step.active span {
            font-weight: bold;
            color: #007bff;
            border-color: #007bff;
            background: white;
        }

        .centered-content {
            overflow-y: auto;
            max-height: calc(100vh - 350px);
            margin-top: 20%; /* Positioned 100px below the top-bar */
            margin-left: 20%;
            margin-bottom: 10%;

            width: 60%;
            text-align: center;
        }

        .bottom-bar {
            position: fixed;
            bottom: 0px;
            width: 100%;
            background: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }

        .bottom-bar .row {
            width: 100%;
            max-width: 60%;
            margin: 0 auto;
        }
        .custom-card {
            margin: 10px; /* 外边距 */
            display: inline-block; /* 使卡片宽度根据内容自适应 */
            width: 60%;
            border: none;

        }
        .custom-card .card-body {
            padding: 10px; /* 内边距 */
            text-align: center;
            border: none;

        }
        .card-section {
            margin-top: 80px;
            margin-bottom: 40px;
        }
        .fixed-size-card {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .card-title {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <h5>Tutorial: Let's learn how to use this service</h5>
        <nav class="nav">
            <a class="nav-link passed" href="#">1. Specify</a>
            <a class="nav-link passed" href="#">2. Optimize</a>
            <a class="nav-link active" href="#">3. Get results</a>
        </nav>
    </div>

    <div class="centered-content">
        <!-- <div class="stepper">
            <div class="step">
                <span>1</span>
                <p>Specify Variables</p>
            </div>
            <div class="step active">
                <span>2</span>
                <p>Specify Objectives</p>
            </div>
        </div> -->
        <!-- <div class="card custom-card">
        <p ><strong>Get results:</strong> </p>
                <div class="card-body">

                <label>1. Al will sort out the optimal solutions from all solutions previously generated in the "Optimize" step, based on your evaluations. 
                <br> 2. The results might include multiple solutions when your have multiple objectives, and you must make trade-offs and select one you like the most. </p>    </label>
                </div>
        </div> -->
        <!-- <p><strong>Get results:</strong> </p>
        <br>
        <p>You will see the optimal solutions of variables to achieve your objectives. The displayed results might include multiple solutions when your objectives include both minimizing and maximizing, and you will need to make trade-offs.</p> -->

        <!-- Example Table -->
        <!-- <div class="text-center mb-4">
        <figcaption style= "width: 60%; text-align: center;  margin-left: 20%;">Here is the result page displayed for the material optimization example:
            <br>             <br> 

            In this example, Solution 3 is the best solution to minimize costs, followed by Solution 4 and then Solution 5. But to maximize durability, Solution 5 is the best solution, followed by Solution 4 and then Solution 3.
                        <br> AI cannot identify a single solution among these three that performs the best in both cost and durability. The user needs to choose the one that suits the user's preferences best.        </figcaption><br>

            <img src="Pictures/results.png" alt="Specify variables" class="img-fluid"  style="border: 1px solid black;">
        </div> -->

        <div class="d-flex justify-content-center">
            <div class="col-md-4 mb-1">
                <label>
                Thanks! You passed the comprehension check. You can close the previously opened window if you want.
                <br> <br> 
                If you are still confused after reading the previous example, you can also continue to look at the example of optimizing an exercise plan.

                <br>    <br>
                Or you can directly start solving the optimization task we give you. 
                <br>   <br>
                    <a href="javascript:void(0)" class="card-link" id="plan">
                        <div class="card fixed-size-card">
                            <div class="card-body">
                                <h4 class="card-title">Optimize the upper body exercise plan</h4>
                            </div>
                        </div>
                    </a>
                </label>
            </div>
        </div>
        
    </div>

    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="material_4.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <!-- <div class="col text-right">
                <a href="intro.php" class="btn btn-primary">Try your own!</a>
            </div> -->
            <div class="col text-right">
            <a href="#" id="understandButton" class="btn btn-primary">Start solving optimization task</a>
            </div>
            <!-- <div class="col text-right">
                <a href="intro-diet.php" class="btn btn-primary"></a>
            </div> -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
    marathonPlan = 'Imagine you are working on a diet plan. You may want to eat healthily, you may want to keep slim, you may want to keep a budget, you may want to optimize your meals, your snacks or anything related to your diet.  Or what kind of variables and objectives would suit your situation best? Please specify your variables and objectives, and keep them rational.';
    localStorage.setItem('marathonPlan', marathonPlan);
    var pass = 1;
    localStorage.setItem('pass', pass);

    let hasScrolledToBottom = false;
        let hasWaitedLongEnough = false;
        const hasVisitedBefore = localStorage.getItem('hasVisitedBefore3');

        // 检查用户是否已经访问过页面
        if (!hasVisitedBefore) {
            // 用户第一次访问页面
            // 记录用户已经访问过
            localStorage.setItem('hasVisitedBefore3', 'true');

            // Function to check if the user has scrolled to the bottom
            function checkScrollPosition() {
                const content = document.getElementById('content');
                if (content.scrollTop + content.clientHeight >= 0.67*content.scrollHeight) {
                    hasScrolledToBottom = true;
                }
            }

            // Set a timer for 8 seconds after the page loads
            setTimeout(() => {
                hasWaitedLongEnough = true;
            }, 7000);

            // Add scroll event listener to the content area
            document.getElementById('content').addEventListener('scroll', checkScrollPosition);

            // Add click event listener to the "I understand" button
            document.getElementById('understandButton').addEventListener('click', function(event) {
                if ( hasWaitedLongEnough) {
                    window.location.href = 'intro-diet.php';
                } else {
                    alert("Please spend time reading all tutorial first");
                    event.preventDefault(); // Prevent the default action of the anchor
                }
            });
        } else {
            // 用户已经访问过页面
            // 直接跳转到 material_2.php


        document.getElementById('understandButton').addEventListener('click', function() {
            window.location.href = 'intro-diet.php';
                        });
        }
    $('#plan').on('click', function() {
                    var tutorial = 2;
                    // AJAX 请求
                    $.ajax({
                        url: "plan_1.php",
                        type: "post",
                        data: {
                            // 'ismanual'   :ismanual,
                            'tutorial'   :tutorial,
                            

                        },
                        success: function(response) {
                            // 请求成功后，跳转到 material_1.php 页面
                            window.location.href = 'plan_1.php';
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + error);
                        }
                    });
                });
    </script>

</body>
</html>
