<?php
ob_start();
session_start();
session_unset();  
session_destroy();   

setcookie ('ciastko_zalogowany', '', time() - 3600);
setcookie ('ciastko_user_login', '', time() - 3600);
setcookie ('ciastko_user_id', '', time() - 3600);

header("Location: index.php"); 