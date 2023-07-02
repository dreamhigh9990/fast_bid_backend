<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class admin_user extends REST_Controller
{
	function sign_up_post()
	{
		if (!$this->post('fullName') or !$this->post('email') or !$this->post('password')) {
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
		}
		$user_name 		= $this->post('email');
		$email 			= $this->post('email');
		$password 		= $this->post('password');
		$full_name 		= $this->post('fullName');

		$this->load->model("Adminusers_model");
		if ($query = $this->Adminusers_model->create_member($user_name, $full_name, $email, $password)) {
			$this->response(array("status" => 200, "message" => "Successfully added"), 200);
		} else {
			$this->response(array("status" => 400, "message" => "Already used user name"), 200);
		}
	}

	function sign_in_post()
	{
		if (!$this->post('email') or !$this->post('password')) {
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
		}

		$email			= $this->post('email');
		$password 		= $this->post('password');

		$this->load->model("Adminusers_model");
		$is_valid = $this->Adminusers_model->validate($email, $password);
		if ($is_valid > 0) {
			$user = $this->Adminusers_model->get_admin_user_info_with_email($email);
			$user->token = $user->id;
			$this->response(array("status" => 200, "admin_user" => $user, "data" => array("token" => $user->token)), 200);
		} else {
			$this->response(array("status" => 401, "message" => "Password doesn't match"), 200);
		}
	}

	function get_user_info_get()
	{
		if (!$this->get('token')) {
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
		}

		$user_id = $this->get('token');

		$this->load->model("Adminusers_model");
		$user = $this->Adminusers_model->get_admin_user_info_with_id($user_id);
		if (!$user)
			$this->response(array("status" => 400, "message" => "Invalid User Name"), 200);
		$this->response(array("status" => 200, "admin_user" => $user), 200);
	}

	function get_user_info_post()
	{
		if (!$this->post('user_name')) {
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
		}

		$user_name = $this->post('user_name');

		$this->load->model("Adminusers_model");
		$user = $this->Adminusers_model->get_admin_user_info($user_name);
		if (!$user)
			$this->response(array("status" => 400, "message" => "Invalid User Name"), 200);
		$this->response(array("status" => 200, "admin_user" => $user), 200);
	}

	function set_user_info_post()
	{
		if (!$this->post('user_info'))
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);

		$user_info = json_decode($this->post('user_info'), true);
		if (!$user_info)
			$this->response(array("status" => 401, "message" => "Invalid user info"), 200);

		if (!isset($user_info['user_name']))
			$this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);

		$this->load->model("Adminusers_model");

		if (isset($user_info["password"]) && isset($user_info["currentPassword"])) {
			$user = $this->Adminusers_model->get_admin_user_info($user_info["user_name"]);
			if ($user['password'] == MD5($user_info['currentPassword'])) {
				$user_info["password"] = MD5($user_info["password"]);
			} else {
				$this->response(array("status" => 402, "message" => "Current password is incorrect."), 200);
			}
		} else {
			unset($user_info['password']);
		}
		unset($user_info['currentPassword']);

		$upload = FALSE;
		if (isset($_FILES['image'])) {
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

		if ($this->Adminusers_model->update_users($user_info["user_name"], $user_info)) {
			$user = $this->Adminusers_model->get_admin_user_info($user_info["user_name"]);
			$this->response(array("status" => 200, "message" => "Successfully updated", "admin_user" => $user), 200);
		}
		$this->response(array("status" => 400, "message" => "Error occured while updating on DB"), 200);
	}
}
