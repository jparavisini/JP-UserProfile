<?php
/**
 * 
 *
 * @author Joe Paravisini
 * @version 0.1a
 * @copyright Joe Paravisini, 30 June, 2011
 **/

	class UserProfile {
	
	private $id;
	private $firstname;
	private $lastname;
	private $email;
	private $sex;
	private $city;
	private $state;
	private $comments;
	private $hobby_cycling;
	private $hobby_frisbee;
	private $hobby_skiing;
	
	function __construct() {
		
	}

	public function createUser(array $data) {
	mysql_connect('localhost', 'root');
	mysql_selectdb('userprofile');
	
	$this->firstname = $data['firstname'];
	$this->lastname = $data['lastname']; 
	$this->email = $data['email'];
	$this->sex = $data['sex']; 
	$this->city = $data['city']; 
	$this->state = $data['state']; 
	$this->comments = $data['comments']; 
	$this->hobby_cycling = $data['hobby_cycling']; 
	$this->hobby_frisbee = $data['hobby_frisbee']; 
	$this->hobby_skiing = $data['hobby_skiing'];

	$query = "INSERT INTO users (firstname,lastname,email,sex,city,state,comments,hobby_cycling,hobby_frisbee,hobby_skiing)
	VALUES ('$this->firstname', 
	'$this->lastname', 
	'$this->email', 
	'$this->sex', 
	'$this->city', 
	'$this->state', 
	'$this->comments',
	'$this->hobby_cycling', 
	'$this->hobby_frisbee', 
	'$this->hobby_skiing')";
	mysql_query($query) or die(mysql_error());
	$this->id = mysql_insert_id();
	mysql_close();
	
	// Send Welcome Email
	$message = 'Dear '.$this->firstname.',\n 
	Welcome to User Profile!\n
	You can view and update your profile by visiting http://joeparavisini.com/userprofile/view/'.$this->id.' \n
	Good luck!\n' ;
	$this->userMail($this->email, 'Welcome to User Profile!', $message);
	return $this->id;
	}
	/**
	 * getUserData
	 *
	 * @param string $id 
	 * @return array
	 * @author Joe Paravisini
	 */
	public function getUserData($id) {
	mysql_connect('localhost', 'root');
	mysql_selectdb('userprofile');
	$query = "SELECT * FROM users WHERE id=$id LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	$results = mysql_fetch_assoc($sql);
	
	$content = '<p><a href="/userprofile/edit/'.$id.'">Edit Your Profile</a> - <a href="/userprofile/delete/'.$id.'">Delete Your Profile</a></p>';

	$content .= '<p><strong>First Name:</strong>'.$results['firstname'].'<br>';
	$content .= '<strong>Last Name:</strong>'.$results['lastname'].'<br>';
	$content .= '<strong>E-Mail Address:</strong>'.$results['email'].'<br>';
	$content .= '<strong>Gender:</strong>'.$results['sex'].'<br>';
	$content .= '<strong>City:</strong>'.$results['city'].'<br>';
	$content .= '<strong>State:</strong>'.$results['state'].'<br>';
	$content .= '<strong>Hobbies:</strong>'.$results['hobby_cycling'].'</p>';
	
	return $content;
	}
	
	public function getUserDataArray($id) {
		mysql_connect('localhost', 'root');
		mysql_selectdb('userprofile');
		$query = "SELECT * FROM users WHERE id=$id LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());
		$results = mysql_fetch_assoc($sql);
		return $results;
		}
	
	public function getAllUsersData(){
		mysql_connect('localhost', 'root');
		mysql_selectdb('userprofile');
		$query = "SELECT * FROM users";
		$sql = mysql_query($query) or die(mysql_error());
		$results = '<ul>';
		while($row = mysql_fetch_array($sql))
		{
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$results .= "<li><a href='/userprofile/view/$id'>$firstname $lastname</a></li>";
		}
		$results .= '</ul>';
		
		return $results;
	}
	
	public function deleteUser($id) {
		$data = $this->getUserDataArray($id);
		$id = $data['id'];
		$firstname = $data['firstname'];
		$hash = md5($data['email'].$id.date('YMD'));
		$message = "Hello $firstname, \n Please click the link below to confirm deletion of your account: \n\n
		http://joeparavisini.com/userprofile/deleteconfirm/$id/$hash
		";
		$content = '<p>Sorry to see you go! For security, a confirmation e-mail has been sent to you. Clicking the link in that e-mail will complete your account deletion.<br><a href="/userprofile">Go Home</a></p>
		';
		$this->userMail($data['email'], 'Confirm deletion of your account', $message);
		return $content;
	}
	
	public function deleteUserConfirm($id,$hash) {
		$data = $this->getUserDataArray($id);
		$key = md5($data['email'].$data['id'].date('YMD'));
		if ($key == $hash) {
		mysql_connect('localhost', 'root');
		mysql_selectdb('userprofile');
		$query = "DELETE FROM users WHERE id='$id'";
		mysql_query($query) or die(mysql_error());
		mysql_close();
		return 'Successfully deleted '.$data['firstname'];
		} else {
		return 'Invalid or expired confirmation link.';
		}
	}
	public function userForm(array $data) {
	$action = '/userprofile/edit/'. $data['id'];
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	$email = $data['email'];
	$sex = $data['sex'];
	$city = $data['city'];
	$state = $data['state'];
	$comments = $data['comments'];
	$hobby_cycling = $data['hobby_cycling'];
	$hobby_frisbee = $data['hobby_frisbee'];
	$hobby_skiing = $data['hobby_skiing'];

	$content = "
		<form action='$action' method='POST'>
		<label for='firstname'>First Name: </label><input type='text' name='firstname' id='firstname' value='$firstname'><br>
		<label for='lastname'>Last Name: </label><input type='text' name='lastname' id='lastname' value='$lastname'><br>
		<label for='email'>E-Mail Address: </label><input type='text' name='email' id='email' value='$email'><br>
		<label for='sex'>Gender: </label><select name='sex'>
			<option value=''></option>
			<option value='M'>Male</option>
			<option value='F'>Female</option>
		</select><br>
		<label for='city'>City</label><input type='text' name='city' id='city' value='$city'><br>
		<label for='state'>State</label><select name='state' id='state'>
			<option value='AL'>Alabama</option> 
			<option value='AK'>Alaska</option> 
			<option value='AZ'>Arizona</option> 
			<option value='AR'>Arkansas</option> 
			<option value='CA'>California</option> 
			<option value='CO'>Colorado</option> 
			<option value='CT'>Connecticut</option> 
			<option value='DE'>Delaware</option> 
			<option value='DC'>District Of Columbia</option> 
			<option value='FL'>Florida</option> 
			<option value='GA'>Georgia</option> 
			<option value='HI'>Hawaii</option> 
			<option value='ID'>Idaho</option> 
			<option value='IL'>Illinois</option> 
			<option value='IN'>Indiana</option> 
			<option value='IA'>Iowa</option> 
			<option value='KS'>Kansas</option> 
			<option value='KY'>Kentucky</option> 
			<option value='LA'>Louisiana</option> 
			<option value='ME'>Maine</option> 
			<option value='MD'>Maryland</option> 
			<option value='MA'>Massachusetts</option> 
			<option value='MI'>Michigan</option> 
			<option value='MN'>Minnesota</option> 
			<option value='MS'>Mississippi</option> 
			<option value='MO'>Missouri</option> 
			<option value='MT'>Montana</option> 
			<option value='NE'>Nebraska</option> 
			<option value='NV'>Nevada</option> 
			<option value='NH'>New Hampshire</option> 
			<option value='NJ'>New Jersey</option> 
			<option value='NM'>New Mexico</option> 
			<option value='NY'>New York</option> 
			<option value='NC'>North Carolina</option> 
			<option value='ND'>North Dakota</option> 
			<option value='OH'>Ohio</option> 
			<option value='OK'>Oklahoma</option> 
			<option value='OR'>Oregon</option> 
			<option value='PA'>Pennsylvania</option> 
			<option value='RI'>Rhode Island</option> 
			<option value='SC'>South Carolina</option> 
			<option value='SD'>South Dakota</option> 
			<option value='TN'>Tennessee</option> 
			<option value='TX'>Texas</option> 
			<option value='UT'>Utah</option> 
			<option value='VT'>Vermont</option> 
			<option value='VA'>Virginia</option> 
			<option value='WA'>Washington</option> 
			<option value='WV'>West Virginia</option> 
			<option value='WI'>Wisconsin</option> 
			<option value='WY'>Wyoming</option>
		</select><br>
		<label for=''>Comments</label><textarea name='' id=''>$comments</textarea><br>
		<label>Hobbies</label>
		<div class='checkbox'>
		<input type='checkbox' name='hobby_cycling' id='hobby_cycling' > <label for='hobby_cycling'>Cycling</label><br>
		<input type='checkbox' name='hobby_frisbee' id='hobby_frisbee'> <label for='hobby_frisbee'>Frisbee</label><br>
		<input type='checkbox' name='hobby_skiing' id='hobby_skiing'> <label for='hobby_skiing'>Skiing</label><br></div>
		<br class='clear'>
		<input type='submit' value='Submit'><br>
		</form>";
		return $content;
	}
	private function userMail($to, $subject, $message) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'To:' . $to . "\r\n";
	$headers .= 'From: User Profiles <no-reply@joeparavisini.com>' . "\r\n";
	$headers .= 'Bcc: jp@julyseven.com' . "\r\n";
	return $message;
//	mail($to, $subject, $message, $headers);
	}
	
}
/* End of file userprofile.php */
/* Location: ./userprofile.php */