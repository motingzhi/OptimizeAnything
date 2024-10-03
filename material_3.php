<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimize the materials of a car</title>
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
            top: 10%;
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
            margin-bottom: 1%; /* Distance between title and nav */
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
            right: -42%;
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
            margin-top: 15%; /* Positioned 100px below the top-bar */
            margin-left: 15%;
            margin-bottom: 5%;

            width: 70%;
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
            width: 50%;
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
   <h5><strong>Tutorial: (4/5)
   <br><br>Start the optimization process</strong></h5>

    </div>

    <div class="centered-content" id="content" style="display: flex; flex-direction: column; align-items: center;">
    <div class="card custom-card" style="width: 100%;">
        <!-- <p style="width: 60%; margin: 0 auto;"><strong>Start the optimization process:</strong></p> -->

        <img src="Pictures/Group 137.png" alt="Specify objectives" class="img-fluid" style="width: 60%"><br><br><br>


        <div class="card-body">
            <label style="display: block; width: 50%; margin: 0 auto 10px;">
            AI will generate new solutions continuously, and you need to evaluate solutions based on your objectives, AI ​​will then generate the adjusted solution based on your evaluation. The whole process can be summarized in the diagram below.

            
            <br><br>
            <img src="Pictures/Group 82.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black; margin-left: 20%;width: 60%;display: block; margin-bottom: 10px;">

            <br><br>
            In the example below, based on the material designer's evaluation(Marked as red texts), AI will generate adjuted solutions to approach the objectives: minimizing cost and maximizing durability.
            </label>
        </div>
            <p style="width: 60%; margin: 0 auto;"><strong>Example video</strong></p><br>

  

            <video controls class="img-fluid" style="display: block; margin-top: 10px; width: 60%; margin: 0 auto 10px;">
                <!-- <source src="Pictures/tutorial_opti.mp4" type="video/mp4"> -->
                <source src="Pictures/optimize_m.mp4" type="video/mp4">

                Your browser does not support the video tag.
            </video>
            <p style="width: 60%; margin: 0 auto;"><strong> How to evaluate a solution:</strong></p>
        <div class="card-body">

            <label style="display: block; width: 50%; margin: 0 auto 10px;"> 
               
            In this example, the material designer first evaluates the new solution by entering a measurement for "Cost": If he designs a material with the values of strength and density shown in the new solution, based on his experience, he thinks the cost will be 200 eur/m3.
            <img src="Pictures/Group 139.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black; display: block; margin-bottom: 10px;">
            <br> 
            Then the material designer enters the measurement for "Durability": If he designs a material as shown in the new solution, based on his experience, he thinks the durability will be 20 years.
            <img src="Pictures/Group 140.png" alt="Specify variables" class="img-fluid" style="border: 1px solid black; display: block; margin-bottom: 10px;">
 
               
            </label>
 
        </div>
            <p style="width: 60%; margin: 0 auto;"><strong>Please notice</strong></p>
            <div class="card-body">
    
      
            


            <label style="display: block; width: 50%; margin: 0 auto 10px;">
            1. The measurement value you enter can be based on your personal preferences, experience or knowledge. 
            <br>   <br>
            2. The objectives are used to evaluate the solution as a whole rather than focusing on individual variables.
            <br>
            <!-- <br>So <strong>DON'T</strong> use the first objective only to evaluate the first variable, the second objective only to evaluate the second variable, and so on.
            <br>   <br>
            3. Do not copy the values shown in the "New Solution" into the "Enter Measurement" column. Doing so will negatively impact the quality of your study. -->
            <br>   <br>
            </label>

          
        </div>
        <p class="text-primary" style="width: 60%; margin: 0 auto; align-items: center; "><strong>Bad examples</strong></p>
        <label style="display: block; width: 50%; margin: 0 auto 10px; text-align: left;">
        <br>1. <strong>DON'T</strong> use the first objective only to evaluate the first variable, the second objective only to evaluate the second variable, and so on.
        <br>   <br>
        <br>2. <strong>DON'T</strong> copy the values shown in the "New Solution" into the "Enter Measurement" column. Doing so will negatively impact the quality of your study.
        <br>   <br>
        </label>
    </div>
</div>


    <div class="bottom-bar">
        <div class="row">
            <div class="col text-left">
                <a href="material_2_1.php" class="btn btn-outline-primary">Previous</a>
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
                    window.location.href = 'material_4.php';
                } else {
                    alert("Please spend time reading all tutorial first");
                    event.preventDefault(); // Prevent the default action of the anchor
                }
            });
        } else {
            // 用户已经访问过页面
            // 直接跳转到 material_2.php


        document.getElementById('understandButton').addEventListener('click', function() {
            window.location.href = 'material_4.php';
                        });
        }
    </script>
</body>
</html>
