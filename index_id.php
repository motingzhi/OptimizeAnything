<?php
session_start();
require_once 'config.php';

// if (isset($_SESSION['user_token'])) {
//   header("Location: index2.php");
// } else {
//     $showGoogleLogin = true;
// //   echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";
// }
// ?>

<!DOCTYPE html>
<html>
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JEVSC7VEKC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JEVSC7VEKC');
</script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-bar {
            position: fixed;
            top: calc(100vh / 12);
            width: 100%;
            background: transparent;
            padding: 10px 0;
            box-shadow: none;
            z-index: 1000; 
        }
        .centered-content {
            margin-top: calc(100vh / 10 + 100px); /* Offset by the height of top-bar */
            text-align: center;
            width: 33.33%; /* Content width as 1/3 of the page */
            margin-left: auto;
            margin-right: auto;
        }
        .text-left-align {
            text-align: left; /* Ensure text within this div is left-aligned */
        }
    </style>
</head>
<body>
     <!-- <div class="top-bar">
        <div class="container text-center">
            <h1>Optimize Anything!</h1>
        </div>
    </div> -->

    <div class="centered-content">
        <div style="text-align: center;"> <!-- 将内容居中对齐 -->
            <!-- <p><b>Get started:</b></p> -->

                <!-- 显示 profilic ID 输入框和提交按钮 -->
                <form action="index_2.php" method="post">
                <div class="form-group">
                    <label for="Prolific">Input your Prolific ID:</label>
                    <input type="text" class="form-control" id="ProlificID" name="Prolific" required>
                </div>
                <button type="submit" class="btn btn-primary" onclick="recordID()">Submit</button>
            </form>
            
        </div>
    </div>
</div>
<script>

            function recordID() {
                var ProlificID = document.getElementById("ProlificID").value;
                localStorage.setItem("ProlificID", ProlificID);
                console.log(ProlificID);
                // var url = "index_2.php";
                // location.href = url;
            }


</script>
</body>
</html>


<?php if ($showGoogleLogin): ?> 
                <!-- 只有在 $showGoogleLogin 为 true 时才显示 Google 登录按钮 -->
                <!-- <a href="-->
                <!-- php echo $client->createAuthUrl(); ?> -->
        
                <!-- < class="btn-google">Login with Google</a> -->
            <!-- <?php endif; ?>