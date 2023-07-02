<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class chapter extends REST_Controller
{
    public function __construct() {
		parent::__construct();
		$this->load->model('knowledge_model');
		$this->load->model('chapters_model');	
        $this->load->model('learninghistory_model');
        $this->load->model('sentences_model');
	}	
	function get_chapters_list_get()
	{
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $courses_id = $this->get('courses_id');
        $chapters_id_like = $this->get('chapters_id_like');
        $chapter_name_like = $this->get('chapter_name_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$this->load->model("chapters_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->chapters_model->count_chapters($courses_id, $chapters_id_like, $chapter_name_like));
		header('link: _');
		$chapters = $this->chapters_model->get_chapters($courses_id, $chapters_id_like, $chapter_name_like, $limit, $page * $limit, $sort, $order);
		$this->response($chapters, 200);
	}

	function delete_chapter_post()
	{
		if(!$this->post('chapters_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$chapters_id = $this->post('chapters_id');
		
		$this->load->model("chapters_model");
		if ($chapters_id > 0) {
			$this->chapters_model->delete_chapters($chapters_id);
			$this->response(array("status"=>200, "message"=>"Successfully deleted the chapter"), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid Chapter ID"), 200);
		}
	}

	function add_new_chapter_post()
	{
		if (!$this->post('name') && !$this->post('courses_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/chapters/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'chapter_image');
            
            if (!$this->chapter_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->chapter_image->data();
                $upload = TRUE;
            }
        }

        $image_path = "";
        if ($upload == TRUE)
            $image_path = $attache_photo["file_name"];

		$this->load->model("chapters_model");
		$result = $this->chapters_model->store_chapters(array('name'=>$this->post('name'),
			'date'=>date('Y-m-d H:i:s'),
			'image'=> $image_path,
			'courses_id'=>$this->post('courses_id')));

		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully added new chapter"), 200);
	}

	function update_chapter_post()
	{
		if (!$this->post('chapters_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $chapters_id = $this->post('chapters_id');

        $upload = FALSE;
        if (isset($_FILES['image'])) 
        {
            $config['upload_path']  = './upload/chapters/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';          
            $this->load->library('upload', $config, 'chapters_image');
            
            if (!$this->chapters_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_image = $this->chapters_image->data();
                $upload = TRUE;
            }
        }
        $data_for_chapters = [];

        if ($this->post('name'))
        	$data_for_chapters['name'] = $this->post('name');

        if ($upload == TRUE)
            $data_for_chapters['image'] = $attache_image["file_name"];
		
		$this->load->model("chapters_model");
		if ($this->chapters_model->update_chapters($chapters_id, $data_for_chapters) == TRUE){
			$this->response(array("status"=>200, "message"=>"Successfully updated"), 200);
		} else {
			$this->response(array("status"=>401, "message"=>"Failed to update the chapter"), 200);
		}
	}

    function get_chapter_detail_post() {
        
        $chapter_words_arr = array(); 
        $chapter_grammars_arr =  array(); 

        $chapters_id = $this->post('chapters_id');
        $users_id = $this->post('users_id');
        
        $sentences_list = $this->sentences_model->get_sentences($chapters_id);
        $chapter = $this->chapters_model->get_chapter_by_id($chapters_id);
        
        $chapter_words_arr =  explode(',', $chapter['words']); 
        $chapter_grammars_arr =  explode(',', $chapter['grammars']); 
        $chapter_words_arr = array_filter($chapter_words_arr);
        $chapter_grammars_arr = array_filter($chapter_grammars_arr);

        $learned_count = $this->knowledge_model->get_w_g_count_learned($users_id, $chapter_words_arr, $chapter_grammars_arr);

        $chapter_detail = array(
            "chapters_name" => $chapter['name'],
            "sentences_list" => $sentences_list,
            "total_word_cnt" =>count($chapter_words_arr),
            "total_grammar_cnt" =>count($chapter_grammars_arr),
            "learned_word_cnt" => $learned_count['word'],
            "learned_grammar_cnt" => $learned_count['grammar'],
        );
        $this->response(array("status"=>200, "chapter_detail"=>$chapter_detail) ,200);
    }

    function get_chapters_post() {
        $chapters_list = array();
        $chapters_result = array();
        $chapters_temp_list = array();

        $course_words_arr = array();
        $course_grammar_arr = array();
        $words_studied_arr = array();
        $grammars_studied_arr = array();

        $sentences_total_count = 0;
        $studied_sentences_count = 0;

        $users_id = $this->post('users_id');
        $courses_id = $this->post('courses_id');
        if(!$courses_id)
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $limit_start = $this->post('limit_start');
        $limit_count = $this->post('limit_count');
        $this->load->model("chapters_model");
        $this->load->model("courses_model");
        
        $course_info = $this->courses_model->get_course_by_id($courses_id);
        $chapters_total_count = $this->chapters_model->count_chapters($courses_id);
        $chapters_list = $this->chapters_model->get_chapters($courses_id, null, null, $limit_count, $limit_start, null, null);
        $chapters_all_list = $this->chapters_model->get_chapters($courses_id);
        
        foreach ($chapters_all_list as $key => $chapter) {
            $sentences_total_count += $chapter['sentences_count'];
                            
            $chapters_words_arr =  explode(',', $chapter['words']); 
            $chapters_grammars_arr =  explode(',', $chapter['grammars']); 
            
            $course_words_arr = array_merge($course_words_arr, $chapters_words_arr);
            $course_grammar_arr = array_merge($course_grammar_arr, $chapters_grammars_arr);

        }

        foreach ($chapters_list as $key => $chapter) {
            $sentences_total_count += $chapter['sentences_count'];
                            
            $chapters_words_arr =  explode(',', $chapter['words']); 
            $chapters_grammars_arr =  explode(',', $chapter['grammars']); 
            
            $review_status = $this->learninghistory_model->check_learning_chapter_history($users_id, $chapter['chapters_id'], true);
            if (!empty($review_status)) {
                $check = "check";
            }
            else {
                $check = "uncheck";
            }
            $chapters_temp_list = array(
                "chapters_id" => $chapter['chapters_id'],
                "chapters_name" =>$chapter['name'],
                "sentences_total_count" =>$chapter['sentences_count'],
                "words_total_count" =>count($chapters_words_arr),
                "grammar_total_count" =>count($chapters_grammars_arr),
                "status" => $check,
            );
            array_push($chapters_result, $chapters_temp_list);
        }
        $course_words_arr = array_unique($course_words_arr);
        $course_words_arr = array_filter($course_words_arr);

        $course_grammar_arr = array_unique($course_grammar_arr);
        $course_grammar_arr = array_filter($course_grammar_arr);

        $learning_history = $this->learninghistory_model->get_courses_learned($users_id);
        $studied_chapter_count = 0;
        if (!empty($learning_history)) {
            foreach ($learning_history as $key => $history) {
                if ($history['courses_id'] === $courses_id) {
                    $studied_chapter_count = $history['chaper_cnt'];
                    $studied_sentences_count = $history['sentences_cnt'];
                                
                    $words_studied_arr = explode(',', $history['words_cnt']);      
                    $grammars_studied_arr = explode(',', $history['grammars_cnt']);
                }
            }

            $words_studied_arr = array_unique($words_studied_arr);
            $words_studied_arr = array_filter($words_studied_arr);

            $grammars_studied_arr = array_unique($grammars_studied_arr);
            $grammars_studied_arr = array_filter($grammars_studied_arr);
        }
        else {
            $studied_sentences_count = 0;
        }
        $courses_info = array(
            "courses_name" => $course_info['name'],
            "courses_description"=> $course_info['courses_description'],
            "courses_image" => $course_info['image'],
            "words_total_count" => count($course_words_arr),
            "studied_word_count" => count($words_studied_arr),
            "grammar_total_count" => count($course_grammar_arr), 
            "studied_grammar_count" => count($grammars_studied_arr),
            "sentences_total_count" => $sentences_total_count,
            "studied_sentences_count" => $studied_sentences_count,
            "chapters_total_count" => $chapters_total_count,
            "studied_chapter_count" => $studied_chapter_count,
        );
        $this->response(array("status"=>200, "chapters"=> $chapters_result, "course_info"=>$courses_info), 200);
    }

    function get_next_chapter_post() {
        $users_id = $this->post('users_id');
        $courses_id = $this->post('courses_id');
        $chapters_list = $this->chapters_model->get_all_chapters_by_course_id($courses_id);
        $studied_chapters = $this->chapters_model->get_next_chapter($users_id, $courses_id);
        $latest_studied_chapter_id = 0;
        if (!empty($studied_chapters[0])) {
            for ($i=0; $i < count($studied_chapters); $i++) { 
                if ($studied_chapters[$i]['success_flag'] == 0) {
                    $latest_studied_chapter_id = $studied_chapters[$i]['chapters_id'];
                    break;
                }
                if ($i === count($studied_chapters) - 1) {
                    $latest_studied_chapter_id = $studied_chapters[$i]['chapters_id'];
                    break;
                }
                    
            }
        }
        $next_chapter_words = array();
        $next_chapter_grammars = array();
        foreach($chapters_list as $key => $chapter)
        {
            if ( $chapter['chapters_id'] === $latest_studied_chapter_id ){
                $next_chapter_id = $chapters_list[$key + 1]['chapters_id'];
                $next_chapter_sentences_count = $chapters_list[$key + 1]['sentences_count'];
                
                $next_chapter_words = explode(',', $chapters_list[$key + 1]['words']);
                $next_chapter_words = array_unique($next_chapter_words);
                $next_chapter_words = array_filter($next_chapter_words);
                
                $next_chapter_grammars = explode(',', $chapters_list[$key + 1]['grammars']);
                $next_chapter_grammars = array_unique($next_chapter_grammars);
                $next_chapter_grammars = array_filter($next_chapter_grammars);
                break;
            }
        }
        if (!isset($next_chapter_id)) {
            $next_chapter_id = $chapters_list[0]['chapters_id'];
            $next_chapter_sentences_count = $chapters_list[0]['sentences_count'];
                
            $next_chapter_words = explode(',', $chapters_list[0]['words']);
            $next_chapter_words = array_unique($next_chapter_words);
            $next_chapter_words = array_filter($next_chapter_words);
                
            $next_chapter_grammars = explode(',', $chapters_list[0]['grammars']);
            $next_chapter_grammars = array_unique($next_chapter_grammars);
            $next_chapter_grammars = array_filter($next_chapter_grammars);
        }
        $this->response(
            array(
                "status"=> 200,
                "next_chapter" => 
                    array(
                        "chapters_id"=>$next_chapter_id, 
                        "sentences_total_count"=>(int)$next_chapter_sentences_count,
                        "words_total_count"=> count($next_chapter_words), 
                        "grammar_total_count"=>count($next_chapter_grammars)
                 )
            )
        );
    }

    // function import_chapter_post() {
        
    //     $chapters_arr = array();
    //     $sentences_arr = array();
    //     $course_name = '';
        
    //     $xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/duolingo_j_e.xml', "SimpleXMLElement", LIBXML_NOCDATA);
    //     // $xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/duoling_k_e.xml', "SimpleXMLElement", LIBXML_NOCDATA);
    //     //$xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/contents_busuu.xml', "SimpleXMLElement", LIBXML_NOCDATA);
        
        
    //     $json = json_encode($xml);
    //     $data = json_decode($json,TRUE);
        
    //     $data = $data['Worksheet'];
    //     $row_count = count($data['Table']['Row']);
    //     $this->load->model("chapters_model");
    //     $this->load->model("sentences_model");
    //     $this->response(array("status"=>200, "data" => $data), 200);
    //     for ($i=1; $i < $row_count; $i++) { 
            
    //         if (!empty($data['Table']['Row'][$i]['Cell'][0])) {
    //             $flag = true;
    //             $course_name = $data['Table']['Row'][$i]['Cell'][0]['Data'];
    //         } 
    //         if (!empty($data['Table']['Row'][$i]['Cell'][1]['Data']) && !empty($data['Table']['Row'][$i]['Cell'][2]['Data'])) {
    //             // $zh_cn = $data['Table']['Row'][$i]['Cell'][1]['Data'];
    //             // $en = $data['Table']['Row'][$i]['Cell'][2]['Data'];
    //             $zh_cn = $data['Table']['Row'][$i]['Cell'][2]['Data'];
    //             $en = $data['Table']['Row'][$i]['Cell'][1]['Data'];
                
    //             if ($flag) {
    //                 $add_chapter_data = array(
    //                     'name'=>$course_name,
    //                     'date'=>date('Y-m-d H:i:s'),
    //                     'courses_id'=> 26,
    //                     'source_language' =>'ja',
    //                     'target_language' => 'en'
    //                     );
    //                 array_push($chapters_arr, $add_chapter_data);
        
    //                 //$new_insert_chapter_id = $this->chapters_model->store_chapters($add_chapter_data);
    //                 $add_sentence_data = array(
    //                     //'chapters_id' => $new_insert_chapter_id,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     's_language' => "en",
    //                     'd_language' => "ja",
    //                     'en'=> $zh_cn,
    //                     'ja' => $en
    //                 );
    //                 array_push($sentences_arr, $add_sentence_data);
        
    //                 //$new_insert_sentences_id = $this->sentences_model->store_sentences($add_sentence_data);
                    
    //             } else {
    //                 $add_sentence_data = array(
    //                     //'chapters_id' => $new_insert_chapter_id,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     's_language' => "en",
    //                     'd_language' => "ja",
    //                     'en'=> $zh_cn,
    //                     'ja' => $en
    //                 );
    //                 array_push($sentences_arr, $add_sentence_data);
    //                 //$new_insert_sentences_id = $this->sentences_model->store_sentences($add_sentence_data);
    //             }
    //             $flag = false;
            
    //         }
            
    //     }
    //     $this->response(array("status"=>200, "chapter" => $chapters_arr, "sentences" => $sentences_arr), 200);
    // }
    

    function import_chapter_post() {
       // Duolingo japanese course 
        $chapters_arr = array();
        $sentences_arr = array();
        $course_name = '';
        
        
        $xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/korean_english_bussu.xml', "SimpleXMLElement", LIBXML_NOCDATA);
        
        //$xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/duolingo_j_e.xml', "SimpleXMLElement", LIBXML_NOCDATA);
        // $xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/duoling_k_e.xml', "SimpleXMLElement", LIBXML_NOCDATA);
        //$xml = simplexml_load_file('http://192.168.0.40/olining_admin/upload/xml/contents_busuu.xml', "SimpleXMLElement", LIBXML_NOCDATA);
        
        
        $json = json_encode($xml);
        $data = json_decode($json,TRUE);
        
        $data = $data['Worksheet'];
        $row_count = count($data['Table']['Row']);
        $this->load->model("chapters_model");
        $this->load->model("sentences_model");
        // $this->response(array("status"=>200, "data" => $data), 200);
        for ($i=1; $i < $row_count; $i++) { 
            if (count($data['Table']['Row'][$i]['Cell']) == 1) {
                $flag = true;
                $course_name = $data['Table']['Row'][$i]['Cell']['Data'];
            } 
            if (!empty($data['Table']['Row'][$i]['Cell'][0]['Data']) && !empty($data['Table']['Row'][$i]['Cell'][1]['Data'])) {
                // $zh_cn = $data['Table']['Row'][$i]['Cell'][1]['Data'];
                // $en = $data['Table']['Row'][$i]['Cell'][2]['Data'];
                $zh_cn = $data['Table']['Row'][$i]['Cell'][0]['Data'];
                $en = $data['Table']['Row'][$i]['Cell'][1]['Data'];
                
                if ($flag) {
                    $add_chapter_data = array(
                        'name'=>$course_name,
                        'date'=>date('Y-m-d H:i:s'),
                        'courses_id'=> 27,
                        'source_language' =>'ko',
                        'target_language' => 'en'
                        );
                    array_push($chapters_arr, $add_chapter_data);
        
                    $new_insert_chapter_id = $this->chapters_model->store_chapters($add_chapter_data);
                    $add_sentence_data = array(
                        'chapters_id' => $new_insert_chapter_id,
                        'date' => date('Y-m-d H:i:s'),
                        's_language' => "en",
                        'd_language' => "ko",
                        'en'=> $zh_cn,
                        'ko' => $en
                    );
                    array_push($sentences_arr, $add_sentence_data);
        
                    $new_insert_sentences_id = $this->sentences_model->store_sentences($add_sentence_data);
                    
                } else {
                    $add_sentence_data = array(
                        'chapters_id' => $new_insert_chapter_id,
                        'date' => date('Y-m-d H:i:s'),
                        's_language' => "en",
                        'd_language' => "ko",
                        'en'=> $zh_cn,
                        'ko' => $en
                    );
                    array_push($sentences_arr, $add_sentence_data);
                    $new_insert_sentences_id = $this->sentences_model->store_sentences($add_sentence_data);
                }
                $flag = false;
            
            }
            
        }
        $this->response(array("status"=>200, "chapter" => $chapters_arr, "sentences" => $sentences_arr), 200);
    }
 
     
}