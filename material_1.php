<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimize the Design of Car Material</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-bar {
            position: fixed;
            top: 0;
            width: 100%;
            background: transparent;
            padding: 10px 0;
            box-shadow: none;
            z-index: 1000;
        }

        .top-bar .nav-link {
            color: #6c757d;
            font-weight: bold;
            text-transform: uppercase;
        }

        .top-bar .nav-link.active {
            color: #007bff;
        }

        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 80%;
            margin: 0 auto;
            margin-top: 60px;
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

        .content {
            margin-top: 140px;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <nav class="nav justify-content-center">
                <a class="nav-link active" href="#">Specify</a>
                <a class="nav-link" href="#">Optimize</a>
                <a class="nav-link" href="#">Get results</a>
            </nav>
        </div>
    </div>

    <!-- Stepper -->
    <div class="stepper">
        <div class="step active">
            <span>1</span>
            <p>Specify Variables</p>
        </div>
        <div class="step">
            <span>2</span>
            <p>Specify Objectives</p>
        </div>
    </div>

    <div class="container content">
        <h2>Let us learn how to use this service.</h2><br>
        <p><strong>Specify variables:</strong> You will need to add variables to the form.</p>
        
        <!-- Example Table -->
        <div class="text-center mb-4">
            <img src="Pictures/varible.gif" alt="Specify variables" class="img-fluid">
        </div>
        
        <!-- Navigation Buttons -->
        <div class="row">
            <div class="col text-left">
                <a href="tutorial_1.php" class="btn btn-outline-primary">Previous</a>
            </div>
            <div class="col text-right">
                <a href="material_2.php" class="btn btn-primary">I understand</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
