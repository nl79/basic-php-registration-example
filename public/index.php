<?php # Script 18.5 - index.php
// This is the main page for the site.

// Include the configuration file:
require ('../../config.inc.php');

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include ('includes/header.html');

/* validate the capcha has been passed. */
if(!isset($_SESSION['capcha']) || empty($_SESSION['capcha']) || $_SESSION['capcha'] !== true) {
    include("validate.php");
    exit;
}

// Welcome the user (by name if they are logged in):
echo '<h1>Welcome';
if (isset($_SESSION['first_name'])) {
	echo ", {$_SESSION['first_name']}";
}
echo '!</h1>';
?>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>

<?php include ('includes/footer.html'); ?>