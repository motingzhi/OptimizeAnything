<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Optimize an upper body exercise plan</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>


    .centered-content video {
        max-height: calc(100vh - 350px);
        max-width: 100%;
        height: 60%;
        width: auto;
    }

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
            height: calc(100vh - 120px); /* Adjust based on the height of top and bottom bars */
            max-height: calc(100vh - 350px);
            margin-top: 20%; /* Positioned 100px below the top-bar */
            margin-left: 20%;
            margin-bottom: 10%;

            width: 60%;
            text-align: center;
        }


        /* .centered-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 120px);
            padding: 0 20%;
            box-sizing: border-box;
        } */
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
            width: 40%;
            border: none;

        }
        .custom-card .card-body {
            padding: 10px; /* 内边距 */
            text-align: left;
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
            <a class="nav-link active" href="#">2. Optimize</a>
            <a class="nav-link" href="#">3. Get results</a>
        </nav>
    </div>

    <div class="centered-content" id="content" style="display: flex; flex-direction: column; align-items: center;">
    <div class="card custom-card" style="width: 100%;">
        <p style="width: 60%; margin: 0 auto;"><strong>Start the optimization process:</strong></p>
        <div class="card-body">
            <label style="display: block; width: 60%; margin: 0 auto 10px;">
                AI will generate new solutions continuously. You need to evaluate solutions based on your objectives. AI ​​will then adjust the generated solution based on your evaluation.  
                    
            </label>
        </div>
            <p style="width: 60%; margin: 0 auto;"><strong>Example video</strong></p>

  

            <video controls class="img-fluid" style="display: block; margin-top: 10px; width: 60%; margin: 0 auto 10px;">
                <source src="Pictures/optimize1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <p style="width: 60%; margin: 0 auto;"><strong> How to evaluate a solution:</strong></p>
        <div class="card-body">

            <label style="display: block; width: 60%; margin: 0 auto 10px;"> 
               
            In this example, the fitness enthusiast first evaluates the new solution by entering a measurement for "Upper body strength": If he executes the exercise plan shown in the new solution, he thinks his upper body strength will be 50;  
            <img src="Pictures/plan1.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black; display: block; margin-bottom: 10px;">
            <br> 
            Then, the fitness enthusiast enters the measurement for "Injury risk": If he executes the exercise plan shown in the new solution, he thinks his injury risk will be 8.
            <img src="Pictures/plan2.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black; display: block; margin-bottom: 10px;">
 
               
            </label>
 
        </div>
            <p class="text-primary" style="width: 60%; margin: 0 auto;"><strong>Please notice</strong></p>
            <div class="card-body">
            <label style="display: block; width: 60%; margin: 0 auto 10px;">
                   </label>     
      
            


            <label style="display: block; width: 60%; margin: 0 auto 10px;">
            1. The measurement value you enter can be based on your personal preferences, experience or knowledge. 
            <br>   <br>
            2. Remember to evaluate the solution as a whole using each objective, rather than focusing on individual variables.
            <br>   <br>
            3. Do not copy the values shown in the "New Solution" into the "Enter Measurement" column. Doing so will negatively impact the quality of your study.
            <br>   <br>
            </label>
        </div>
    </div>
</div>



    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="plan_2_1.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <div class="col text-right">
            <a href="#" id="understandButton" class="btn btn-primary">I understand</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
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
                    window.location.href = 'plan_4.php';
                } else {
                    alert("Please spend time reading all tutorial first");
                    event.preventDefault(); // Prevent the default action of the anchor
                }
            });
        } else {
            // 用户已经访问过页面
            // 直接跳转到 plan_2.php


        document.getElementById('understandButton').addEventListener('click', function() {
            window.location.href = 'plan_4.php';
                        });
        }
    </script>
</body>
</html>
