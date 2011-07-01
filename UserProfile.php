<?php
/**
 * 
 *
 * @author Joe Paravisini
 * @version 0.1a
 * @copyright Joe Paravisini, 30 June, 2011
 * @package n/a
 **/



/*
id integer not null auto_increment primary key, 
firstname char(50), 
lastname char(50), 
email char(100), 
sex char(1),  // M, F 
city char(50), 
state char(2), // AK, AL, ... WI, WY 
comments text, 
hobby_cycling integer,  // 1 = Yes, 0 = No 
hobby_frisbee integer, // 1 = Yes, 0 = No 
hobby_skiing integer, // 1 = Yes, 0 = No

*/
	class UserProfile {
	
	function __construct() {
		
	}

	public function createUser() {
		$query = "INSERT INTO userinfo firstname,lastname,email,sex,city,state,comments,hobby_cycling,hobby_frisbee,hobby_skiing 
		VALUES('$firstname', '$lastname', '$email', '$sex', '$city', '$state', '$comments', '$hobby_cycling', '$hobby_frisbee', '$hobby_skiing')";

		//mysql_query($query) or die();
		
	}
	/**
	 * getUserData
	 *
	 * @param string $id 
	 * @return array
	 * @author Joe Paravisini
	 */
	public function getUserData($id) {
		(int)$id;
		$data['firstname'] = 'Joe'.$id;
		return $data;
	}
}
/* End of file userprofile.php */
/* Location: ./userprofile.php */