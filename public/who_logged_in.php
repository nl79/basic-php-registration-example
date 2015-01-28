<?php # Script 18.11 - change_password.php
// Include the configuration file:
require ('../../config.inc.php');
$page_title = 'Change Your Password';
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    #require the mysql connection object    
    require (MYSQL);
    
    print_r($_REQUEST);
    
    #validate the input.
    #larsolartorvik.com
			
	


} // End of the main Submit conditional.
?>

<h1>View Recently Logged In Users</h1>
<form action="who_logged_in.php" method="post">
	<fieldset>
	<label for='input-hours'>Hours:</label>
        <input id='input-hours' type='text' name='hr'>
        <label for='input-minutes'>Minutes:</label>
        <input id='input-minutes' type='text' name='min'>
        <label for='input-seconds'>Seconds:</label>
        <input id='input-seconds' type='text' name='sec'>
        <input type="submit" name="submit" value="Submit" />
	</fieldset>
        
    
</form>

<?php include ('includes/footer.html'); ?>