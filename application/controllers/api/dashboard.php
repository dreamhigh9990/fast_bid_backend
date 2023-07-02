<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class dashboard extends REST_Controller
{	
  	public function __construct() {
		parent::__construct();
		$this->load->model('dictionary_model'); 
		$this->load->model('grammartest_model');
		$this->load->model('knowledge_model');
		$this->load->model('learninghistory_model');
		$this->load->model('searchhistory_model');
		$this->load->model('questions_model');
		$this->load->model('answers_model');
		$this->load->model("courses_model");
        $this->load->model("chapters_model");
        $this->load->model("review_model");
		$this->load->model("users_model");
		$this->load->model("sentences_model");
		$this->load->model("videos_model");
	}
	
	public function get_dashboard_user_info_post() {
		$temp = array();
		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$w_g_counts = $this->knowledge_model->get_user_w_g_count($users_id);
		$total_words_count = $this->dictionary_model->get_words_count();
		$total_grammars_count = $this->grammartest_model->get_grammar_total_count();
		if (empty($w_g_counts[0])){
			$studied_words_count = 0;
			$stuied_gramars_count = 0;
		}
		else if (empty($w_g_counts[1])){
			$studied_words_count = 0;
			$stuied_gramars_count = 0;
		}
		else {
			$studied_words_count = $w_g_counts[1]['cnt'];
			$stuied_gramars_count = $w_g_counts[0]['cnt'];
		}
		$this->response(array("status" => 200, "total_words_count"=> $total_words_count,"total_grammars_count"=> $total_grammars_count,
		"studied_words_count"=> $studied_words_count,"studied_grammars_count"=> $stuied_gramars_count), 200);
	}

	public function get_learning_history_post() {
		$total_words_arr = array();
		$total_grammars_arr = array();
		$temp_words_arr = array();
		$temp_grammars_arr = array();	

		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$learning_history = $this->learninghistory_model->get_learning_history($users_id);
		$search_history_count = $this->searchhistory_model->get_total_search_count($users_id);
		$question_count_by_user = $this->questions_model->count_questions_by_user($users_id);
		$answer_count_by_user = $this->answers_model->count_answers_by_user($users_id);
		
		$general_chapter_count = 0;
		$general_sentences_count = 0;
		$smart_chapter_count = 0;
		$smart_sentences_count = 0;
		$smart_words_count = 0;
		$smart_grammars_count = 0;
		
		if (!empty($learning_history)) {
			for ($i=0; $i < count($learning_history); $i++) { 
				if($learning_history[$i]['smart_flag'] == 0) {
					$general_chapter_count = $learning_history[$i]['chaper_cnt'];
					$general_sentences_count = $learning_history[$i]['sentences_cnt'];
					
					$temp_words_arr =  explode(',', $learning_history[$i]['words_cnt']);
					$total_words_arr = array_merge($total_words_arr, $temp_words_arr);
					$temp_grammars_arr =  explode(',', $learning_history[$i]['grammars_cnt']);
					$total_grammars_arr = array_merge($total_grammars_arr, $temp_grammars_arr);
				}
				else {
					$smart_chapter_count = $learning_history[$i]['chaper_cnt'];
					$smart_sentences_count = $learning_history[$i]['sentences_cnt'];

					$smart_chapters_words_arr =  explode(',', $learning_history[$i]['words_cnt']);
					$total_words_arr = array_merge($total_words_arr, $smart_chapters_words_arr);
					$smart_chapters_words_arr = array_unique($smart_chapters_words_arr);
					$smart_chapters_words_arr = array_filter($smart_chapters_words_arr);
					$smart_words_count = count($smart_chapters_words_arr);
					
					
					$smart_chapters_grammars_arr =  explode(',', $learning_history[$i]['grammars_cnt']);
					$total_grammars_arr = array_merge($total_grammars_arr, $smart_chapters_grammars_arr);
					$smart_chapters_grammars_arr = array_unique($smart_chapters_grammars_arr);
					$smart_chapters_grammars_arr = array_filter($smart_chapters_grammars_arr);
					$smart_grammars_count = count($smart_chapters_grammars_arr);

				}
			}
		}

		$total_words_arr = array_unique($total_words_arr);
		$total_words_arr = array_filter($total_words_arr);
		$total_words_count = count($total_words_arr);

		$total_grammars_arr = array_unique($total_grammars_arr);
		$total_grammars_arr = array_filter($total_grammars_arr);
		$total_grammars_count = count($total_grammars_arr);
			
		$result = array(
			"studied_total_chpater_cnt"=> $general_chapter_count + $smart_chapter_count,
			"studied_smart_chapter_cnt"=>(int)$smart_chapter_count,
			"studied_total_sentences_cnt"=>$general_sentences_count + $smart_sentences_count,
			"studied_smart_sentences_cnt"=>(int)$smart_sentences_count,
			"studied_total_words_cnt"=>$total_words_count,
			"studied_smart_words_cnt"=>$smart_words_count,
			"studied_total_grammars_cnt"=>$total_grammars_count,
			"studied_smart_grammars_cnt"=>$smart_grammars_count,
			"search_total_count" => $search_history_count,
			"question_total_count" =>$question_count_by_user,
			"answer_total_count" =>$answer_count_by_user,
		);
		$this->response(array("status"=>200, "result" => $result), 200);
	}
	
	public function get_recent_search_history_post() {
		$users_id = $this->post('users_id');
		$limit_start = $this->post('limit_start');
		$limit_count = $this->post('limit_count');
		$recent_search_history = $this->searchhistory_model->get_recent_search_history($users_id, $limit_start, $limit_count);
		$this->response(array("status"=>200, "result"=>$recent_search_history), 200);

	}  


	public function get_recent_question_history_post() {
		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$limit_start = $this->post('limit_start');
		$limit_count = $this->post('limit_count');
		$recent_question_history = $this->questions_model->get_recent_questions_history($users_id, $limit_start, $limit_count);
		$this->response(array("status"=>200, "result"=>$recent_question_history), 200);

	}

	public function get_recent_answers_history_post() {
		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$limit_start = $this->post('limit_start');
		$limit_count = $this->post('limit_count');
		$recent_answers_history = $this->answers_model->get_recent_answers_history($users_id, $limit_start, $limit_count);
		$this->response(array("status"=>200, "result"=>$recent_answers_history), 200);

	}


	/*  Get Smart Course List Info*/

    function get_dashboard_smart_chatper_id($users_id){
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

	Public function get_smart_courses_info_post() {

		$total_words_arr = array();
		$total_grammars_arr = array();

		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		$learning_history = $this->learninghistory_model->get_learning_history($users_id);
		
		$smart_chapter_count = 0;
		$smart_sentences_count = 0;
		$smart_words_count = 0;
		$smart_grammars_count = 0;
		
		if (!empty($learning_history)) {
			for ($i=0; $i < count($learning_history); $i++) { 
				if($learning_history[$i]['smart_flag'] == 1) {
					$smart_chapter_count = $learning_history[$i]['chaper_cnt'];
					$smart_sentences_count = $learning_history[$i]['sentences_cnt'];

					$smart_chapters_words_arr =  explode(',', $learning_history[$i]['words_cnt']);
					$smart_chapters_words_arr = array_unique($smart_chapters_words_arr);
					$smart_chapters_words_arr = array_filter($smart_chapters_words_arr);
					$smart_words_count = count($smart_chapters_words_arr);
					
					
					$smart_chapters_grammars_arr =  explode(',', $learning_history[$i]['grammars_cnt']);
					$smart_chapters_grammars_arr = array_unique($smart_chapters_grammars_arr);
					$smart_chapters_grammars_arr = array_filter($smart_chapters_grammars_arr);
					$smart_grammars_count = count($smart_chapters_grammars_arr);
				}
			}
		}

		$smart_courses_info = array(
			"smart_chapters_count" =>(int)$smart_chapter_count,
			"smart_sentences_count" =>(int)$smart_sentences_count,
			"smart_words_count" =>$smart_words_count,
			"smart_grammars_count" =>$smart_grammars_count,
		);
		$smart_courses_chapters = $this->get_dashboard_smart_chatper_id($users_id);
		$smart_chapters = $this->chapters_model->get_chapters_by_chapters_id($smart_courses_chapters['chapters_id']);
		
		$new_words_count = 0;
		$new_grammars_count = 0;
		if (!empty($smart_chapters[0])) {
			$smart_words_arr =  explode(',', $smart_chapters[0]['words']);
			$smart_words_arr = array_filter($smart_words_arr); 
			foreach ($smart_words_arr as $key => $words_id) {
				$query_words_id[] = $words_id;
			}
            $new_words_count = $this->knowledge_model->get_new_words_count($users_id, $query_words_id, 'new');
			
			$smart_grammars_arr =  explode(',', $smart_chapters[0]['grammars']); 
			$smart_grammars_arr = array_filter($smart_grammars_arr);
			foreach ($smart_grammars_arr as $key => $grammars_id) {
				$query_grammars_id[] = $grammars_id;
			}
			$new_grammars_count = $this->knowledge_model->get_new_grammars_count($users_id, $query_grammars_id, 'new');
			
			$next_smart_courses_info = array(
				"chapter_name" => $smart_chapters[0]['chapters_name'],
				"chapters_id" => $smart_chapters[0]['chapters_id'],
				"courses_name" => $smart_chapters[0]['courses_name'],
				"smart_sentences_count" => (int)$smart_chapters[0]['sentences_count'],
				"smart_words_count" =>count($smart_words_arr),
				"smart_grammars_count" =>count($smart_grammars_arr),
				"new_words_count" => $new_words_count,
				"new_grammars_count" => $new_grammars_count,
			);
		} else {
			$next_smart_courses_info = null;
		}
		

		$this->response(array("status"=>200, "smart_course_info"=>$smart_courses_info, "next_smart_course_info"=>$next_smart_courses_info), 200);
	}

	Public function get_general_courses_info_post() {
		
		$chapters_info_list = array();
		$result_arr = array();

		$sentences_total_count = 0;

		$users_id = $this->post('users_id');
		if(!$users_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }	
		
		$courses_history_data = $this->learninghistory_model->get_courses_learned($users_id);
		//$this->response(array(0=>$courses_history_data));
		if (!empty($courses_history_data)) {
			foreach ($courses_history_data as $key => $course_history_data) {
				$words_arr = array();
				$words_temp_arr = array();
				$grammars_arr = array();
				$grammars_temp_arr = array();
				$studied_words_arr = array();
				$studied_grammars_arr = array();
				
				$courses_id = $course_history_data['courses_id'];
				$courses_info = $this->courses_model->get_courses($courses_id);
				$chapters_info_list = $this->chapters_model->get_chapters($courses_id);
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

				$studied_chapters_count = $course_history_data['chaper_cnt'];
				$studied_sentences_count = $course_history_data['sentences_cnt'];

				$studied_words_arr =  explode(',', $course_history_data['words_cnt']);
				$studied_words_arr = array_unique($studied_words_arr);
				$studied_words_arr = array_filter($studied_words_arr);
				$studied_words_count = count($studied_words_arr);
				
				$studied_grammars_arr =  explode(',', $course_history_data['grammars_cnt']);
				$studied_grammars_arr = array_unique($studied_grammars_arr);
				$studied_grammars_arr = array_filter($studied_grammars_arr);
				$studied_grammars_count = count($studied_grammars_arr);
				
				$general_courses_info = array(
					"courses_info" => $courses_info,
					"chapter_total_count" => count($chapters_info_list),
					"sentences_total_count" => $sentences_total_count,
					"words_total_count" => count($words_arr),
					"grammar_total_count" => count($grammars_arr), 
					"studied_chapters_count" => (int)$studied_chapters_count,
					"studied_sentences_count" => (int)$studied_sentences_count,
					"studied_word_count" => $studied_words_count,
					"studied_grammar_count" => $studied_grammars_count,
				);
				
				array_push($result_arr, $general_courses_info);
			}
			$this->response(array("status"=>200, "result"=>$result_arr),200);
		}
		else {
			$this->response(array("status"=>200, "result"=>null),200);
		}

	}

	function get_user_statistic_data_post() {
		
		$date_list = array();
		$date_data = array();
		$users_count_list = array();
		$users_active_count_list = array();
		$users_smart_count_list = array();

		$users_count_per_day = $this->users_model->get_user_count_per_day();
		$user_acive_count_per_day = $this->learninghistory_model->get_user_acive_count_per_day();
		$user_smart_count_per_day = $this->learninghistory_model->get_user_smart_course_count_per_day();

		array_push($date_list, min(array_column( $users_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $users_count_per_day, 'date' )));
		array_push($date_list, min(array_column( $user_acive_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $user_acive_count_per_day, 'date' )));
		array_push($date_list, min(array_column( $user_smart_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $user_smart_count_per_day, 'date' )));
		

		$start_date = min($date_list);
		$last_date = max($date_list);
		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}
		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($users_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['u_cnt'];
				}
			}
			array_push($users_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($user_acive_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['u_cnt'];
				} 
			}
			array_push($users_active_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($user_smart_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['u_cnt'];
				}
			}
			array_push($users_smart_count_list, $default_value);
		}

		$this->response(
			array(
				"status"=>200, 
				"users_count_list"=>$users_count_list, 
				"user_acive_count_list"=>$users_active_count_list,
				"user_smart_count_list"=>$users_smart_count_list,
				"date_data" => $date_data,
				), 
			200
		);
	} 

	function get_course_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$courses_count_list = array();
		$courses_activity_count_list = array();
		
		$course_count_per_day = $this->courses_model->get_course_count_per_day(); 
		
		array_push($date_list, min(array_column( $course_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $course_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($course_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($courses_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($course_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($courses_activity_count_list, $default_value);
		}

		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"courses_count_list" => $courses_count_list,
				"courses_activity_count_list" => $courses_activity_count_list,
			),
			200
		);
	}

	function get_chapter_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$chapters_count_list = array();
		$chapters_activity_count_list = array();

		$chapter_count_per_day = $this->chapters_model->get_chapter_count_per_day(); 
		
		array_push($date_list, min(array_column( $chapter_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $chapter_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($chapter_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($chapters_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($chapter_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($chapters_activity_count_list, $default_value);
		}


		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"chapters_count_list" => $chapters_count_list,
				"chapters_activity_count_list" => $chapters_activity_count_list,
			),
			200
		);
	}


	function get_sentences_statistic_data_post() {
		
		$date_list = array();
		$date_data = array();
		$sentences_count_list = array();
		$sentences_activity_count_list = array();
		
		$sentences_count_per_day = $this->sentences_model->get_sentence_count_per_day(); 

		array_push($date_list, min(array_column( $sentences_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $sentences_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($sentences_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($sentences_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($sentences_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($sentences_activity_count_list, $default_value);
		}


		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"sentences_count_list" => $sentences_count_list,
				"sentences_activity_count_list" => $sentences_activity_count_list,
			),
			200
		);
		
	}

	function get_video_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$videos_count_list = array();
		$videos_activity_count_list = array();

		$video_count_per_day = $this->videos_model->get_video_count_per_day(); 
		array_push($date_list, min(array_column( $video_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $video_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($video_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($videos_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($video_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($videos_activity_count_list, $default_value);
		}


		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"videos_count_list" => $videos_count_list,
				"videos_activity_count_list" => $videos_activity_count_list,
			),
			200
		);
	}

	function get_question_statistic_data_post() {

		$date_list = array();
		$date_data = array();
		$questions_count_list = array();
		$questions_activity_count_list = array();

		$question_count_per_day = $this->questions_model->get_question_count_per_day(); 

		array_push($date_list, min(array_column( $question_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $question_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($question_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($questions_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($question_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($questions_activity_count_list, $default_value);
		}


		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"questions_count_list" => $questions_count_list,
				"questions_activity_count_list" => $questions_activity_count_list,
			),
			200
		);
	}

	function get_answer_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$anwsers_count_list = array();
		$answers_activity_count_list = array();

		$answer_count_per_day = $this->answers_model->get_answer_count_per_day(); 
		
		array_push($date_list, min(array_column( $answer_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $answer_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		$default_value = 0;
		foreach ($date_data as $key => $data) {
			foreach ($answer_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value += (int)$value['co_cnt'];
				}
			}
			array_push($anwsers_count_list, $default_value);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($answer_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				} 
			}
			array_push($answers_activity_count_list, $default_value);
		}
		
		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"anwsers_count_list" => $anwsers_count_list,
				"answers_activity_count_list" => $answers_activity_count_list,
			),
			200
		);
	}

	function courses_studied_history_get($limit_start=null, $limit_end=null, $sort=null, $order=null)
	{
		$page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $sort = $this->get('_sort');
		$order = $this->get('_order');
		
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->courses_model->count_courses());
		header('link: _');
		$course_studied_history = $this->courses_model->get_course_studied_history($limit, $page * $limit, $sort, $order);
		
		$this->response($course_studied_history['day'], 200);
	}

	function get_search_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$search_count_list = array();

		$search_count_per_day = $this->searchhistory_model->get_search_total_count_per_day(); 
		
		array_push($date_list, min(array_column( $search_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $search_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($search_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				}
			}
			array_push($search_count_list, $default_value);
		}

		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"search_total_count_list" => $search_count_list,
			),
			200
		);
	}

	function get_search_user_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$search_count_list = array();

		$search_count_per_day = $this->searchhistory_model->get_search_user_count_per_day(); 
		
		array_push($date_list, min(array_column( $search_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $search_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($search_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				}
			}
			array_push($search_count_list, $default_value);
		}

		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"search_user_count_list" => $search_count_list,
			),
			200
		);
	}

	function get_favourite_user_statistic_data_post() {
		$date_list = array();
		$date_data = array();
		$favourite_count_list = array();

		$favourite_count_per_day = $this->review_model->get_favourite_count_per_day(); 
		
		array_push($date_list, min(array_column( $favourite_count_per_day, 'date' )));
		array_push($date_list, max(array_column( $favourite_count_per_day, 'date' )));
		$start_date = min($date_list);
		$last_date = max($date_list);

		while ($start_date <= $last_date) {
			array_push($date_data, $start_date);
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}

		
		foreach ($date_data as $key => $data) {
			$default_value = 0;
			foreach ($favourite_count_per_day as $key => $value) {
				if ($value['date'] === $data) {
					$default_value = (int)$value['co_cnt'];
				}
			}
			array_push($favourite_count_list, $default_value);
		}

		$this->response(
			array(
				"status"=>200,
				"date_data" => $date_data,
				"search_favourite_count_list" => $favourite_count_list,
			),
			200
		);
	}


	function get_knowledge_statistic_data_post() {
		$chartData = array();
		$response_data = array();
		$w_data = array();
		$g_data = array();
		$date_data = array();
		
		$current_date = date("Y-m-d");
		$users_id = $this->post('users_id');
		$filter = $this->post('filter');
		
		if ($filter == 7) {
			$week_ago = date('Y-m-d', strtotime("-1 week"));
		} elseif ($filter == 15) {
			$week_ago = date('Y-m-d', strtotime("-2 week"));
		} elseif ($filter == 30) {
			$week_ago = date('Y-m-d', strtotime("-4 week"));
		} 
		
		$response_data = $this->knowledge_model->get_knowledge_statistic_data($users_id, $week_ago);
		
		$last_date = $current_date;
		$start_date = $week_ago;			
		
		while ($start_date <= $last_date) {
			array_push($w_data, array(0=>$start_date, 1=>0));
			array_push($g_data, array(0=>$start_date, 1=>0));
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}
		for ($i=0; $i < count($w_data); $i++) { 
			for ($j=0; $j < count($response_data['word_data']); $j++) {
				if ($w_data[$i][0] === $response_data['word_data'][$j]['date']) {
					$w_data[$i][1] = (int)$response_data['word_data'][$j]['cnt'];
				}	
			}
		}

		for ($i=0; $i < count($w_data); $i++) { 
			for ($j=0; $j < count($response_data['grammar_data']); $j++) {
				if ($g_data[$i][0] === $response_data['grammar_data'][$j]['date']) {
					$g_data[$i][1] = (int)$response_data['grammar_data'][$j]['cnt'];
				}	
			}
		}
				
		array_push($chartData, array("key"=>"Word", "bar"=> true, "values"=>$w_data));
		array_push($chartData, array("key"=>"Grammar", "bar"=> false, "values"=>$g_data));		
		$this->response(array("status"=>200, "chartData"=>$chartData), 200);
	}
	function get_user_level_static_data_post(){
		
		$chartData = array();
		$response_data = array();
		$date_data = array();
		$w_data = array();
		$g_data = array();
		$date_data = array();
		$ret = array();
		$level = 0;

		$users_id = $this->post('users_id');
		
		$current_date = date("Y-m-d");
		$total_word_cnt = $this->dictionary_model->get_words_count()/100;
		$total_grammar_cnt = $this->grammartest_model->get_grammar_total_count()/100;
		$week_ago = date('Y-m-d', strtotime("-1 week"));
		$response_data = $this->knowledge_model->get_knowledge_statistic_data($users_id, $week_ago);
		$word_date_data = $response_data['word_data'];
		$grammar_date_data = $response_data['word_data']; 
		
		$word_min_date = array_reduce($word_date_data, function($a, $b){
			return $a['date'] < $b['date'] ? $a : $b;
		}, array_shift($response_data['word_data']));

		$grammar_min_date = array_reduce($grammar_date_data, function($a, $b){
			return $a['date'] < $b['date'] ? $a : $b;
		}, array_shift($grammar_date_data));
		
		$last_date = $current_date;
		$start_date = min($grammar_min_date['date'], $word_min_date['date']);
		
		while ($start_date < $last_date) {
			array_push($date_data, array(0=>$start_date));
			$your_date = strtotime("1 day", strtotime($start_date));
			$start_date = date("Y-m-d", $your_date);
		}
		for ($i=0; $i < count($date_data); $i++) { 
			for ($j=0; $j < count($response_data['word_data']); $j++) {
				if ($date_data[$i][0] === $response_data['word_data'][$j]['date']) {
					$flag = (float)$response_data['word_data'][$j]['cnt']/$total_word_cnt;
					break;	
				} else {
					$flag=0;
				}
			}
			array_push($w_data, $flag);
		}

		for ($i=0; $i < count($date_data); $i++) { 
			for ($j=0; $j < count($response_data['grammar_data']); $j++) {
				if ($date_data[$i][0] === $response_data['grammar_data'][$j]['date']) {
					$flag = (float)$response_data['grammar_data'][$j]['cnt']/$total_grammar_cnt;
					break;
				} else {
					$flag=0;
				}
			}
			array_push($g_data, $flag);
		}

		for ($i=0; $i < count($date_data); $i++) { 
			$level += ($w_data[$i] + $g_data[$i]) / 2;
			if ($date_data[$i][0] >= $week_ago) {
				array_push($ret, array("label"=>$date_data[$i][0], "value"=> $level, "color"=>'#229de3'));
			}
		}

		$this->response(
			array("status"=>200, 
					"ret"=>$ret
			),
			200
		);
	}

}

//{label: '01/01/2018', value: 30, color: '#229de3'},
	