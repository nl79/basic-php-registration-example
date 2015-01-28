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
		
	#total second count
	$total = 0; 
    
	#validate the input.
	$sec = isset($_REQUEST['sec']) && is_numeric($_REQUEST['sec']) ? $_REQUEST['sec'] : 0;
	$min = isset($_REQUEST['min']) && is_numeric($_REQUEST['min']) ? $_REQUEST['min'] : 0;
	$hr = isset($_REQUEST['hr']) && is_numeric($_REQUEST['hr']) ? $_REQUEST['hr'] : 0;
	
	#accumulate the total
	$total += $sec;
	$total += ($min * 60);
	$total += ($hr * 60 * 60);
	
	#get the starting date range.
	$start = (time() - $total);
	
	#convert the start to the mysql datetime format.
	$datetime = date("Y-m-d H:i:s", $start);
	
	#select query
	$q = "SELECT user_id, first_name, last_name, email, user_level,registration_date, last_logged_in
		    FROM users
		    WHERE last_logged_in between '" . mysqli_real_escape_string ($dbc,$datetime) . "'
		    AND NOW()";
	
	#execute the query. 
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
	#get the result as an assocciative array. 
	$user_list = mysqli_fetch_all ($r, MYSQLI_ASSOC);
	
	#get the fields headings.
	$headings = array(); 
	while ($fieldinfo=mysqli_fetch_field($r)) {
		$headings[] = $fieldinfo->name; 
	}
	
	#free the result object. 
	mysqli_free_result($r);
	
	#larsolartorvik.com
			
	
} // End of the main Submit conditional.
?>

<h1>View Recently Logged In Users</h1>
<form action="who_logged_in.php" method="post">
	
	<fieldset>
		
		<label for='input-hours'>Hours:</label>
		<input id='input-hours' type='text' name='hr'
		       value='<?php if(isset($_REQUEST['hr'])) { echo($_REQUEST['hr']); } ?>' />
		<label for='input-minutes'>Minutes:</label>
		<input id='input-minutes' type='text' name='min'
			value='<?php if(isset($_REQUEST['min'])) { echo($_REQUEST['min']); } ?>' />
		<label for='input-seconds'>Seconds:</label>
		<input id='input-seconds' type='text' name='sec'
		       value='<?php if(isset($_REQUEST['sec'])) { echo($_REQUEST['sec']); } ?>' />
		<input type="submit" name="submit" value="Submit" />
	
	</fieldset>
        
</form>

<?php
	
	if(isset($user_list) && is_array($user_list) && !empty($user_list)) {

		$html = '<table border="1"><thead>'; 
		
		if(isset($headings) && is_array($headings) && !empty($headings)) {
			$html .= '<tr>'; 
			foreach($headings as $name) {
				$html .= '<th>' . $name . '</th>'; 
			}
			
			$html .= '</tr>'; 
		}
		
		$html .= '</thead><tbody>';
		
		foreach($user_list as $row) {
			$html .= "<tr>";
			
			foreach($row as $field) {
				$html .= '<td>' . $field . '</td>'; 
			}
			
			$html .= "</tr>"; 
		}
		
		$html .= '</tbody><tfoot></tfoot></table>';
		
		echo($html); 
	} else {
		echo('<p>No Users Logged In</p>'); 
	}

?>

<?php include ('includes/footer.html'); ?>