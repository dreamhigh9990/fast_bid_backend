<?php //header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST');
// header('Access-Control-Allow-Headers: X-Custom-Header');
// header('Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE');
// header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
// header('Access-Control-Allow-Origin: *');

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
require 'vendor/autoload.php';

define('DEFAULT_SEARCH_COUNT', 10);

class search extends REST_Controller
{	
    function get_trend_query_list_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id = $this->post('users_id');
		
		$this->response(array("status"=>200, "queries"=>
			array("That's a nice idea", 
			    "I should write a daily report until 5 PM", 
			    "Would you like some coffee", 
			    "Hurry, we are so late", 
			    "Why don't we turn left", 
			    "I am always happy to see you smiling on me. I think I like you now.", )), 200);
	}

	function search_all_post()
	{
		if(!$this->post('users_id') or !$this->post('query'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $users_id = $this->post('users_id');
        $query = $this->post('query');
        $from = $this->post('from');
        $count = $this->post('count');

        if (!$from)
            $from = 0;
        if (!$count)
            $count = DEFAULT_SEARCH_COUNT;

        $this->load->model("users_model");
        $this->load->model("sentences_model");
        $this->load->model("questions_model");
        $this->load->model("vsentences_model");
        
        $user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>401, "message"=>"Invalid users id"), 200);

        $result = $this->search_all_es($users_id, $query, $from, $count, $user['source_language'], $user['target_language']);

        $this->response(array("status"=>200, "result"=>$result), 200);
	}

    function search_all_es($users_id, $query, $from, $count, $s_language, $d_language)
    {
        $this->load->model("sentences_model");
        $this->load->model("questions_model");
        $this->load->model("vsentences_model");
        $result = array();
        $search_result = $this->sentences_model->search_all_es($query, $from, $count);
        $search_array = $search_result['hits']['hits'];
        foreach ($search_array as $key => $sentence_info) {
            switch ($sentence_info['_index']) {
                case 'sentences':
                    $sentence_key = $sentence_info['_source'];
                    $sentence = $this->sentences_model->get_sentences_by_users_id_and_id($users_id, $sentence_key['sentences_id']);
                    $result_sentence = array();
                    $result_sentence['type'] = 'sentences';
                    $result_sentence['sentences_id'] = $sentence['sentences_id'];
                    $result_sentence['source_text'] = $sentence[$sentence['s_language']];
                    $result_sentence['target_text'] = $sentence[$sentence['d_language']];
                    $result_sentence['reviewed_count'] = $sentence['reviewed_count'];
                    $result_sentence['viewed_count'] = $sentence['viewed_count'];
                    $result_sentence['reviewed_by_user'] = $sentence['reviewed_by_user'];

                    $result_sentence['chapters_id'] = 0;
                    $result_sentence['books_id'] = 0;
                    $result_sentence['courses_id'] = 0;
                    if ($sentence['chapters_id'] > 0) {
                        $this->load->model("chapters_model");
                        $result_sentence['chapters_id'] = $sentence['chapters_id'];
                        $chapter = $this->chapters_model->get_chapter_by_id($sentence['chapters_id']);
                        if ($chapter) {
                            $result_sentence['chapters_name'] = $chapter['name'];

                            if ($chapter['books_id'] > 0) {
                                $this->load->model("books_model");
                                $result_sentence['books_id'] = $chapter['books_id'];
                                $book = $this->books_model->get_book_by_id($chapter['books_id']);
                                if ($book) {
                                    $result_sentence['books_name'] = $book['name'];

                                    if ($book['courses_id'] > 0) {
                                        $this->load->model("courses_model");
                                        $result_sentence['courses_id'] = $book['courses_id'];
                                        $course = $this->courses_model->get_course_by_id($book['courses_id']);
                                        if ($course)
                                            $result_sentence['courses_name'] = $course['name'];
                                    }
                                }
                            }
                        }
                    }
                    array_push($result, $result_sentence);
                    break;
                case 'questions':
                    $sentence_key = $sentence_info['_source'];
                    $discussion = $this->questions_model->get_question_by_users_id_and_id($users_id, $sentence_key['questions_id']);
                    $result_discussion = array();
                    $result_discussion['type'] = 'discussions';
                    $result_discussion['questions_id'] = $discussion['questions_id'];
                    $result_discussion['question_text'] = $discussion['question'];
                    $result_discussion['answers_id'] = $discussion['answers_id'];
                    $result_discussion['answer_text'] = $discussion['answer'];
                    $result_discussion['viewed_count'] = $discussion['viewed_count'];
                    $result_discussion['answered_count'] = $discussion['answered_count'];
                    $result_discussion['reviewed_count'] = $discussion['reviewed_count'];
                    $result_discussion['asker_name'] = $discussion['asker_name'];
                    $result_discussion['reviewed_by_user'] = $discussion['reviewed_by_user'];

                    array_push($result, $result_discussion);
                    break;
                case 'vsentences':
                    $sentence_key = $sentence_info['_source'];
                    $video = $this->vsentences_model->get_vsentences_by_users_id_and_id($users_id, $sentence_key['vsentences_id']);
                    $result_videos = array();
                    $result_videos['type'] = 'videos';
                    $result_videos['videos_id'] = $video['videos_id'];
                    $result_videos['video_title'] = $video['title'];
                    $result_videos['vsentences_id'] = $video['vsentences_id'];
                    $result_videos['source_text'] = htmlspecialchars_decode($video[$video['main']], ENT_QUOTES);

                    // temp code
                    if ($d_language == 'zh-CN' && trim($video[$d_language]) == "")
                        $video[$d_language] = $video['zh'];

                    $result_videos['target_text'] = htmlspecialchars_decode($video[$d_language], ENT_QUOTES);
                    $result_videos['viewed_count'] = $video['viewed_count'];
                    $result_videos['reviewed_count'] = $video['reviewed_count'];
                    $result_videos['reviewed_by_user'] = $video['reviewed_by_user'];

                    array_push($result, $result_videos);
                    break;
                default:
                    break;
            }
        }
        return $result;
    }

    function search_all($users_id, $query, $from, $count, $s_language, $d_language)
    {
        $this->load->model("sentences_model");
        $this->load->model("questions_model");
        $this->load->model("vsentences_model");
        $result = array();
        $merged_keys = $this->sentences_model->search_all($query, $from, $count);
        foreach ($merged_keys as $key => $sentence_key) {
            switch ($sentence_key['type']) {
                case 'sentences':
                    $sentence = $this->sentences_model->get_sentences_by_users_id_and_id($users_id, $sentence_key['id']);
                    $result_sentence = array();
                    $result_sentence['type'] = 'sentences';
                    $result_sentence['sentences_id'] = $sentence['sentences_id'];
                    $result_sentence['source_text'] = $sentence[$sentence['s_language']];
                    $result_sentence['target_text'] = $sentence[$sentence['d_language']];
                    $result_sentence['reviewed_count'] = $sentence['reviewed_count'];
                    $result_sentence['viewed_count'] = $sentence['viewed_count'];
                    $result_sentence['reviewed_by_user'] = $sentence['reviewed_by_user'];

                    $result_sentence['chapters_id'] = 0;
                    $result_sentence['books_id'] = 0;
                    $result_sentence['courses_id'] = 0;
                    if ($sentence['chapters_id'] > 0) {
                        $this->load->model("chapters_model");
                        $result_sentence['chapters_id'] = $sentence['chapters_id'];
                        $chapter = $this->chapters_model->get_chapter_by_id($sentence['chapters_id']);
                        if ($chapter) {
                            $result_sentence['chapters_name'] = $chapter['name'];

                            if ($chapter['books_id'] > 0) {
                                $this->load->model("books_model");
                                $result_sentence['books_id'] = $chapter['books_id'];
                                $book = $this->books_model->get_book_by_id($chapter['books_id']);
                                if ($book) {
                                    $result_sentence['books_name'] = $book['name'];

                                    if ($book['courses_id'] > 0) {
                                        $this->load->model("courses_model");
                                        $result_sentence['courses_id'] = $book['courses_id'];
                                        $course = $this->courses_model->get_course_by_id($book['courses_id']);
                                        if ($course)
                                            $result_sentence['courses_name'] = $course['name'];
                                    }
                                }
                            }
                        }
                    }
                    array_push($result, $result_sentence);
                    break;
                case 'questions':
                    $discussion = $this->questions_model->get_question_by_users_id_and_id($users_id, $sentence_key['id']);
                    $result_discussion = array();
                    $result_discussion['type'] = 'discussions';
                    $result_discussion['questions_id'] = $discussion['questions_id'];
                    $result_discussion['question_text'] = $discussion['question'];
                    $result_discussion['answers_id'] = $discussion['answers_id'];
                    $result_discussion['answer_text'] = $discussion['answer'];
                    $result_discussion['viewed_count'] = $discussion['viewed_count'];
                    $result_discussion['answered_count'] = $discussion['answered_count'];
                    $result_discussion['reviewed_count'] = $discussion['reviewed_count'];
                    $result_discussion['asker_name'] = $discussion['asker_name'];
                    $result_discussion['reviewed_by_user'] = $discussion['reviewed_by_user'];

                    array_push($result, $result_discussion);
                    break;
                case 'vsentences':
                    $video = $this->vsentences_model->get_vsentences_by_users_id_and_id($users_id, $sentence_key['id']);
                    $result_videos = array();
                    $result_videos['type'] = 'videos';
                    $result_videos['videos_id'] = $video['videos_id'];
                    $result_videos['video_title'] = $video['title'];
                    $result_videos['vsentences_id'] = $video['vsentences_id'];
                    $result_videos['source_text'] = htmlspecialchars_decode($video[$video['main']], ENT_QUOTES);

                    // temp code
                    if ($d_language == 'zh-CN' && trim($video[$d_language]) == "")
                        $video[$d_language] = $video['zh'];

                    $result_videos['target_text'] = htmlspecialchars_decode($video[$d_language], ENT_QUOTES);
                    $result_videos['viewed_count'] = $video['viewed_count'];
                    $result_videos['reviewed_count'] = $video['reviewed_count'];
                    $result_videos['reviewed_by_user'] = $video['reviewed_by_user'];

                    array_push($result, $result_videos);
                    break;
                default:
                    break;
            }
        }
        return $result;
    }

	function search_sentences_post()
	{
		if(!$this->post('users_id') or !$this->post('query'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $users_id = $this->post('users_id');
        $query = $this->post('query');
        $from = $this->post('from');
        $count = $this->post('count');

        if (!$from)
        	$from = 0;
        if (!$count)
        	$count = 10;

        $this->load->model("users_model");
        
        $user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>401, "message"=>"Invalid users id"), 200);

        $result = $this->search_sentences_es($users_id, $query, $user['source_language'], $user['target_language'], $from, $count);

        $this->response(array("status"=>200, "result"=>$result), 200);
	}

    function search_sentences_es($users_id, $query, $s_language, $d_language, $from, $count)
    {
        $result = array();
        $this->load->model("sentences_model");
        $search_result = $this->sentences_model->search_sentences_es($users_id, $query, $s_language, $d_language, $from, $count);
        $search_array = $search_result['hits']['hits'];
        foreach ($search_array as $key => $sentence_info) {
            $sentence_key = $sentence_info['_source'];
            $sentence = $this->sentences_model->get_sentences_by_users_id_and_id($users_id, $sentence_key['sentences_id']);
            $result_sentence = array();
            $result_sentence['type'] = 'sentences';
            $result_sentence['sentences_id'] = $sentence['sentences_id'];
            $result_sentence['source_text'] = $sentence[$sentence['s_language']];
            $result_sentence['target_text'] = $sentence[$sentence['d_language']];
            $result_sentence['reviewed_count'] = $sentence['reviewed_count'];
            $result_sentence['viewed_count'] = $sentence['viewed_count'];
            $result_sentence['reviewed_by_user'] = $sentence['reviewed_by_user'];

            $result_sentence['chapters_id'] = 0;
            $result_sentence['books_id'] = 0;
            $result_sentence['courses_id'] = 0;
            if ($sentence['chapters_id'] > 0) {
                $this->load->model("chapters_model");
                $result_sentence['chapters_id'] = $sentence['chapters_id'];
                $chapter = $this->chapters_model->get_chapter_by_id($sentence['chapters_id']);
                if ($chapter) {
                    $result_sentence['chapters_name'] = $chapter['name'];

                    if ($chapter['books_id'] > 0) {
                        $this->load->model("books_model");
                        $result_sentence['books_id'] = $chapter['books_id'];
                        $book = $this->books_model->get_book_by_id($chapter['books_id']);
                        if ($book) {
                            $result_sentence['books_name'] = $book['name'];

                            if ($book['courses_id'] > 0) {
                                $this->load->model("courses_model");
                                $result_sentence['courses_id'] = $book['courses_id'];
                                $course = $this->courses_model->get_course_by_id($book['courses_id']);
                                if ($course)
                                    $result_sentence['courses_name'] = $course['name'];
                            }
                        }
                    }
                }
            }

            array_push($result, $result_sentence);
        }

        return $result;
    }


	function search_sentences($users_id, $query, $s_language, $d_language, $from, $count)
	{
		$result = array();
        $this->load->model("sentences_model");
        $sentences = $this->sentences_model->search_sentences($users_id, $query, $s_language, $d_language, $from, $count);
        foreach ($sentences as $key => $sentence) {
        	$result_sentence = array();
            $result_sentence['type'] = 'sentences';
        	$result_sentence['sentences_id'] = $sentence['sentences_id'];
        	$result_sentence['source_text'] = $sentence[$sentence['s_language']];
        	$result_sentence['target_text'] = $sentence[$sentence['d_language']];
        	$result_sentence['reviewed_count'] = $sentence['reviewed_count'];
        	$result_sentence['viewed_count'] = $sentence['viewed_count'];
            $result_sentence['reviewed_by_user'] = $sentence['reviewed_by_user'];

        	$result_sentence['chapters_id'] = 0;
       		$result_sentence['books_id'] = 0;
       		$result_sentence['courses_id'] = 0;
        	if ($sentence['chapters_id'] > 0) {
        		$this->load->model("chapters_model");
        		$result_sentence['chapters_id'] = $sentence['chapters_id'];
        		$chapter = $this->chapters_model->get_chapter_by_id($sentence['chapters_id']);
        		if ($chapter) {
	        		$result_sentence['chapters_name'] = $chapter['name'];

	        		if ($chapter['books_id'] > 0) {
	        			$this->load->model("books_model");
	        			$result_sentence['books_id'] = $chapter['books_id'];
	        			$book = $this->books_model->get_book_by_id($chapter['books_id']);
	        			if ($book) {
	        				$result_sentence['books_name'] = $book['name'];

	        				if ($book['courses_id'] > 0) {
	        					$this->load->model("courses_model");
	        					$result_sentence['courses_id'] = $book['courses_id'];
	        					$course = $this->courses_model->get_course_by_id($book['courses_id']);
	        					if ($course)
	        						$result_sentence['courses_name'] = $course['name'];
	        				}
	        			}
	        		}
	        	}
        	}

        	array_push($result, $result_sentence);
        }

        return $result;
	}

	function search_discussion_post()
	{
		if(!$this->post('users_id') or !$this->post('query'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $users_id = $this->post('users_id');
        $query = $this->post('query');
        $from = $this->post('from');
        $count = $this->post('count');

        if (!$from)
        	$from = 0;
        if (!$count)
        	$count = 10;

        $this->load->model("users_model");

        $user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>400, "message"=>"Invalid users id"), 200);

        $result = $this->search_discussion_es($users_id, $query, $user['source_language'], $user['target_language'], $from, $count);
        $this->response(array("status"=>200, "result"=>$result), 200);
	}

    function search_discussion_es($users_id, $query, $s_language, $d_language, $from, $count)
    {
        $result = array();
        $this->load->model("questions_model");
        $search_result = $this->questions_model->search_discussion_es($users_id, $query, $s_language, $d_language, $from, $count);
        $search_array = $search_result['hits']['hits'];
        foreach ($search_array as $key => $discussion_info) {
            $sentence_key = $discussion_info['_source'];
            $discussion = $this->questions_model->get_question_by_users_id_and_id($users_id, $sentence_key['questions_id']);
            if (!$discussion)
                continue;

            $result_discussion = array();
            $result_discussion['type'] = 'discussions';
            $result_discussion['questions_id'] = $discussion['questions_id'];
            $result_discussion['question_text'] = $discussion['question'];
            $result_discussion['answers_id'] = $discussion['answers_id'];
            $result_discussion['answer_text'] = $discussion['answer'];
            $result_discussion['viewed_count'] = $discussion['viewed_count'];
            $result_discussion['answered_count'] = $discussion['answered_count'];
            $result_discussion['reviewed_count'] = $discussion['reviewed_count'];
            $result_discussion['asker_name'] = $discussion['asker_name'];
            $result_discussion['reviewed_by_user'] = $discussion['reviewed_by_user'];

            array_push($result, $result_discussion);
        }
        return $result;
    }

	function search_discussion($users_id, $query, $s_language, $d_language, $from, $count)
	{
		$result = array();
        $this->load->model("questions_model");
        $discussions = $this->questions_model->search_discussion($users_id, $query, $s_language, $d_language, $from, $count);

        foreach ($discussions as $key => $discussion) {
        	$result_discussion = array();
            $result_discussion['type'] = 'discussions';
        	$result_discussion['questions_id'] = $discussion['questions_id'];
        	$result_discussion['question_text'] = $discussion['question'];
        	$result_discussion['answers_id'] = $discussion['answers_id'];
        	$result_discussion['answer_text'] = $discussion['answer'];
        	$result_discussion['viewed_count'] = $discussion['viewed_count'];
        	$result_discussion['answered_count'] = $discussion['answered_count'];
            $result_discussion['reviewed_count'] = $discussion['reviewed_count'];
            $result_discussion['asker_name'] = $discussion['asker_name'];
            $result_discussion['reviewed_by_user'] = $discussion['reviewed_by_user'];

        	array_push($result, $result_discussion);
        }
        return $result;
	}

	function search_videos_post()
	{
		if(!$this->post('users_id') or !$this->post('query'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $users_id = $this->post('users_id');
        $query = $this->post('query');
        $from = $this->post('from');
        $count = $this->post('count');

        if (!$from)
        	$from = 0;
        if (!$count)
        	$count = 10;

        $this->load->model("users_model");

        $user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>400, "message"=>"Invalid users id"), 200);

        $result = $this->search_videos_es($users_id, $query, $user['source_language'], $user['target_language'], $from, $count);

        $this->response(array("status"=>200, "result"=>$result), 200);
	}

    function search_videos_es($users_id, $query, $s_language, $d_language, $from, $count)
    {
        $result = array();
        $this->load->model("vsentences_model");
        $search_result = $this->vsentences_model->search_videos_es($users_id, $query, $s_language, $d_language, $from, $count);
        $search_array = $search_result['hits']['hits'];
        foreach ($search_array as $key => $vsentence_info) {
            $sentence_key = $vsentence_info['_source'];
            $video = $this->vsentences_model->get_vsentences_by_users_id_and_id($users_id, $sentence_key['vsentences_id']);
            if (count($video) == 0)
                continue;
            $result_videos = array();
            $result_videos['type'] = 'videos';
            $result_videos['videos_id'] = $video['videos_id'];
            $result_videos['video_title'] = $video['title'];
            $result_videos['vsentences_id'] = $video['vsentences_id'];
            $result_videos['source_text'] = htmlspecialchars_decode($video[$video['main']], ENT_QUOTES);

            // temp code
            if ($d_language == 'zh-CN' && trim($video[$d_language]) == "")
                $video[$d_language] = $video['zh'];

            $result_videos['target_text'] = htmlspecialchars_decode($video[$d_language], ENT_QUOTES);
            $result_videos['viewed_count'] = $video['viewed_count'];
            $result_videos['reviewed_count'] = $video['reviewed_count'];
            $result_videos['reviewed_by_user'] = $video['reviewed_by_user'];

            array_push($result, $result_videos);
        }
        return $result;
    }

	function search_videos($users_id, $query, $s_language, $d_language, $from, $count)
	{
		$result = array();
        $this->load->model("vsentences_model");
        $videos = $this->vsentences_model->search_videos($users_id, $query, $s_language, $d_language, $from, $count);

        foreach ($videos as $key => $video) {
        	$result_videos = array();
            $result_videos['type'] = 'videos';
        	$result_videos['videos_id'] = $video['videos_id'];
        	$result_videos['video_title'] = $video['title'];
        	$result_videos['vsentences_id'] = $video['vsentences_id'];
        	$result_videos['source_text'] = htmlspecialchars_decode($video[$video['main']], ENT_QUOTES);

            // temp code
            if ($d_language == 'zh-CN' && trim($video[$d_language]) == "")
                $video[$d_language] = $video['zh'];

        	$result_videos['target_text'] = htmlspecialchars_decode($video[$d_language], ENT_QUOTES);
        	$result_videos['viewed_count'] = $video['viewed_count'];
        	$result_videos['reviewed_count'] = $video['reviewed_count'];
            $result_videos['reviewed_by_user'] = $video['reviewed_by_user'];

        	array_push($result, $result_videos);
        }
        return $result;
	}
}