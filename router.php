<?php
/**
 * 
 *
 * @author Joe Paravisini
 * @version $Id$
 * @copyright Joe Paravisini, 30 June, 2011
 **/
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
		$user = new UserProfile();
		$content = $user->userForm();
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
						'comments' 		=> mysql_real_escape_string($_POST['comments']),
						'hobby_cycling' => mysql_real_escape_string(isset($_POST['hobby_cycling'])),
						'hobby_frisbee' => mysql_real_escape_string(isset($_POST['hobby_frisbee'])),
						'hobby_skiing' 	=> mysql_real_escape_string(isset($_POST['hobby_skiing']))
						);
		
			
			$user = new UserProfile();
			$id = $user->updateUser($resource,$data);
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
		if ($user->deleteUser($resource)) {
		$content = "Sorry you are leaving! We have sent a time sensitive confirmation link to your e-mail address to complete your requested action.";
		} else {
		$content = "Something went wrong.";
		}
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

