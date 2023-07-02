<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class discussion extends REST_Controller
{	
	function get_discussion_detail_post()
	{
		if(!$this->post('users_id') or !$this->post('questions_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$questions_id	= $this->post('questions_id');
		
		$this->load->model("questions_model");
		$this->load->model("answers_model");

		$question = $this->questions_model->get_question_by_id($questions_id);
		if (!$question || count($question) <= 0)
			$this->response(array("status"=>400, "message"=>"No info for that question id"), 200);

		$question = $question[0];

		$answers = $this->answers_model->get_answers_by_users_id($users_id, $questions_id);

		$this->response(array("status"=>200, array("question"=>$question, 
			"answers"=>$answers)), 200);
	}

	function get_questions_post()
	{
		if(!$this->post('users_id') or !$this->post('type'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id = $this->post('users_id');
		$type = $this->post('type');

        $query = $this->post('query');
        $from = $this->post('from');
        $count = $this->post('count');
        if (!$from)
        	$from = 0;
        if (!$count)
        	$count = 10;

		$this->load->model("questions_model");
		$this->load->model("answers_model");

		$order = null;
		$order_type = null;
		$mine_id = null;
		$is_random = null;
		switch ($type) {
			case 'Newest':
				$order = "date";
				$order_type = "desc";
				break;
			case 'Featured':
			case 'Recommended':
				$is_random = true;
				break;
			case 'Mine':
				$mine_id = $users_id;
				$order = "date";
				$order_type = "desc";
				break;
			default:
				break;
		}

		$questions = $this->questions_model->get_questions_list($query, $order, $order_type, $count, $from, $mine_id, $is_random);
		if (!$questions || count($questions) <= 0)
			$this->response(array("status"=>200, "questions"=>[]), 200);

		foreach ($questions as $key => $question) {
			$answers = $this->answers_model->get_answers($question['questions_id']);
			$questions[$key]['answers'] = $answers;
		}

		$this->response(array("status"=>200, "questions"=>$questions), 200);
	}
	
	function post_answer_post()
	{
		if (!$this->post('users_id') or !$this->post('answers_text') or !$this->post('questions_id')) {
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		}

		$users_id = $this->post('users_id');
		$answers_text = $this->post('answers_text');
		$questions_id = $this->post('questions_id');

		$this->load->model("questions_model");
		$this->load->model("answers_model");
		$question = $this->questions_model->get_question_by_id($questions_id);
		if (!$question || count($question) <= 0)
			$this->response(array("status"=>400, "message"=>"No info for that question id"), 200);

		$question = $question[0];
		$answer_data = array(
			'answerer_id'=>$users_id, 
			'questions_id'=>$questions_id,
			'answer'=>$answers_text,
			'date'=>date('Y-m-d H:i:s'),
			'lang'=>$question['answer_lang'],
			'question_lang'=>$question['lang'],
			);
		$result = $this->answers_model->store_answers($answer_data);
		if (!$result) 
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$answer = $this->answers_model->get_answers_by_id($result);
		$answer = $answer[0];
		$this->response(array("status"=>200, "answer"=>$answer, "message"=>"Successfully posted"), 200);
	}

	function post_new_question_post()
	{
		if (!$this->post('users_id') or !$this->post('source_text') or !$this->post('source_language') or !$this->post('target_language'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/questions/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'question_image');
            
            if (!$this->question_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->question_image->data();
                $upload = TRUE;
            }
        }

        $image_path = "";
        if ($upload == TRUE)
            $image_path = $attache_photo["file_name"];

		$this->load->model("questions_model");
		$description = $this->post('description');
		if (!$description)
			$description = "";
		$result = $this->questions_model->store_question(array('lang'=>$this->post('source_language'),
			'answer_lang'=>$this->post('target_language'),
			'question'=>$this->post('source_text'),
			'description'=>$description,
			'date'=>date('Y-m-d H:i:s'),
			'images'=> $image_path,
			'asker_id'=>$this->post('users_id')));

		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		if ($this->post('answer_text')) {
			$data_for_answer['answerer_id'] = $this->post('users_id');
		    $data_for_answer['liked_count'] = 0;
		    $data_for_answer['is_correct_answer'] = 1;
		    $data_for_answer['answer'] = $this->post('answer_text');
		    $data_for_answer['date'] = date('Y-m-d H:i:s');
		    $data_for_answer['questions_id'] = $result;
		    $data_for_answer['lang'] = $this->post('target_language');
		    $data_for_answer['question_lang'] = $this->post('source_language');
		    $this->load->model("answers_model");
		    $this->answers_model->store_answers($data_for_answer);
		}
		$this->response(array("status"=>200, "message"=>"Successfully posted"), 200);		
	}

	function get_questions_list_get()
	{
		// if(!$this->get('token') or !$this->get('user_name'))
		//       {
		//           $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		//       }
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $asker_name_like = $this->get('asker_name_like');
        $question_like = $this->get('user_name_like');
        $description_like = $this->get('full_name_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$user_name = $this->post('user_name');
		
		$this->load->model("questions_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->questions_model->count_questions($asker_name_like, $question_like, $description_like));
		header('link: _');
		$questions = $this->questions_model->get_questions_smart_list($asker_name_like, $question_like, $description_like, $limit, $page * $limit, $sort, $order);
		$this->response($questions, 200);
	}

	function get_question_detail_post()
	{
		if (!$this->post('questions_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $questions_id = $this->post('questions_id');

        $this->load->model("questions_model");
		$question = $this->questions_model->get_question_by_id($questions_id);
		if (count($question) > 0)
		  	$question = $question[0];
		else
			$this->response(array("status"=>401, "message"=>"Invalid Question ID"), 200);

		$this->load->model("answers_model");
		$answers = $this->answers_model->get_answers($questions_id);
			$question['answers'] = $answers;
		$this->response(array("status"=>200, "question"=>$question, "message"=>"Successfully got"), 200);
	}

	function update_question_detail_post()
	{
		if (!$this->post('questions_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

		$data_for_question = [];
		$questions_id = $this->post('questions_id');
		if ($this->post('lang'))
			$data_for_question['lang'] = $this->post('lang');
		if ($this->post('answer_lang'))
			$data_for_question['answer_lang'] = $this->post('answer_lang');
		if ($this->post('question'))
			$data_for_question['question'] = $this->post('question');
		if ($this->post('description'))
			$data_for_question['description'] = $this->post('description');

		$upload = FALSE;

		if (isset($_FILES['image'])) {
			$config['upload_path'] = './upload/questions/images/';
			$config['overwrite'] = FALSE;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config, 'sentences_image');

			if (!$this->sentences_image->do_upload("image")) {
				$upload = FALSE;
			} else {
				$attache_photo = $this->sentences_image->data();
				$upload = TRUE;
			}
		}

		if ($upload == TRUE)
            $data_for_question['images'] = $attache_photo["file_name"];
		
		$data_for_question['date'] = date('Y-m-d H:i:s');
		$this->load->model("questions_model");
		$result = $this->questions_model->update_questions($questions_id, $data_for_question);
		$this->response(array("status"=>200, "message"=>"Successfully updated"), 200);
	}

	function upload_image_post()
	{
		if (!isset($_FILES['file']))
        {
            $this->response(array("status"=>400, "message"=>"No file attached"), 200);
        }
		$config['upload_path'] = './upload/questions/images';
		$config['overwrite'] = FALSE;
		$config['allowed_types'] = '*';
		$config['max_size'] = '50000';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config, 'question_image');
		if ($this->question_image->do_upload('file')) {
			$attached_photo = $this->question_image->data();
			$this->response(array("status"=>200, "file_name"=>$attached_photo["file_name"], "message"=>"No file attached"), 200);
		}
		$this->response(array("status"=>401, "message"=>"File Uploading Failed"), 200);
	}

	function delete_image_post()
	{
		if (!$this->post('image'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		unlink('./upload/questions/images/' . $this->post('image'));
		$this->response(array("status"=>200, "message"=>"Successfully deleted"), 200);
	}

	function update_answer_post()
	{
		if (!$this->post('answers_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$data_for_answer = [];
		$answers_id = $this->post('answers_id');
		if ($this->post('answer'))
			$data_for_answer['answer'] = $this->post('answer');
		if ($this->post('is_correct_answer'))
			$data_for_answer['is_correct_answer'] = $this->post('is_correct_answer');
		$this->load->model("answers_model");
		$result = $this->answers_model->update_answers($answers_id, $data_for_answer);
		$this->response(array("status"=>200, "message"=>"Successfully updated"), 200);
	}

	function delete_question_post()
	{
		if (!$this->post('questions_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $this->load->model("questions_model");
        $this->questions_model->delete_question($this->post('questions_id'));
        $this->response(array("status"=>200, "message"=>"Successfully deleted"), 200);
	}

	function delete_answer_post()
	{
		if (!$this->post('answers_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $this->load->model("answers_model");
        $this->answers_model->delete_answer($this->post('answers_id'));
        $this->response(array("status"=>200, "message"=>"Successfully deleted"), 200);
	}
}