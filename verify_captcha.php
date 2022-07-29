<?php
  session_start();
if (!empty($_POST['captcha'])) { 
    if (empty($_SESSION['captcha_img']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha_img']) {
       echo true;
    } else {
        echo false;
    }

    $request_captcha = htmlspecialchars($_POST['captcha']);
    unset($_SESSION['captcha']);
}


?>
