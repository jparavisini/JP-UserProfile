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

	private $genders = array('M' => 'Male', 'F' => 'Female');
	private $states = array('AL' => 'Alabama', 'AK' => 'Alaska',  'AZ' => 'Arizona',  'AR' => 'Arkansas',  'CA' => 'California',
							'CO' => 'Colorado',  'CT' => 'Connecticut',  'DE' => 'Delaware',  'DC' => 'District Of Columbia',
							'FL' => 'Florida',  'GA' => 'Georgia',  'HI' => 'Hawaii',  'ID' => 'Idaho',  'IL' => 'Illinois',
							'IN' => 'Indiana',  'IA' => 'Iowa',  'KS' => 'Kansas',  'KY' => 'Kentucky',  'LA' => 'Louisiana',
							'ME' => 'Maine',  'MD' => 'Maryland',  'MA' => 'Massachusetts',  'MI' => 'Michigan',  'MN' => 'Minnesota',
							'MS' => 'Mississippi',  'MO' => 'Missouri',  'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
							'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina',
							'ND' => 'North Dakota', 'OH' => 'Ohio',  'OK' => 'Oklahoma',  'OR' => 'Oregon',  'PA' => 'Pennsylvania',
							'RI' => 'Rhode Island',  'SC' => 'South Carolina',  'SD' => 'South Dakota', 'TN' => 'Tennessee',  'TX' => 'Texas',
							'UT' => 'Utah',  'VT' => 'Vermont',  'VA' => 'Virginia',  'WA' => 'Washington',  'WV' => 'West Virginia',
							'WI' => 'Wisconsin',  'WY' => 'Wyoming');
							
	private $hobbies = array('hobby_cycling' => 'Cycling', 'hobby_frisbee' => 'Frisbee', 'hobby_skiing' => 'Skiing');
	
	private $db_host = 'localhost';
	private $db_port = '3306';
	private $db_user = 'userprofile';
	private $db_pass = '8fh234irf89h';
	private $db_db   = 'userprofile';
	
	function __construct() {
		
	}
	
	/**
	 * createUser
	 *
	 * @param array $data 
	 * @return integer
	 * @author Joe Paravisini
	 */
	public function createUser(array $data) {
		$con = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db, $con);
		
		$this->firstname = mysql_real_escape_string($data['firstname']);
		$this->lastname = mysql_real_escape_string($data['lastname']); 
		$this->email = mysql_real_escape_string($data['email']);
		$this->sex = mysql_real_escape_string($data['sex']);
		$this->city = mysql_real_escape_string($data['city']);
		$this->state = mysql_real_escape_string($data['state']);
		$this->comments = mysql_real_escape_string($data['comments']);
		$this->hobby_cycling = mysql_real_escape_string($data['hobby_cycling']); 
		$this->hobby_frisbee = mysql_real_escape_string($data['hobby_frisbee']); 
		$this->hobby_skiing = mysql_real_escape_string($data['hobby_skiing']);

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

		mysql_query($query, $con) or die(mysql_error($con));
		$this->id = mysql_insert_id($con);
		mysql_close($con);
		
		// Send Welcome Email
		$message = 'Dear '.$this->firstname.', 
		Welcome to User Profile!
		You can view and update your profile by visiting http://joeparavisini.com/userprofile/view/'.$this->id.'
		Good luck!' ;
		$this->userMail($this->email, 'Welcome to User Profile!', $message);
		return $this->id;
	}
	
	/**
	 * getUserData
	 *
	 * @param string $id 
	 * @return string
	 * @author Joe Paravisini
	 */
	public function getUserData($id) {
		$con = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db, $con);

		$query = "SELECT * FROM users WHERE id=$id LIMIT 1";
		$sql = mysql_query($query,$con) or die(mysql_error($con));
		$results = mysql_fetch_assoc($sql);
		
		$content = '<p><a href="/userprofile/edit/'.$id.'">Edit Your Profile</a> - <a href="/userprofile/delete/'.$id.'">Delete Your Profile</a></p>';

		$content .= '<p><strong>First Name:</strong>'.$results['firstname'].'<br>';
		$content .= '<strong>Last Name:</strong>'.$results['lastname'].'<br>';
		$content .= '<strong>E-Mail Address:</strong>'.$results['email'].'<br>';
		$content .= '<strong>Gender:</strong>'.$results['sex'].'<br>';
		$content .= '<strong>City:</strong>'.$results['city'].'<br>';
		$content .= '<strong>State:</strong>'.$results['state'].'<br>';
		$content .= '<strong>Comments:</strong>'.$results['comments'].'<br>';

		$hobbies = array();
		if ($results['hobby_cycling']) {
		$hobbies[] = "Cycling";
		}
		if ($results['hobby_frisbee']) {
		$hobbies[] = "Frisbee";
		}
		if ($results['hobby_skiing']) {
		$hobbies[] = "Skiing";
		}
		$hobbylist = implode(', ', $hobbies);
		$content .= '<strong>Hobbies: </strong>'.$hobbylist;
		$content .= '</p>';
		mysql_close($con);
		
		return $content;
	}
	
	/**
	 * getUserDataArray
	 *
	 * @param string $id 
	 * @return array
	 * @author Joe Paravisini
	 */
	public function getUserDataArray($id) {
		mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db);
		$query = "SELECT * FROM users WHERE id=$id LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());
		$results = mysql_fetch_assoc($sql);
		return $results;
	}
	
	/**
	 * getAllUsersData
	 *
	 * @return string
	 * @author Joe Paravisini
	 */
	public function getAllUsersData(){
		$con = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db, $con);
		$query = "SELECT * FROM users";
		$sql = mysql_query($query,$con) or die(mysql_error($con));
		$results = '<ul>';
		while($row = mysql_fetch_array($sql))
		{
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$results .= "<li><a href='/userprofile/view/$id'>$firstname $lastname</a></li>";
		}
		$results .= '</ul>';
		mysql_close($con);
		return $results;
	}
	
	/**
	 * updateUser
	 *
	 * @param integer $id, array $data 
	 * @return integer
	 * @author Joe Paravisini
	 */
	public function updateUser($id, array $data) {
		$con = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db, $con);
		
		$this->firstname = mysql_real_escape_string($data['firstname']);
		$this->lastname = mysql_real_escape_string($data['lastname']); 
		$this->email = mysql_real_escape_string($data['email']);
		$this->sex = mysql_real_escape_string($data['sex']); 
		$this->city = mysql_real_escape_string($data['city']);
		$this->state = mysql_real_escape_string($data['state']);
		$this->comments = mysql_real_escape_string($data['comments']);
		$this->hobby_cycling = mysql_real_escape_string($data['hobby_cycling']); 
		$this->hobby_frisbee = mysql_real_escape_string($data['hobby_frisbee']); 
		$this->hobby_skiing = mysql_real_escape_string($data['hobby_skiing']);

		$query = "UPDATE users SET firstname='$this->firstname',
		lastname='$this->lastname',
		email='$this->email',
		sex='$this->sex',
		city='$this->city',
		state='$this->state',
		comments='$this->comments',
		hobby_cycling='$this->hobby_cycling',
		hobby_frisbee='$this->hobby_frisbee',
		hobby_skiing='$this->hobby_skiing' WHERE id='$id'";
		
		mysql_query($query, $con) or die(mysql_error($con));
		mysql_close($con);
		return $id;
	}
	
	/**
	 * deleteUser
	 *
	 * @param integer $id
	 * @return boolean
	 * @author Joe Paravisini
	 */
	public function deleteUser($id) {
		$data = $this->getUserDataArray($id);
		$id = $data['id'];
		$firstname = $data['firstname'];
		$hash = md5($data['email'].$id.date('YMD'));
		$message = "Hello $firstname, 
Please click the link below to confirm deletion of your account: 
http://joeparavisini.com/userprofile/deleteconfirm/$id/$hash";
		
		$content = '<p>Sorry to see you go! For security, a confirmation e-mail has been sent to you. Clicking the link in that e-mail will complete your account deletion.<br><a href="/userprofile">Go Home</a></p>';
		
		$this->userMail($data['email'], 'Confirm deletion of your account', $message);
		return TRUE;
	}
	/**
	 * deleteUserConfirm
	 *
	 * @param integer $id, string $hash
	 * @return boolean
	 * @author Joe Paravisini
	 */
	public function deleteUserConfirm($id,$hash) {
		$data = $this->getUserDataArray($id);
		$key = md5($data['email'].$data['id'].date('YMD'));
		if ($key == $hash) {
		$con = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		mysql_selectdb($this->db_db, $con);
		$query = "DELETE FROM users WHERE id='$id'";
		mysql_query($query,$con) or die(mysql_error($con));
		mysql_close($con);
		return TRUE;
		//return 'Successfully deleted '.$data['firstname'];
		} else {
		//return 'Invalid or expired confirmation link.';
		return FALSE;
		}
	}
	
	/**
	 * userForm
	 * The world's messiest form generator. 
	 * @param array $data
	 * @return boolean
	 * @author Joe Paravisini
	 */
	public function userForm() {
	$numargs = func_num_args();
	if ($numargs > 0) {
		$data = func_get_arg(0);
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
		
		$content = "<form action='$action' method='POST'>
			<label for='firstname'>First Name: </label><input type='text' name='firstname' id='firstname' value='$firstname'><br>
			<label for='lastname'>Last Name: </label><input type='text' name='lastname' id='lastname' value='$lastname'><br>
			<label for='email'>E-Mail Address: </label><input type='text' name='email' id='email' value='$email'><br>";
			
		$content .= "<label for='sex'>Gender: </label><select name='sex'><option value=''></option>";
		
		foreach ($this->genders as $code => $label) {
			if ($data['sex'] == $code){ 
				$content .= "<option value='$code' selected>$label</option>";
			}else {
				$content .= "<option value='$code'>$label</option>";
			}
		}
		$content .=	"</select><br>";
			
		$content .= "<label for='city'>City</label><input type='text' name='city' id='city' value='$city'><br>";
		
		$content .= "<label for='state'>State</label><select name='state' id='state'>";
		foreach ($this->states as $code => $label) {
			if ($state == $code){ 
				$content .= "<option value='$code' selected>$label</option>";
			}else {
				$content .= "<option value='$code'>$label</option> ";
			}
		}
		$content .= "</select><br>
			<label for='comments'>Comments</label><textarea name='comments' id='comments'>$comments</textarea><br>
			<label>Hobbies</label>
			<div class='checkbox'>";
		
		if ($hobby_cycling) {
		$content .="<input type='checkbox' name='hobby_cycling' id='hobby_cycling' checked> <label for='hobby_cycling'>Cycling</label><br>";
		} else {
				$content .="<input type='checkbox' name='hobby_cycling' id='hobby_cycling'> <label for='hobby_cycling'>Cycling</label><br>";
		}
		if ($hobby_frisbee) {
		$content .="<input type='checkbox' name='hobby_frisbee' id='hobby_frisbee' checked> <label for='hobby_frisbee'>Frisbee</label><br>";
		} else {
				$content .="<input type='checkbox' name='hobby_frisbee' id='hobby_frisbee'> <label for='hobby_frisbee'>Frisbee</label><br>";
		}		
		if ($hobby_skiing) {
		$content .="<input type='checkbox' name='hobby_skiing' id='hobby_skiing' checked> <label for='hobby_skiing'>Skiing</label><br>";
		} else {
				$content .="<input type='checkbox' name='hobby_skiing' id='hobby_skiing'> <label for='hobby_skiing'>Skiing</label><br>";
		}
		
		$content .="</div><br class='clear'>
			<input type='submit' value='Submit'>
			</form><br><br>";
	} else {
		$action = '/userprofile/new/';
		$content = "
			<form action='$action' method='POST'>
			<label for='firstname'>First Name: </label><input type='text' name='firstname' id='firstname'><br>
			<label for='lastname'>Last Name: </label><input type='text' name='lastname' id='lastname'><br>
			<label for='email'>E-Mail Address: </label><input type='text' name='email' id='email'><br>";
			
		$content .= "<label for='sex'>Gender: </label><select name='sex'><option value=''></option>";
		
		foreach ($this->genders as $code => $label) {
				$content .= "<option value='$code'>$label</option>";
		}
		$content .=	"</select><br>";
			
		$content .= "<label for='city'>City</label><input type='text' name='city' id='city'><br>";
		
		$content .= "<label for='state'>State</label><select name='state' id='state'>";
		foreach ($this->states as $code => $label) {
				$content .= "<option value='$code'>$label</option> ";
		}
		$content .= "
			</select><br>
			<label for='comments'>Comments</label><textarea name='comments' id='comments'></textarea><br>
			<label>Hobbies</label>
			<div class='checkbox'>";

		$content .="	<input type='checkbox' name='hobby_cycling' id='hobby_cycling' > <label for='hobby_cycling'>Cycling</label><br>
			<input type='checkbox' name='hobby_frisbee' id='hobby_frisbee'> <label for='hobby_frisbee'>Frisbee</label><br>
			<input type='checkbox' name='hobby_skiing' id='hobby_skiing'> <label for='hobby_skiing'>Skiing</label><br></div>
			<br class='clear'>
			<input type='submit' value='Submit'>
			</form><br><br>";
	}
		
		return $content;
	}
	/**
	 * userMail
	 *
	 * @param string $to 
	 * @param string $subject 
	 * @param string $message 
	 * @return bool
	 * @author Joe Paravisini
	 */
	private function userMail($to, $subject, $message) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To:' . $to . "\r\n";
		$headers .= 'From: User Profiles <no-reply@joeparavisini.com>' . "\r\n";
		if (mail($to, $subject, $message, $headers)) {
			return TRUE;
		} else {
			return FALSE;
		};
	}
	
}
/* End of file userprofile.php */
/* Location: ./userprofile.php */
