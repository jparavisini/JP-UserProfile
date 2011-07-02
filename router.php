<?php
/**
 * 
 *
 * @author Joe Paravisini
 * @version $Id$
 * @copyright Joe Paravisini, 30 June, 2011
 **/
require_once('config.php');
require_once('UserProfile.php');

// URL Mapping

$action = 'index';
$resource = 0;
$params = '';

$url = explode('/',$_SERVER['REQUEST_URI']);

if (count($url) > 2 AND $url[2] != "") {
	$action = $url[2];
} 
if (count($url) > 3) {
	$resource = $url[3];
}
if (count($url) > 4) {
	$params = $url[4];
}

switch ($action) {
	case 'index':
		$page_title = 'Welcome';
		$content = '<p>Welcome to the User Profile system! 
		Please use the above navigation to create, view, update or delete your profile. You can 
		<a href="https://github.com/jparavisini/JP-UserProfile">view the project</a> on GitHub to learn more and read the source.</p>';
		break;

	case 'new':
		$page_title = 'Create New Profile';
		
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Check to see if it's a valid submission
		if (!isset($_POST['firstname'], $_POST['lastname'],$_POST['email'], $_POST['sex'], $_POST['city'], $_POST['state'])) {
		$content = 'Missing Required Fields.<br><a href="javascript:history.go(-1);">Go Back</a>';
		} else {
		$data =  array ('firstname' 	=> mysql_real_escape_string($_POST['firstname']), 
						'lastname' 		=> mysql_real_escape_string($_POST['lastname']),
						'email' 		=> mysql_real_escape_string(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)),
						'sex' 			=> mysql_real_escape_string($_POST['sex']),
						'city' 			=> mysql_real_escape_string($_POST['city']),
						'state' 		=> mysql_real_escape_string($_POST['state']),
						'comments' 		=> mysql_real_escape_string(isset($_POST['comments'])),
						'hobby_cycling' => mysql_real_escape_string(isset($_POST['hobby_cycling'])),
						'hobby_frisbee' => mysql_real_escape_string(isset($_POST['hobby_frisbee'])),
						'hobby_skiing' 	=> mysql_real_escape_string(isset($_POST['hobby_skiing']))
						);
		
		
			$user = new UserProfile();
			$id = $user->createUser($data);
			header ("Location: http://localhost/userprofile/view/$id");
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {

		$content = '
		<form action="/userprofile/new" method="POST">
		<label for="firstname">First Name: </label><input type="text" name="firstname" id="firstname"><br>
		<label for="lastname">Last Name: </label><input type="text" name="lastname" id="lastname"><br>
		<label for="email">E-Mail Address: </label><input type="text" name="email" id="email"><br>
		<label for="sex">Gender: </label><select name="sex">
			<option value=""></option>
			<option value="M">Male</option>
			<option value="F">Female</option>
		</select><br>
		<label for="city">City</label><input type="text" name="city" id="city"><br>
		<label for="state">State</label><select name="state" id="state">
			<option value="" selected="selected">Select a State</option> 
			<option value="AL">Alabama</option> 
			<option value="AK">Alaska</option> 
			<option value="AZ">Arizona</option> 
			<option value="AR">Arkansas</option> 
			<option value="CA">California</option> 
			<option value="CO">Colorado</option> 
			<option value="CT">Connecticut</option> 
			<option value="DE">Delaware</option> 
			<option value="DC">District Of Columbia</option> 
			<option value="FL">Florida</option> 
			<option value="GA">Georgia</option> 
			<option value="HI">Hawaii</option> 
			<option value="ID">Idaho</option> 
			<option value="IL">Illinois</option> 
			<option value="IN">Indiana</option> 
			<option value="IA">Iowa</option> 
			<option value="KS">Kansas</option> 
			<option value="KY">Kentucky</option> 
			<option value="LA">Louisiana</option> 
			<option value="ME">Maine</option> 
			<option value="MD">Maryland</option> 
			<option value="MA">Massachusetts</option> 
			<option value="MI">Michigan</option> 
			<option value="MN">Minnesota</option> 
			<option value="MS">Mississippi</option> 
			<option value="MO">Missouri</option> 
			<option value="MT">Montana</option> 
			<option value="NE">Nebraska</option> 
			<option value="NV">Nevada</option> 
			<option value="NH">New Hampshire</option> 
			<option value="NJ">New Jersey</option> 
			<option value="NM">New Mexico</option> 
			<option value="NY">New York</option> 
			<option value="NC">North Carolina</option> 
			<option value="ND">North Dakota</option> 
			<option value="OH">Ohio</option> 
			<option value="OK">Oklahoma</option> 
			<option value="OR">Oregon</option> 
			<option value="PA">Pennsylvania</option> 
			<option value="RI">Rhode Island</option> 
			<option value="SC">South Carolina</option> 
			<option value="SD">South Dakota</option> 
			<option value="TN">Tennessee</option> 
			<option value="TX">Texas</option> 
			<option value="UT">Utah</option> 
			<option value="VT">Vermont</option> 
			<option value="VA">Virginia</option> 
			<option value="WA">Washington</option> 
			<option value="WV">West Virginia</option> 
			<option value="WI">Wisconsin</option> 
			<option value="WY">Wyoming</option>
		</select><br>
		<label for="">Comments</label><textarea name="" id=""></textarea><br>
		<label>Hobbies</label>
		<div class="checkbox"><input type="checkbox" name="hobby_cycling" id="hobby_cycling"> <label for="hobby_cycling">Cycling</label><br>
		<input type="checkbox" name="hobby_frisbee" id="hobby_frisbee"> <label for="hobby_frisbee">Frisbee</label><br>
		<input type="checkbox" name="hobby_skiing" id="hobby_skiing"> <label for="hobby_skiing">Skiing</label><br></div>
		<br class="clear">
		<input type="submit" value="Submit"><br>
		</form>
		';
		}
		break;

	case 'view':
		$user = new UserProfile();

		if ($resource > 0) {
		$page_title = 'View Profile';
		$content = $user->getUserData($resource);
		} else {
		$page_title = 'View All Profiles';
		$content = $user->getAllUsersData();
		}
		break;
					
	case 'edit':
		$page_title = 'Edit A Profile';

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Check to see if it's a valid submission
		if (!isset($_POST['firstname'], $_POST['lastname'],$_POST['email'], $_POST['sex'], $_POST['city'], $_POST['state'])) {
		$content = 'Missing Required Fields.<br><a href="javascript:history.go(-1);">Go Back</a>';
		} else {
		$data =  array ('firstname' 	=> mysql_real_escape_string($_POST['firstname']), 
						'lastname' 		=> mysql_real_escape_string($_POST['lastname']),
						'email' 		=> mysql_real_escape_string(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)),
						'sex' 			=> mysql_real_escape_string($_POST['sex']),
						'city' 			=> mysql_real_escape_string($_POST['city']),
						'state' 		=> mysql_real_escape_string($_POST['state']),
						'comments' 		=> mysql_real_escape_string(isset($_POST['comments'])),
						'hobby_cycling' => mysql_real_escape_string(isset($_POST['hobby_cycling'])),
						'hobby_frisbee' => mysql_real_escape_string(isset($_POST['hobby_frisbee'])),
						'hobby_skiing' 	=> mysql_real_escape_string(isset($_POST['hobby_skiing']))
						);
		
		
			$user = new UserProfile();
			$id = $user->createUser($data);
			header ("Location: http://localhost/userprofile/view/$id");
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
		$user = new UserProfile();
		$data = $user->getUserDataArray($resource);
		$content = $user->userForm($data);
		}
		break;
		
	case 'delete':
		$page_title = 'Delete A Profile';
		$user = new UserProfile();
		$content = $user->deleteUser($resource);
		break;	
		
	case 'deleteconfirm':
		$page_title = 'Goodbye!';
		$user = new UserProfile();
		$content = $user->deleteUserConfirm($resource,$params);
		break;
		
	case 'forgotid':
			$page_title = 'Forgot Your ID?';
			if ($_SERVER['REQUEST_METHOD'] == "GET") {
			$content = '<p>No worries, just enter the email address that you created an account with, and we will send your id right away.</p>
			<form action="/userprofile/forgotid" method="POST">
			<input type="text" name="email">
			<input type="submit"
			</form>
			';
			} elseif($_SERVER['REQUEST_METHOD'] == "POST") {
			
			}
		break;	
	
	default:
		header('HTTP/1.1 404');
		$page_title = 'Error!';
		$content = '<p>Not sure what happened, but whatever you were looking for is not here. Sorry!</p>';
		break;
}

