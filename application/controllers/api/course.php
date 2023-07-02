<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class course extends REST_Controller
{	
    public function __construct() {
		parent::__construct();
		$this->load->model('knowledge_model');
		$this->load->model("courses_model");
        $this->load->model("chapters_model");
        $this->load->model("review_model");
        $this->load->model('learninghistory_model');
	}

	function get_courses_list_get()
	{
		// if(!$this->get('token') or !$this->get('user_name'))
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $courses_id_like = $this->get('courses_id_like');
        $course_name_like = $this->get('course_name_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$this->load->model("courses_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->courses_model->count_courses($courses_id_like, $course_name_like));
		header('link: _');
		$users = $this->courses_model->get_courses($courses_id_like, $course_name_like, $limit, $page * $limit, $sort, $order);
		$this->response($users, 200);
	}

	function delete_course_post()
	{
		if(!$this->post('courses_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$courses_id = $this->post('courses_id');
		
		$this->load->model("courses_model");
		if ($courses_id > 0) {
			$this->courses_model->delete_courses($courses_id);
			$this->response(array("status"=>200, "message"=>"Successfully deleted the course"), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid Courses ID"), 200);
		}
	}

	function add_new_course_post()
	{
		if (!$this->post('name') && !$this->post('author_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/courses/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'course_image');
            
            if (!$this->course_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->course_image->data();
                $upload = TRUE;
            }
        }

        $image_path = "";
        if ($upload == TRUE)
            $image_path = $attache_photo["file_name"];

		$this->load->model("courses_model");
		$result = $this->courses_model->store_courses(array('name'=>$this->post('name'),
			'date'=>date('Y-m-d H:i:s'),
			'image'=> $image_path,
			'author_id'=>$this->post('author_id')));

		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully added new course"), 200);
	}

	function update_course_post()
	{
		if (!$this->post('courses_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $courses_id = $this->post('courses_id');

        $upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/courses/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';          
            $this->load->library('upload', $config, 'courses_image');
            
            if (!$this->courses_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_image = $this->courses_image->data();
                $upload = TRUE;
            }
        }
        $data_for_courses = [];

        if ($this->post('name'))
        	$data_for_courses['name'] = $this->post('name');

        if ($upload == TRUE)
            $data_for_courses['image'] = $attache_image["file_name"];
		
		$this->load->model("courses_model");
		if ($this->courses_model->update_courses($courses_id, $data_for_courses) == TRUE){
			$this->response(array("status"=>200, "message"=>"Successfully updated"), 200);
		} else {
			$this->response(array("status"=>401, "message"=>"Failed to update the course"), 200);
		}
	}


    /*  Get Geneal Course List Info*/

    function get_course_info_list_post() {
        
        $courses_info_list = array();
        $courses_info_list_temp = array();
        $chapters_info_list = array();
        $words_temp_arr = array();
        $grammars_temp_arr = array();
        $learning_history = array();
        
        $users_id = $this->post('users_id');
        if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $limit_start = $this->post('limit_count');
        $limit_end = $this->post('limit_start');

        $courses_list = $this->courses_model->get_courses_info($users_id, $limit_start, $limit_end);

        foreach ($courses_list as $course_key => $course) {
            $sentences_total_count = 0;
            $words_total_count = 0;
            $grammar_total_count = 0;
            $studied_sentences_count = 0;        
            $words_arr = array();
            $grammars_arr = array();
            $words_studied_arr = array();
            $grammars_studied_arr = array();
            if (!empty($course)) {
                if ($course['courses_id'] !== "7") {
                    $chapters_info_list = $this->chapters_model->get_chapters($course['courses_id']);
                    foreach ($chapters_info_list as $key => $chapter) {
                        if (!empty($chapter)) {
                            $sentences_total_count += $chapter['sentences_count'];
                            $words_temp_arr = explode(',', $chapter['words']);
                            $words_arr = array_merge($words_arr, $words_temp_arr);
                            
                            $grammars_temp_arr = explode(',', $chapter['grammars']);
                            $grammars_arr = array_merge($grammars_arr, $grammars_temp_arr);
                            
                        }
                    }
                    $words_arr = array_unique($words_arr);
                    $words_arr = array_filter($words_arr);

                    $grammars_arr = array_unique($grammars_arr);
                    $grammars_arr = array_filter($grammars_arr);

                    
                    $learning_history = $this->learninghistory_model->get_courses_learned($users_id);
                    if (!empty($learning_history)) {
                        foreach ($learning_history as $key => $history) {
                            if ($history['courses_id'] === $course['courses_id']) {
                                $studied_sentences_count = $history['sentences_cnt'];
                                $words_studied_arr = explode(',', $history['words_cnt']);  
                                $grammars_studied_arr = explode(',', $history['grammars_cnt']);
                            }
                        }
                        
                        $words_studied_arr = array_unique($words_studied_arr);
                        $words_studied_arr = array_filter($words_studied_arr);

                        $grammars_studied_arr = array_unique($grammars_studied_arr);
                        $grammars_studied_arr = array_filter($grammars_studied_arr);
                    } else {
                        $studied_sentences_count = 0;
                    }

                }  
                $courses_info_list_temp = array(
                    "courses_id" => $course['courses_id'],
                    "courses_name" => $course['name'],
                    "courses_description"=> $course['courses_description'],
                    "courses_image" => $course['image'],
                    "chapters_total_count" => count($chapters_info_list),
                    "sentences_total_count" => $sentences_total_count,
                    "words_total_count" => count($words_arr),
                    "grammar_total_count" => count($grammars_arr), 
                    "studied_chapter_count" => count($learning_history),
                    "studied_sentences_count" => $studied_sentences_count,
                    "studied_word_count" => count($words_studied_arr),
                    "studied_grammar_count" => count($grammars_studied_arr),
                );
                array_push($courses_info_list, $courses_info_list_temp);
            }
            
        }
        
        $this->response(array("result"=>$courses_info_list));
    }

    /*  Get Smart Course List Info*/

    function get_recommend_smart_chatper_id($users_id){
        $words = '';
        $grammars = '';
        
        // $users_id = $this->post('users_id');
        
        $w_results = $this->knowledge_model->get_knowledges_words($users_id);
        foreach ($w_results as $key => $w_result) {
			if (strlen($words) == 0) {
				$words = $w_result['w_g_id'];
			} else {
				$words = $words.' '.$w_result['w_g_id'];
			}
		
		}
		$g_results = $this->knowledge_model->get_knowledges_grammars($users_id);
        foreach ($g_results as $key => $g_result) {
			
			if (strlen($grammars) == 0) {
				$grammars = $g_result['w_g_id'];
			} else {
				$grammars = $grammars.' '.$g_result['w_g_id'];
			}
			
		}
        $smart_chapter_id = $this->knowledge_model->get_smart_chapter_id($words, $grammars);
        return $smart_chapter_id[0];
        // $this->response(array("status"=>200, "smart_id"=>$smart_chapter_id), 400);
    }

    function get_smart_course_info_list_post() {

        $users_id = $this->post('users_id');
        if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        
        $studied_words_count = 0;
        $studied_grammar_count = 0;
        
        
        $smart_courses_chapters = $this->get_recommend_smart_chatper_id($users_id);
        
        $courses_info = $this->courses_model->get_courses($smart_courses_chapters['courses_id']);
        $smart_chapters = $this->chapters_model->get_chapters_by_chapters_id($smart_courses_chapters['chapters_id']);
        
        if (!empty($smart_chapters[0]) && !empty($courses_info[0])) {
            $smart_words_arr =  explode(',', $smart_chapters[0]['words']); 
            $smart_words_arr = array_filter($smart_words_arr);
            $smart_grammars_arr =  explode(',', $smart_chapters[0]['grammars']); 
            $smart_grammars_arr = array_filter($smart_grammars_arr);

            foreach ($smart_words_arr as $key => $words_id) {
				$query_words_id[] = $words_id;
			}

            $studied_words_count = $this->knowledge_model->get_new_words_count($users_id, $query_words_id, 'studied');

            foreach ($smart_grammars_arr as $key => $grammars_id) {
				$query_grammars_id[] = $grammars_id;
			}

            $studied_grammar_count = $this->knowledge_model->get_new_grammars_count($users_id, $query_grammars_id, 'studied');

            $smart_courses_info = array(
                "courses_id" => $courses_info[0]['courses_id'],
                "courses_name" => $courses_info[0]['name'],
                "courses_description"=> $courses_info[0]['courses_description'],
                "courses_image" => $courses_info[0]['image'],
                "chapters_name" =>$smart_chapters[0]['chapters_name'],
                "chapters_id" =>$smart_chapters[0]['chapters_id'],
                "sentences_total_count" => $smart_chapters[0]['sentences_count'],
                "words_total_count" => count($smart_words_arr),
                "grammar_total_count" => count($smart_grammars_arr), 
                "studied_word_count" => $studied_words_count,
                "studied_grammar_count" => $studied_grammar_count,
            );
            $this->response(array("status"=>200, "smart_result"=>$smart_courses_info), 200);
        }
        
    }
}