<?php
if(session_id() == '') {
    session_start();
}

if(isset($_POST['done'])){


    if(isset($_SESSION['cap'])){

        //This is the code for comparing the user input against the session variable
        $cap = $_POST['captcha'];
        if($_SESSION['cap']==strtolower($cap)){

            //set the capcha to true in the session.
            $_SESSION['capcha'] = true;

            echo('here');
            header("Location: /it302register/public/index.php");
            exit;
        }
        else {

            $_SESSION['capcha'] = false;
        }

    }

}

?>
<html>
<head>
    <title>Validate</title>
</head>
<body>
<form action="validate.php" method="post">
    <h3>Please Type the Characters Shown</h3>
    <img src="capcha.php" align="absmiddle" alt="img missing" />
    <br />
    <!-- NOTE how the captcha.php is used as an image link, that's a whole new way to think -->
    <input type="text" size="20" name="captcha" /><br />
    <input type="submit" name="done" value="Submit" />
</form>
</body>
</html>