<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class like extends REST_Controller
{	
	function like_answer_post()
	{
		if(!$this->post('users_id') or !$this->post('answers_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$answers_id		= $this->post('answers_id');

		$this->load->model("like_model");
		$result = $this->like_model->store_liked(array(
			'users_id'=>$users_id,
			'answers_id'=>$answers_id,
			'date'=>date('Y-m-d H:i:s'),
			));

		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->load->model("answers_model");
		if (!$this->answers_model->increase_liked_count($answers_id))
			$this->response(array("status"=>401, "message"=>"Errors occured while increasing answers liked count"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully liked answer"), 200);
	}

	function unlike_answer_post()
	{
		if(!$this->post('users_id') or !$this->post('answers_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$answers_id		= $this->post('answers_id');

		$this->load->model("like_model");
		$result = $this->like_model->delete_liked(array(
			'users_id'=>$users_id,
			'answers_id'=>$answers_id,
			));

		$this->load->model("answers_model");
		if (!$this->answers_model->decrease_liked_count($answers_id))
			$this->response(array("status"=>401, "message"=>"Errors occured while decreasing answers liked count"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully unliked answer"), 200);
	}

	function get_review_sentences_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("review_model");
		$this->load->model("users_model");
		$user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>401, "message"=>"Invalid users id"), 200);

		$sentences = $this->review_model->get_review_sentences($users_id, $user['source_language'], $user['target_language'], $from_date, $to_date);

		$merged_sentences = array_merge($sentences['sentences'], $sentences['discussion'], $sentences['videos']);
		$sorted_sentences = $this->array_sort($merged_sentences, "reviewed_date");

		$reviewed_count = $this->review_model->get_reviewed_count($users_id, $from_date, $to_date);

		$this->response(array("status"=>200, "reviewed_count"=>$reviewed_count, "reviewed_sentences"=>array_values($sorted_sentences)), 200);
	}

	function array_sort($array, $on, $order=SORT_ASC)
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

	function get_review_score_history_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("review_model");
		$history = $this->review_model->get_review_score_history($users_id, $from_date, $to_date);
		$this->response(array("status"=>200, "history"=>$history), 200);
	}

	function set_review_score_post()
	{
		if(!$this->post('users_id') or !$this->post('reviewed_date') or !$this->post('reviewed_sentences_count'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $reviewed_date	= $this->post('reviewed_date');
		$reviewed_sentences_count	= $this->post('reviewed_sentences_count');
		$reviewed_words_count	= $this->post('reviewed_words_count');
		$reviewed_accuracy = $this->post('reviewed_accuracy');
		$success	= $this->post('success') ? 1 : 0;
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("review_model");
		$result = $this->review_model->set_review_score($users_id, $reviewed_date, $reviewed_sentences_count, $reviewed_words_count, $reviewed_accuracy, $success, $from_date, $to_date);
		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully set review score"), 200);

	}
}