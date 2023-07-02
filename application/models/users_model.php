<?php
class Users_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
    function validate($user_name, $password)
    {
        $this->db->where('user_name', $user_name);
        $this->db->where('password', $password);
        $this->db->where('type', 1);
        $query = $this->db->get('users');
        
        if($query->num_rows == 1)
        {
            return true;
        }       
    }

    /**
    * Get user by his id
    * @param int $id 
    * @return array
    */
    public function get_users_by_id($id)
    {
        $result = array();
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('users_id', $id);
		$query = $this->db->get();
		$user = $query->result_array();
        if ($user) {
            $this->db->select('*');
            $this->db->from('user_ability');
            $this->db->where('users_id', $id);
            $query = $this->db->get();
            $result = $user[0];
            $user_ability = $query->result_array();
            if ($user_ability)
                $result['user_ability'] = $user_ability;
        }

        return $result;
    }    

    public function get_users_name_by_users_id($users_id)
    {
        $this->db->select('user_name');
        $this->db->from('users');
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result_array = $query->result_array();
            return $result_array[0]['user_name'];
        }
        return "";
    }

    /**
    * Get all users
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_users($users_id=null, $user_name_like=null, $full_name_like=null, $email_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('users');

		if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end);	
        }

        if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
        
        if ($users_id) {
            // $this->db->where('users_id', $users_id);
            $this->db->like('users_id', $users_id);
        }
        if ($user_name_like) {
            $this->db->like('user_name', $user_name_like);
        }
        if ($full_name_like) {
            $this->db->like('full_name', $full_name_like);
        }
        if ($email_like) {
            $this->db->like('email', $email_like);
        }

        if ($sort){
            $this->db->order_by($sort, $order);
        }

		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_users($users_id=null, $user_name_like=null, $full_name_like=null, $email_like=null)
    {
		$this->db->select('*');
		$this->db->from('users');
        if ($users_id) {
            // $this->db->where('users_id', $users_id);
            $this->db->like('users_id', $users_id);
        }
        if ($user_name_like) {
            $this->db->like('user_name', $user_name_like);
        }
        if ($full_name_like) {
            $this->db->like('full_name', $full_name_like);
        }
        if ($email_like) {
            $this->db->like('email', $email_like);
        }
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_users($data)
    {
        $this->db->trans_start();
		$insert = $this->db->insert('users', $data);
        if ($insert)
            $insert_id = $this->db->insert_id();
        else
            $insert_id = -1;
        $this->db->trans_complete();
	    return $insert_id;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_users($id, $data)
    {
		$this->db->where('users_id', $id);
		$this->db->update('users', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    function update_user_ability($users_id, $user_ability)
    {
        foreach ($user_ability as $key => $ability) {
            $this->db->where('users_id', $users_id);
            $this->db->where('language', $ability['language']);
            $this->db->update('user_ability', $ability);
            $report = array();
            $report['error'] = $this->db->_error_number();
            $report['message'] = $this->db->_error_message();
            if($report !== 0){
                continue;
            }else{
                return false;
            }
        }
        return true;
    }

    /**
    * Update user location
    * @return boolean
    */
    function update_user_location($id, $longitude, $latitude)
    {
        $data = array(
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                );
        $this->db->where('users_id', $id);
        return $this->db->update('users', $data);
    }
    /**
    * Delete manufacturer
    * @param int $id - manufacture id
    * @return boolean
    */
	function delete_users($id){
		$this->db->where('users_id', $id);
		$this->db->delete('users'); 
	}

    function delete_users_ability($id){
        $this->db->where('users_id', $id);
        $this->db->delete('user_ability'); 
    }

    /**
    * Check if this user already exists
    * @param string $email, $phone
    * @return boolean
    */
    function check_user_exists_email($email)
    {
        $strQuery = "SELECT * FROM users WHERE email='$email'";
        $query = $this->db->query($strQuery);
        if ($query->num_rows() > 0)
            return true;
        return false;
    }

    /**
    * Check if this user already exists
    * @param string $user_name
    * @return boolean
    */
    function check_user_exists_user_name($user_name)
    {
        $strQuery = "SELECT * FROM users WHERE user_name='$user_name'";
        $query = $this->db->query($strQuery);
        if ($query->num_rows() > 0)
            return true;
        return false;
    }

    function stores_user_ability($users_id, $language, $data)
    {
        $data['users_id'] = $users_id;
        $data['language'] = $language;
        $this->db->trans_start();
        $insert = $this->db->insert('user_ability', $data);
        if ($insert)
            $insert_id = $this->db->insert_id();
        else
            $insert_id = -1;
        $this->db->trans_complete();
        return $insert_id;
    }

    public function get_user_ability($user_ability_id)
    {
        $this->db->select('*');
        $this->db->from('user_ability');
        $this->db->where('user_ability_id', $user_ability_id);
        $query = $this->db->get();
        $user = $query->result_array();

        return $user;
    } 


    
    function check_verify_code($phone, $verify_code)
    {
        $strQuery = "SELECT * FROM verification WHERE code='$verify_code' AND phone='$phone'";
        $query = $this->db->query($strQuery);
        if ($query->num_rows() > 0)
            return false;
        return true;
    }

    function get_users_id_by_user_name($user_name)
    {
        $strQuery = "SELECT users_id FROM users WHERE user_name='$user_name'";
        $query = $this->db->query($strQuery);
        if ($query->num_rows() > 0) {
            $users_ids = $query->result_array();
            return $users_ids[0]['users_id'];
        }
        return -1;
    }

    function update_verification_code($phone, $verify_code)
    {
        $this->db->where('phone', $phone);
        $this->db->delete('verification'); 

        $data = array(
                    'phone' => $phone,
                    'code' => $verify_code,
                );
        return $this->db->insert('verification', $data);
    }

    function signin_user_with_email($email, $password)
    {
        $password = md5($password);
        $strQuery = "SELECT users_id FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $query = $this->db->query($strQuery);

        if ($query->num_rows() == 1){
            $user_data = $query->result_array(); 
            return $user_data[0]['users_id'];
        } else {
            return -1;
        }
    }

    function signin_user($user_name, $password)
    {
        $password = md5($password);
        $strQuery = "SELECT users_id FROM users WHERE user_name='$user_name' AND password='$password' LIMIT 1";
        $query = $this->db->query($strQuery);

        if ($query->num_rows() == 1){
            $user_data = $query->result_array(); 
            return $user_data[0]['users_id'];
        } else if ($query->num_rows() == 0) {
            $strQuery = "SELECT users_id FROM users WHERE user_name='$user_name'  LIMIT 1";
            $query = $this->db->query($strQuery);
            if ($query->num_rows() == 1){
                return -2;
            } else {
                return -1;
            }
        }
    }

    function signin_user_with_udid($udid)
    {
        $strQuery = "SELECT users_id FROM users WHERE udid='$udid' LIMIT 1";
        $query = $this->db->query($strQuery);

        if ($query->num_rows() == 1){
            $user_data = $query->result_array(); 
            return $user_data[0]['users_id'];
        } else {
            return -1;
        }
    }

    function change_password($users_id, $new_password, $old_password)
    {
        $this->db->select('*');
        $this->db->where('password', md5($old_password));
        $this->db->where('users_id', $users_id);
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return 402;

        $data = array(
                    'password' => md5($new_password),
                );
        $this->db->where('users_id', $users_id);
        if ($this->db->update('users', $data) == true)
            return 200;
        return 403;
    }

    function get_all_deliverers($slong, $slat, $elong, $elat)
    {
        $strQuery = "SELECT users_id, longitude, latitude, photo, name, feedback_deliverer, feedback_customer FROM users WHERE $slong<longitude AND $lat<latitude AND $elong>longitude AND $elat>latitude AND is_deliverer=TRUE LIMIT 100";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            return $query->result_array();
        }
    }

    function get_all_delivery($slong, $slat, $elong, $elat, $users_id)
    {
        $strQuery = "SELECT * FROM deliveries WHERE (($slong<flong AND $slat>flat AND $elong>flong AND $elat<flat) OR ($slong<tlong AND $slat>tlat AND $elong>tlong AND $elat<tlat)) AND (deliverer_id = 0 AND customer_id != $users_id) LIMIT 100";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            $result_array = $query->result_array();
            $index = 0;
            foreach ($result_array as $value) 
            {
                // Get deliverer data
                $customer_id = $value['customer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$customer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback)
                    $result_array[$index]['customer'] = $feedback[0];
                
                // Get customer data
                $deliverer_id = $value['deliverer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$deliverer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback) 
                    $result_array[$index]['deliverer'] = $feedback[0];

                $index++;
            }
            return $result_array;
        }
        return null;
    }

    function create_new_delivery($delivery_data)
    {
        $status = $this->db->insert('deliveries', $delivery_data);
        return $status;
    }

    function change_user_photo($users_id, $photo_name)
    {
        $data = array(
                    'photo' => $photo_name,
                );
        $this->db->where('users_id', $users_id);
        return $this->db->update('users', $data);
    }

    function apply_on_delivery($delivery_id, $deliverer_id)
    {
        $data = array(
                    'deliverer_id' => $deliverer_id,
                    'state' => 1, // Delivery in progress
                );
        $this->db->where('deliveries_id', $delivery_id);
        return $this->db->update('deliveries', $data);
    }

    function finish_delivery($delivery_id, $deliverer_id)
    {
        $data = array(
                    'state' => 2, // Delivery in finish
                );
        $this->db->where('deliveries_id', $delivery_id);
        return $this->db->update('deliveries', $data);
    }

    function accept_delivery($delivery_id, $deliverer_id)
    {
        $data = array(
                    'state' => 3, // Delivery in accept
                );
        $this->db->where('deliveries_id', $delivery_id);
        return $this->db->update('deliveries', $data);
    }

    function leave_customer_feedback($delivery_id, $feedback)
    {
        $data = array(
                    'feedback_customer' => $feedback, // Delivery in accept
                );
        $this->db->where('deliveries_id', $delivery_id);
        return $this->db->update('deliveries', $data);
    }

    function leave_deliverer_feedback($delivery_id, $feedback)
    {
        $data = array(
                    'feedback_deliverer' => $feedback, // Delivery in accept
                );
        $this->db->where('deliveries_id', $delivery_id);
        return $this->db->update('deliveries', $data);
    }

    function set_is_deliverer($users_id, $is_deliverer)
    {
        $data = array(
                    'is_deliverer' => $is_deliverer,
                );
        $this->db->where('users_id', $users_id);
        return $this->db->update('users', $data);
    }

    function get_current_my_deliverer($customer_id)
    {
        $strQuery = "SELECT * FROM deliveries WHERE customer_id=$customer_id AND deliverer_id !=0 LIMIT 1";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            $result_array = $query->result_array();
            $index = 0;
            foreach ($result_array as $value) 
            {
                // Get deliverer data
                $customer_id = $value['customer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$customer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback)
                    $result_array[$index]['customer'] = $feedback[0];
                
                // Get customer data
                $deliverer_id = $value['deliverer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$deliverer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback) 
                    $result_array[$index]['deliverer'] = $feedback[0];

                $index++;
            }
        }
        return $result_array;
    }

    function get_current_my_delivery($deliverer_id)
    {
        $strQuery = "SELECT * FROM deliveries WHERE deliverer_id=$deliverer_id LIMIT 1";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            $result_array = $query->result_array();
            $index = 0;
            foreach ($result_array as $value) 
            {
                // Get deliverer data
                $customer_id = $value['customer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$customer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback)
                    $result_array[$index]['customer'] = $feedback[0];
                
                // Get customer data
                $deliverer_id = $value['deliverer_id'];
                $strQuery = "SELECT * FROM users WHERE users_id=$deliverer_id";
                $query = $this->db->query($strQuery);
                $feedback = $query->result_array();
                if ($feedback) 
                    $result_array[$index]['deliverer'] = $feedback[0];

                $index++;
            }
        }
        return $result_array;
    }

    function get_user_count_per_day() {
        $this->db->select('session_date as date, COUNT(*) as u_cnt');
        $this->db->from("users");
        $this->db->group_by("session_date");
        $query = $this->db->get();
		return $query->result_array();
    }
}