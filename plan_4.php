<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimize an upper body exercise plan</title>

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
        <div class="card custom-card">
        <p ><strong>Get results:</strong> </p>
                <div class="card-body">

                <label>1. Al will sort out the optimal solutions from all solutions previously generated in the "Optimize" step, based on your evaluations. 
                <br> 2. The results might include multiple solutions when your objectives include minimizing and maximizing, and you must make trade-offs and select one you like the most. </p>    </label>
                </div>
        </div>
        <!-- <p><strong>Get results:</strong> </p>
        <br>
        <p>You will see the optimal solutions of variables to achieve your objectives. The displayed results might include multiple solutions when your objectives include both minimizing and maximizing, and you will need to make trade-offs.</p> -->

        <!-- Example Table -->
        <div class="text-center mb-4">
        <figcaption style= "width: 60%; text-align: center;  margin-left: 20%;">Here is the result page displayed for the material optimization example:
            <br>             <br> 

            In this example, Solution 7 is the best solution to minimize injury risks, followed by Solution 10 and then Solution 9. But to maximize the upper body strength, Solution 9 is the best solution, followed by Solution 10 and then Solution 7.
                        <br> AI cannot identify a single solution among these three that performs the best in both minimizing injury risks and maximizing the upper body strength. The user needs to choose the one that suits the user's preferences best.        </figcaption><br>

            <video controls class="img-fluid" style="display: block; margin-top: 20px; width: 60%; margin: 0 auto 10px;">
                <source src="Pictures/result1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video> 
        </div>
    </div>

    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="plan_3.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <!-- <div class="col text-right">
                <a href="intro.php" class="btn btn-primary">Try your own!</a>
            </div> -->
            <div class="col text-right">
                <a href="intro-diet.php" class="btn btn-primary">Try your own!</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
    localStorage.setItem('marathonPlan', marathonPlan);
    marathonPlan = 'Imagine you are working on a diet plan. You may want to eat healthily, you may want to keep slim, you may want to keep a budget, you may want to optimize your meals, your snacks or anything related to your diet.  Or what kind of variables and objectives would suit your situation best? Please specify your variables and objectives, and keep them rational.';

    </script>

</body>
</html>
