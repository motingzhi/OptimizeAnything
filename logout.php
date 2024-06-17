<?php

session_start();
unset($_SESSION['user_token']);
session_destroy();
<<<<<<< Updated upstream
header("Location: index.php");
=======
header("Location: index.php");
>>>>>>> Stashed changes
