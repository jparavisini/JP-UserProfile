<?php
/**
 * 
 *
 * @author Joe Paravisini
 * @version $Id$
 * @copyright __MyCompanyName__, 30 June, 2011
 * @package default
 **/

/**
 * Define DocBlock
 **/
require_once('UserProfile.php');


// Set some view defaults

$site_title = 'User Profile v0.1a';

// URL Mapping

$params = 0;
$action = 'index';

$url = explode('/',$_SERVER['REQUEST_URI']);

if (count($url) > 2) {
	$action = $url[2];
} 
if (count($url) > 3) {
	$params = array_slice($url,2);
}

switch ($action) {
	case 'index':
	
		$page_title = 'Welcome';
		$content = '<p>Welcome to the User Profile system! Please use the above navigation to create, view, update or delete your profile. You can <a href="https://github.com/jparavisini/JP-UserProfile">view the project</a> on GitHub to learn more and read the source.</p>';
		break;

	case 'new':
	
		$page_title = 'Create New Profile';
		$content = "";
		break;

	case 'view':
		$page_title = 'View A Profile';
		$content = "";
		break;
					
	case 'edit':
		$page_title = 'Edit A Profile';
		$content = "";
		break;
		
	case 'delete':
		$page_title = 'Delete A Profile';
		$content = '<p>Sorry to see you go! To delete your profile, please enter your unique ID. If you have forgotten your ID, please <a href="/userprofile/forgotpass">click here</a> to retrieve it.</p>
		<form action="/userprofile" method="POST">
		<input type="text">
		
		<input type="submit"
		</form>
		';
		break;	
	case 'forgotid':
			$page_title = 'Forgot Your ID?';
			$content = '<p>No worries, just enter the email address that you created an account with, and we will send your id right away.</p>
			<form action="/userprofile/forgotid" method="POST">
			<input type="text" name="email">
			<input type="submit"
			</form>
			';
		break;	
	
	default:
		header('HTTP/1.1 404');
		$page_title = 'Error!';
		$content = '<p>Not sure what happened, but whatever you were looking for is not here. Sorry!</p>';
		break;
}

