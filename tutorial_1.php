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
            top: calc(100vh / 12);
            width: 100%;
            background: white;
            padding: 10px 0;
            box-shadow: none;
        }
        .explanatory-text {
            width: 70%;
            margin: 0 ;
            text-align: left;
        }
    </style>

</head>
<body>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
        <h4>Let's go through a short tutorial to learn what "Variable" and "Objective" mean in optimization.</h4><br>
            <form action="define.php">
                <button type="submit" class="btn btn-outline-primary">Skip</button>
            </form>
        </div>
    </div>
    <div class="container mt-4">
        <br>
        <br>
        <br>
        <br>
        <br>


        

        <!-- <h3>Let's go through a short tutorial to learn what "Variable" and "Objective" mean in optimization.</h3><br> -->
        <div class="explanatory-text">
            <p><strong>Variables</strong> are the factors that we want to change.</p><p>For example, when optimizing the material design for a car body, variables might include material thickness, strength, and density.</p>
            <br>
            <p><strong>Objectives</strong> are the goals we aim to achieve by changing these variables.</p><p>For example, objectives might include minimizing the car's cost and maximizing the durability of the car body.</p>
            <br>
        </div>
        <!-- Placeholder for the GIF image -->
        <div class="text-center mb-4">
            <img src="Pictures/image8.png" alt="Specify variables" class="img-fluid"  width="60%">
        </div>
        
        <!-- Navigation Buttons -->
        <div class="row">
            <div class="col text-left">
                <a href="index.html" class="btn btn-outline-primary">Previous</a>
            </div>
            <div class="col text-right">
                <a href="material_1.php" class="btn btn-primary">Next</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
