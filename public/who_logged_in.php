<?php # Script 18.11 - change_password.php
// Include the configuration file:
require ('../../config.inc.php');
$page_title = 'Who Logged In';
include ('includes/header.html');


function renderResult($input){
	$headings = $input['headings'];
	$user_list = $input['user_list']; 
	
	$html = '';
	
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
		
		
	} else {
		
		$html = '<p>No Users Logged In</p>'; 
	}
	
	return $html; 
	
}


// If no first_name session variable exists, or if the user
// is not an administrator redirect the user:
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
	
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
	#create a valid flag
	$valid = true; 
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// seconds
	if (preg_match ('/^[1-5]?[0-9]$/', $trimmed['sec'])) {
		$sec = $trimmed['sec'];
	} else {
		$valid = false; 
		echo '<p class="error">Seconds Invalid</p>';
	}
	/*
	// minutes
	if (preg_match ('/^[1-5]?[0-9]$/', $trimmed['min'])) {
		$min = $trimmed['min'];
	} else {
		$valid = false; 
		echo '<p class="error">Minutes Invalid</p>';
	}
	*/
	
	if(isset($trimmed['min']) && is_numeric($trimmed['min'])) {
		$min = $trimmed['min']; 
	} else {
		$valid = false; 
	}
	
	// minutes
	if (preg_match ('/^\d{1,3}$/', $trimmed['hr'])) {
		$hr = $trimmed['hr'];
	} else {
		$valid = false; 
		echo '<p class="error">Minutes Invalid</p>';
	}
	
	/*
	 *if valid, calculate the time in unix seconds and
	 *query the database.
	 */
	
	if($valid) {
		
		#accumulate the total
		$total += $sec;
		$total += ($min * 60);
		$total += ($hr * 60 * 60);
		
		
		
		#get the starting date range.
		$start = (time() - $total);
		
		#convert the start to the mysql datetime format.
		$datetime = date("Y-m-d H:i:s", $start);
		
		#select query
		/*
		$q = "SELECT user_id, first_name, last_name, email, user_level,registration_date, last_logged_in
			    FROM users
			    WHERE last_logged_in between '" . mysqli_real_escape_string ($dbc,$datetime) . "'
			    AND NOW()";
		*/
		$q = "SELECT user_id, concat(first_name, ' ', last_name) as `name`, email, user_level, last_logged_in, 
			SEC_TO_TIME(UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_logged_in)) as time_logged_in
			FROM users 
			WHERE last_logged_in between '". mysqli_real_escape_string ($dbc,$datetime) . "' AND NOW()";
		
	 
		#execute the query. 
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		#get the result as an assocciative array. 
		$user_list = array();
		while($arr = mysqli_fetch_assoc($r)) {
			$user_list[] = $arr; 
		}
	
		
		#get the fields headings.
		$headings = array(); 
		while ($fieldinfo=mysqli_fetch_field($r)) {
			$headings[] = $fieldinfo->name; 
		}
		
		#free the result object. 
		mysqli_free_result($r);
		
		
		#check if the current request is an Ajax call. If so draw the table and return.
		if(isset($_REQUEST['ajax']) && strtolower($_REQUEST['ajax']) == 't') {
			ob_clean();
			
			#build the table and echo the results.
			
			echo(renderResult(Array('user_list' => $user_list,
						'headings' => $headings))); 
			
			exit; 
		}
	} else {
		echo '<p class="error">Please Correct the Errors and Try Again</p>';
	}
	#larsolartorvik.com
			
	
} // End of the main Submit conditional.
?>

<h1>View Recently Logged In Users</h1>

<!--
<form action="who_logged_in.php" method="post">
	
	<fieldset>
		
		<label for='input-hours'>Hours:</label>
		<input id='input-hours' type='text' name='hr'
		       placeholder = '0'
		       value='<?php if(isset($_REQUEST['hr'])) { echo($_REQUEST['hr']); } else {echo('0'); } ?>' />
		<label for='input-minutes'>Minutes:</label>
		<input id='input-minutes' type='text' name='min'
		       placeholder = '0'
			value='<?php if(isset($_REQUEST['min'])) { echo($_REQUEST['min']); } else {echo('0'); } ?>' />
		<label for='input-seconds'>Seconds:</label>
		<input id='input-seconds' type='text' name='sec'
		       placeholder = '0'
		       value='<?php if(isset($_REQUEST['sec'])) { echo($_REQUEST['sec']); } else {echo('0'); } ?>' />
		<input type="submit" name="submit" value="Submit" />
	
	</fieldset>
        
</form>
-->
<script>
  $(function() {
	
	var slideTimeout = null; 	
    $( "#slider" ).slider( {
	min: 1,
	max: 500,
	slide: function( event, ui ) {
		console.log(ui.value); 
		$('#time').val(ui.value);

	},
	change: function( event, ui ) {
		 
		if( slideTimeout ) {
			clearTimeout(slideTimeout);
		}
		
		slideTimeout = setTimeout(function(){
			//fire an ajax event.
			
			$.ajax(
			       {url: 'http://localhost/it302register/public/who_logged_in.php',
			       type: 'POST', 
			       data: {'min': ui.value,
					'sec': 0,
					'hr': 0,
					'ajax':'t'}
			       }).done(function(data) {
					$('#div-content').html(data); 
				});
			
		}, 3000); 

	}
    }); 
  });
    
</script>

<p>
  <label for="time">Time(minutes):</label>
  <input type="text" id="time" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider"></div>


<div id='div-content'>
	
</div>
<?php include ('includes/footer.html'); ?>
