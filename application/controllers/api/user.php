<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class user extends REST_Controller
{	
	public function __construct() {
		parent::__construct();
		$this->load->model('dictionary_model'); 
		$this->load->model('grammartest_model');
		$this->load->model('knowledge_model'); 
		   
	}

	function sign_up_post()
	{
		if(!$this->post('users_id') or !$this->post('user_name') or !$this->post('full_name') or !$this->post('email') or !$this->post('password'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$user_name 			= $this->post('user_name');
		$email 			= $this->post('email');
		$password 		= $this->post('password');
		$full_name 		= $this->post('full_name');
		
		$this->load->model("users_model");
		if ($this->users_model->check_user_exists_email($email)) {
			//$this->response(array("status"=>400, "message"=>"Already used email address"), 200);
		}

		if ($this->users_model->check_user_exists_user_name($user_name)) {
			//$this->response(array("status"=>401, "message"=>"Already used user name"), 200);
		}

		if ($this->users_model->get_users_name_by_users_id($users_id) == "")
			$this->response(array("status"=>402, "message"=>"Invalid user ID"), 200);

		$this->users_model->update_users($users_id, array("user_name"=>$user_name, "email"=>$email, "password"=>md5($password), "full_name"=>$full_name));
		$users_id = $this->users_model->signin_user($user_name, $password);
		$user = $this->users_model->get_users_by_id($users_id);
		$this->response(array("status"=>200, "user"=>$user), 200);
	}

	function add_new_user_post()
	{
		if(!$this->post('user_name') or !$this->post('full_name') or !$this->post('email'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$user_name 		= $this->post('user_name');
		$email 			= $this->post('email');
		$password 		= "admin";
		$full_name 		= $this->post('full_name');
		$source_language = "en";
		$target_language = "zh-CN";
		
		$this->load->model("users_model");
		if ($this->users_model->check_user_exists_email($email)) {
			$this->response(array("status"=>400, "message"=>"Already used email address"), 200);
		}

		if ($this->users_model->check_user_exists_user_name($user_name)) {
			$this->response(array("status"=>401, "message"=>"Already used user name"), 200);
		}

		$users_id = $this->users_model->store_users(array("udid"=>$this->guidv4(openssl_random_pseudo_bytes(16)), "source_language"=>$source_language, "target_language"=>$target_language, "user_name"=>$user_name, "email"=>$email, "full_name"=>$full_name, "password"=>md5($password)));

		$user = $this->users_model->get_users_by_id($users_id);
		$this->response(array("status"=>200, "user"=>$user), 200);
	}

	function guidv4($data)
	{
	    assert(strlen($data) == 16);

	    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

	    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	function sign_in_post()
	{
		if ($this->post('token') && $this->post('token') > 0) {
			$users_id = $this->post('token');
			$this->load->model("users_model");
			$user = $this->users_model->get_users_by_id($users_id);
			$user['token'] = $users_id;
			$this->response(array("status"=>200, "user"=>$user), 200);
		}

		if(!$this->post('user_name') or !$this->post('password'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$user_name		= $this->post('user_name');
		$password 		= $this->post('password');
		
		$this->load->model("users_model");
		$users_id = $this->users_model->signin_user($user_name, $password);
		
		if ($users_id > 0) {
			$user = $this->users_model->get_users_by_id($users_id);
			$user['token'] = $users_id;
			$this->response(array("status"=>200, "user"=>$user), 200);
		} else if ($users_id == -1) {
			$this->response(array("status"=>401, "message"=>"UserName doesn't match"), 200);
		} else if ($users_id == -2) {
			$this->response(array("status"=>401, "message"=>"Password doesn't match"), 200);
		}
	}

	function create_knowledge_table($users_id, $word_count, $grammar_count){
		$result = $this->knowledge_model->create_knowledge_database($users_id, $word_count, $grammar_count);
		return $result;
	}

	function sign_up_auto_post()
	{
		$word_count = $this->post('word_count');
		$grammar_count = $this->post('grammar_count');

		if(!$this->post('udid') or !$this->post('source_language') or !$this->post('target_language'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$udid					= $this->post('udid');
		$source_language 		= $this->post('source_language');
		$target_language 		= $this->post('target_language');
		$user_name = "user_" . substr($udid, 0, 4) . (int)(microtime() * 1000000);
		$ip = $this->post('ip');


		// Language checking...

		// Register new blank user
		
		$this->load->model("users_model");
		$users_id = $this->users_model->store_users(array("udid"=>$udid, "source_language"=>$source_language, "target_language"=>$target_language, "user_name"=>$user_name, "ip"=>$ip));
		if ($users_id > 0) {
			$user_ability_id = $this->users_model->stores_user_ability($users_id, $target_language, array());
			$user = $this->users_model->get_users_by_id($users_id);
			// $user_ability = $this->users_model->get_user_ability($user_ability_id);
			// $user["user_ability"] = $user_ability;
			
			// Knowledge Table Creating.
			$this->create_knowledge_table($users_id, $word_count, $grammar_count);
			
			$user['token'] = $users_id;
			$this->response(array("status"=>200, "user"=>$user), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Error occured while creating a new user on DB."), 200);
		}
	}

	function get_access_key_post()
	{
		if(!$this->post('udid'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $udid = $this->post('udid');
        $this->load->model("users_model");
        if (!$this->post('user_name') or !$this->post('password'))
        {
        	if (($users_id = $this->users_model->signin_user_with_udid($udid)) != -1) {
	        	$api_key = md5(microtime().rand());
	        	$this->users_model->update_users($users_id, array("session"=>$api_key, "session_date"=>date("Y-m-d")));
	        	$this->response(array("status"=>200, "api_key"=>$api_key), 200);
	        }
	        $this->response(array("status"=>401, "message"=>"Invalid udid"), 200);
        }

        $user_name = $this->post('user_name');
        $password = $this->post('password');

        if (($users_id = $this->users_model->signin_user($user_name, $password)) != -1) {
        	$api_key = md5(microtime().rand());
        	$this->users_model->update_users($users_id, array("session"=>$api_key, "session_date"=>date("Y-m-d")));
        	$this->response(array("status"=>200, "api_key"=>$api_key), 200);
        }
        $this->response(array("status"=>400, "message"=>"Invalid user name or password"), 200);
	}

	function get_user_info_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$users_id = $this->post('users_id');
		
		$this->load->model("users_model");
		if ($users_id > 0) {
			$user = $this->users_model->get_users_by_id($users_id);
			if (!$user)
				$this->response(array("status"=>400, "message"=>"Invalid User ID"), 200);
			$this->response(array("status"=>200, "user"=>$user), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid User ID"), 200);
		}
	}

	function set_user_info_post()
	{
		if (!$this->post('user_info'))
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);

		$user_info = json_decode($this->post('user_info'), true);
		if (!$user_info)
			$this->response(array("status"=>401, "message"=>"Invalid user info"), 200);

		if (!isset($user_info['users_id']))
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);

		$this->load->model("users_model");

		if (isset($user_info["user_ability"])) {
			$user_ability = $user_info["user_ability"];
			unset($user_info["user_ability"]);
		}

		if (isset($user_info["password"]) && isset($user_info["currentPassword"])) {
			$user = $this->users_model->get_users_by_id($user_info["users_id"]);
			if ($user['password'] == MD5($user_info['currentPassword'])) {
				$user_info["password"] = MD5($user_info["password"]);
			} else {
				$this->response(array("status"=>402, "message"=>"Current password is incorrect."), 200);
			}
		} else {
			unset($user_info['password']);
		}
		unset($user_info['currentPassword']);

		$upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/users/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'user_image');
            
            if (!$this->user_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->user_image->data();
                $upload = TRUE;
            }
        }

        if ($upload == TRUE) {
            $image_path = $attache_photo["file_name"];
	        $user_info["photo"] = $image_path;
        }

		if ($this->users_model->update_users($user_info["users_id"], $user_info)) {
			if (isset($user_ability)) {
				if ($this->users_model->update_user_ability($user_info["users_id"], $user_ability)) {
					$user = $this->users_model->get_users_by_id($user_info["users_id"]);
					$this->response(array("status"=>200, "message"=>"Successfully updated", "user"=>$user), 200);
				}
			} else {
				$user = $this->users_model->get_users_by_id($user_info["users_id"]);
				$this->response(array("status"=>200, "message"=>"Successfully updated", "user"=>$user), 200);
			}
		}
		$this->response(array("status"=>400, "message"=>"Error occured while updating on DB"), 200);
	
	}

	function get_users_list_get()
	{
		// if(!$this->get('token') or !$this->get('user_name'))
  //       {
  //           $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
  //       }
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $users_id_like = $this->get('users_id_like');
        $user_name_like = $this->get('user_name_like');
        $full_name_like = $this->get('full_name_like');
        $email_like = $this->get('email_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$user_name = $this->post('user_name');
		
		$this->load->model("users_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->users_model->count_users($users_id_like, $user_name_like, $full_name_like, $email_like));
		header('link: _');
		$users = $this->users_model->get_users($users_id_like, $user_name_like, $full_name_like, $email_like, $limit, $page * $limit, $sort, $order);
		$this->response($users, 200);
	}

	function delete_user_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$users_id = $this->post('users_id');
		
		$this->load->model("users_model");
		if ($users_id > 0) {
			$user = $this->users_model->get_users_by_id($users_id);
			if (!$user)
				$this->response(array("status"=>400, "message"=>"Invalid User ID"), 200);
			$this->users_model->delete_users_ability($users_id);
			$this->users_model->delete_users($users_id);
			$this->response(array("status"=>200, "message"=>"Successfully removed the user"), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid User ID"), 200);
		}
	}
}