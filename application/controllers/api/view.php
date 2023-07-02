<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class view extends REST_Controller
{	
	function set_view_sentence_post() 
	{
		if (!$this->post('users_id') or !$this->post('sentences_id')) {
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		}

		$users_id = $this->post('users_id');
		$sentences_id = $this->post('sentences_id');

		$this->load->model("sentences_model");
		$this->load->model("viewed_model");
		if (!$this->sentences_model->increase_view_count($sentences_id))
			$this->response(array("status"=>401, "message"=>"Errors occured while updating sentence DB"), 200);
		$result = $this->viewed_model->store_viewed(array(
			"users_id"=>$users_id,
			"date"=>date('Y-m-d H:i:s'),
			"sentences_id"=>$sentences_id,
			));
		if (!$result) 
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);
		$this->response(array("status"=>200, "message"=>"Successfully updated view count"), 200);
	}

	function set_view_discussion_post() 
	{
		if (!$this->post('users_id') or !$this->post('questions_id')) {
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		}

		$users_id = $this->post('users_id');
		$questions_id = $this->post('questions_id');

		$this->load->model("questions_model");
		$this->load->model("viewed_model");
		if (!$this->questions_model->increase_view_count($questions_id))
			$this->response(array("status"=>401, "message"=>"Errors occured while updating discussion db"), 200);
		$result = $this->viewed_model->store_viewed(array(
			"users_id"=>$users_id,
			"date"=>date('Y-m-d H:i:s'),
			"questions_id"=>$questions_id,
			));
		if (!$result) 
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);
		$this->response(array("status"=>200, "message"=>"Successfully updated view count"), 200);
	}

	function set_view_video_sentence_post() 
	{
		if (!$this->post('users_id') or !$this->post('vsentences_id')) {
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		}

		$users_id = $this->post('users_id');
		$vsentences_id = $this->post('vsentences_id');

		$this->load->model("vsentences_model");
		$this->load->model("viewed_model");
		if (!$this->vsentences_model->increase_view_count($vsentences_id))
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);
		$result = $this->viewed_model->store_viewed(array(
			"users_id"=>$users_id,
			"date"=>date('Y-m-d H:i:s'),
			"vsentences_id"=>$vsentences_id,
			));
		if (!$result) 
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);
		$this->response(array("status"=>200, "message"=>"Successfully updated view count"), 200);
	}
}