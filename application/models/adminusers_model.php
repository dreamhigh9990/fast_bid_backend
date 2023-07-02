<?php
class Adminusers_model extends CI_Model {

    /**
    * Validate the login's data with the database
    * @param string $email
    * @param string $password
    * @return void
    */
	function validate($email, $password)
	{
		$this->db->where('email', $email);
		$this->db->where('password', md5($password));
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return true;
		}		
	}

	function validateWithUsername($user_name, $password)
	{
		$this->db->where('user_name', $user_name);
		$this->db->where('password', md5($password));
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return true;
		}		
	}

    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}

	function get_admin_user_info($user_name)
	{
		$this->db->select('*');
		$this->db->where('user_name', $user_name);
		$query = $this->db->get('membership');
		$result = $query->result();
		return $result[0];
	}

	function get_admin_user_info_with_id($user_id)
	{
		$this->db->select('*');
		$this->db->where('id', $user_id);
		$query = $this->db->get('membership');
		$result = $query->result();
		return $result[0];
	}

	function get_admin_user_info_with_email($email)
	{
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get('membership');
		$result = $query->result();
		return $result[0];
	}
	
    /**
    * Store the new user's data into the database
    * @return boolean - check the insert
    */	
	function create_member($user_name, $full_name, $email, $password)
	{
		$this->db->where('user_name', $user_name);
		$query = $this->db->get('membership');

        if($query->num_rows > 0){
        	return false;
		}else{
			$name_array = explode(' ', $full_name);
			$new_member_insert_data = array(
				'first_name' => $name_array[0],
				'last_name' => isset($name_array[1]) ? $name_array[1] : '',
				'email' => $email,
				'user_name' => $user_name,
				'password' => md5($password)
			);
			$insert = $this->db->insert('membership', $new_member_insert_data);
		    return $insert;
		}
	}

	function update_users($user_name, $data)
    {
		$this->db->where('user_name', $user_name);
		$this->db->update('membership', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}
}
