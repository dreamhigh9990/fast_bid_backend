<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class knowledge extends REST_Controller
{	
  	public function __construct() {
		parent::__construct();
		$this->load->model('dictionary_model'); 
		$this->load->model('grammartest_model');
		$this->load->model('knowledge_model'); 
	}
	
	public function get_knowledge_data_post() {
		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$limit_start = $this->post('limit_start');
		$limit_count = $this->post('limit_count');

		$knowledge_list = $this->knowledge_model->get_knowledge_data($users_id, $limit_start, $limit_count);
		if (!empty($knowledge_list)) {
			$this->response(array("status"=>200, "result" => $knowledge_list), 200);
		}
		$this->response(array("status"=>200, "result" => null), 200);
	}

	public function format_confidence_post() {
		$knowledge_id = $this->post('knowledge_id');		
		if(!$knowledge_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$result = $this->knowledge_model->format_confidence($knowledge_id);
		$this->response(array("status"=>200, "result" => $result), 200);
		
	}

	
	
}