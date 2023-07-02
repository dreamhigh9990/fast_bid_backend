<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
define('DEFAULT_REVIEW_COUNT', 10);

class review extends REST_Controller
{	
	public function __construct() {
		parent::__construct();
		$this->load->model('knowledge_model');
		$this->load->model('chapters_model');
		$this->load->model('dictionary_model');	
		$this->load->model('learninghistory_model');
		$this->load->model("review_model");
	}

	private $review_sentences = array(
		
		array(
			"sentence_id" => 1,
			"en_sentence" => "My friend came to me and said about the story",
			"zh-CN" =>"这只狗是我们的",
			"level" => 4,
			"word_list" => array(
				"0"=> "My",
				"1"=> "friend",
				"2"=> "came",
				"3"=> "to",
				"4"=> "me",
				"5"=> "and",
				"6"=> "said",
				"7"=> "about",
				"8"=> "the",
				"9"=> "story",
			),
			"sentence_list" => array(
				array(
					"content"=> "The dog is ours",
					"id" => 1
				),
				array(
					"content"=> "The dog is ours_1.",
					"id" => 2
				),
				array(
					"content"=> "The dog is ours_2.",
					"id" => 3
				),array(
					"content"=> "The dog is ours_3.",
					"id" => 4
				)
			),
			"grammar_list" => array(
				"0"=> "noun",
				"1"=> "possessive",
				"2"=> "be",
				"3"=> "object"
			),
			"audio" => "test.wma"
		),
		array(
			"sentence_id" =>2,
			"en_sentence" => "I am yours",
			"zh-CN" =>"我是你的.",
			"level" => 2,
			"word_list" => array(
				"0"=> "I",
				"1"=> "am",
				"2"=> "yours",
			),
			"sentence_list" => array(
				array(
					"content"=> "I am yours",
					"id" => 2
				),
				array(
					"content"=> "I am yours._1.",
					"id" => 3
				),
				array(
					"content"=> "I am yours._2.",
					"id" => 4
				),array(
					"content"=> "I am yours._3.",
					"id" => 5
				)
			),
			"grammar_list" => array(
				"0"=> "possessive",
				"1"=> "be",
				"2"=> "noun",
			),
			"audio" => "test.wma"
		),
	

		array(
			"sentence_id"=> 3,
			"en_sentence" => "I have these men's books",
			"zh-CN" =>"我有这些男人的书",
			"level" => 2,
			"word_list" => array(
				"0"=> "I",
				"1"=> "have",
				"2"=> "these",
				"3"=> "men's",
				"4"=> "books",
			),
			"sentence_list" => array(
				array(
					"content"=> "I have these men's books",
					"id" => 3
				),
				array(
					"content"=> "I have these men's books._1.",
					"id" => 4
				),
				array(
					"content"=> "I have these men's books._2.",
					"id" => 5
				),array(
					"content"=> "I have these men's books._3.",
					"id" => 6
				)
			),
			"grammar_list" => array(
				"0"=> "possessive",
				"1"=> "plural",
				"2"=> "noun",
				"3"=> "have"
			),
			"audio" => "test.wma"
		),
		array(
			"sentence_id" => 4,
			"en_sentence" => "Are you here",
			"zh-CN" =>"我是你的.",
			"level" => 3,
			"word_list" => array(
				"0"=> "Are",
				"1"=> "here",
				"2"=> "you",
			),
			"sentence_list" => array(
				array(
					"content"=> "Are you here",
					"id" => 4
				),
				array(
					"content"=> "I am yours._1.",
					"id" => 5
				),
				array(
					"content"=> "I am yours._2.",
					"id" => 6
				),array(
					"content"=> "I am yours._3.",
					"id" => 7
				)
			),
			"grammar_list" => array(
				"0"=> "possessive",
				"1"=> "be",
				"2"=> "noun",
			),
			"audio" => "test.wma"
		),
		array(
			"sentence_id"=> 5,
			"en_sentence" => "The child is mine",
			"zh-CN" =>"这只狗是我们的",
			"level" => 3,
			"word_list" => array(
				"0"=> "The",
				"1"=> "child",
				"2"=> "is",
				"3"=> "mine",
			),
			"sentence_list" => array(
				array(
					"content"=> "The child is mine",
					"id" => 5
				),
				array(
					"content"=> "The child is mine._1.",
					"id" => 6
				),
				array(
					"content"=> "The child is mine._2.",
					"id" => 7
				),array(
					"content"=> "The child is mine._3.",
					"id" => 8
				)
			),
			"grammar_list" => array(
				"0"=> "definite_article",
				"1"=> "be",
				"2"=> "possessive",
				"3"=> "noun"
			),
			"audio" => "test.wma"
		),
		array(
			"sentence_id" => 6,
			"en_sentence" => "The horse is yours",
			"zh-CN" =>"我是你的.",
			"level" => 3,
			"word_list" => array(
				"0"=> "The",
				"1"=> "horse",
				"2"=> "is",
				"3"=> "yours",
			),
			"sentence_list" => array(
				array(
					"content"=> "The horse is yours",
					"id" => 6
				),
				array(
					"content"=> "The horse is yours.._1.",
					"id" => 7
				),
				array(
					"content"=> "The horse is yours.._2.",
					"id" => 8
				),array(
					"content"=> "The horse is yours.._3.",
					"id" => 9
				)
			),
			"grammar_list" => array(
				"0"=> "definite_article",
				"1"=> "noun",
				"2"=> "be",
				"3"=> "possessive"
			),
			"audio" => "test.wma"
		),

		array(
			"sentence_id"=> 7,
			"en_sentence" => "He reads me one book",
			"zh-CN" =>"他给我读一本书",
			"level" => 4,
			"word_list" => array(
				"0"=> "me",
				"1"=> "reads",
				"2"=> "He",
				"3"=> "one",
				"4"=> "a",
				"5"=> "book",
			),
			"sentence_list" => array(
				array(
					"content"=> "He reads me one book",
					"id" => 7
				),
				array(
					"content"=> "He reads me one book./ He reads me a book.._1.",
					"id" => 8
				),
				array(
					"content"=> "He reads me one book./ He reads me a book.._2.",
					"id" => 9
				),array(
					"content"=> "He reads me one book./ He reads me a book.._3.",
					"id" => 10
				)
			),
			"grammar_list" => array(
				"0"=> "verb",
				"1"=> "noun",
				"2"=> "object",
			),
			"audio" => "test.wma"
		),
		array(
			"sentence_id"=>8,
			"en_sentence" => "She reads them one book",
			"zh-CN" =>"她给他们读一本书.",
			"level" => 3,
			"word_list" => array(
				"0"=> "She",
				"1"=> "reads",
				"2"=> "them",
				"3"=> "one",
				"4"=> "book",
			),
			"sentence_list" => array(
				array(
					"content"=> "She reads them one book",
					"id" => 8
				),
				array(
					"content"=> "She reads them one book./She reads them a book._1.",
					"id" => 9
				),
				array(
					"content"=> "She reads them one book./She reads them a book.._2.",
					"id" => 10
				),array(
					"content"=> "She reads them one book./She reads them a book._3.",
					"id" => 11
				)
			),
			"grammar_list" => array(
				"0"=> "verb",
				"1"=> "object",
				"2"=> "noun",
			),
			"audio" => "test.wma"
		)	
	);


	private $optional_words_list = array(
		'train','venomous','bang','inconclusive','secret','cagey','measly','unfasten','sticky','twist','please','delight','grain','reflective','picayune','squeak',
		'ill','notebook','slim','tangible','room','slip','snow','prose','line','useless','umbrella','rake','violent','nation','stale','own','annoying','station',
		'ceaseless','note','abaft','bat','cheerful','toothsome','powder','deep','knowledgeable','adjustment','ring','carve','questionable','messy','direful',
		'naive','space','mess up','depend','plant','attack','witty','staking','helpful','empty','plants','sordid','crayon','festive','fool','rabbit','interest',
		'sail','needy','duck','move','taboo','large','side','cloistered','high','note','lip','lake','knit','examine','discovery','zoo','shelf','quaint','save',
		'white','cough','yummy','smell','inform','faulty','beneficial','dare','woman','furry','throat','nine','pull','kind','stare','loss','lonely','quarrelsome',
		'last','wipe','fuzzy','writing','press','acrid','slippery','kettle','brief','absorbing','separate','license','hot','dazzling','impolite','frame','confess',
		'doctor','fanatical','elegant','veil','tough','finicky','jeans','wash','ritzy','mask','mysterious','call','ragged','wash','fancy','rhetorical','water',
		'ship','lush','interest','fretful','dress','bedroom','fork','wild','odd','common','mixed','wheel','late','curve','stop','field','spark','soap','determined',
		'encouraging','limit','languid','motionless','smart','fat','brave','knife','coal','psychotic','wriggle','structure','curtain','kiss','house','guarded',
		'outstanding','dam','half','dashing','cooing','grandmother','tease','suffer','calm','wonderful','jam','ordinary','texture','snatch','insurance','third','zonked',
		'awake','books','jagged','regular','flash','possible','limping','hang','attend','subtract','colour','need','square','superficial','wealthy','month','able',
		'ignorant','defective','super','fairies','willing','imperfect','medical','arrange','guess','government','fascinated','exclusive','swing','wise','crazy',
		'oven','victorious','ocean','sparkling','cook','shiny','dramatic','uttermost','deafening','mitten','mean','tranquil','expensive','quiver','one',
		'pump','partner','womanly','close','respect','pray','ill-fated','fail','wilderness','seat','grieving','song','gainful','familiar','aback','ajar',
		'scary','overflow','crow','horse','earth','reminiscent','heap','wiggly','border','wary','hurry','enthusiastic','agonizing','snobbish','dislike',
		'hover','dirty','alleged','hole','grandiose','roll','outgoing','moan','thread','rude','frighten','supreme','slope','hunt','amount','church','workable',
		'historical','island','different','cobweb','amusement','four','alarm','disgusted','minor','turn','drain','poor','flame','tremble','juggle','spray'
	);

	function add_into_review_list_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
        $sentences_id	= $this->post('sentences_id');
		$questions_id	= $this->post('questions_id');
		$answers_id		= $this->post('answers_id');
		$vsentences_id	= $this->post('vsentences_id');

		$result = $this->review_model->store_review(array(
			'users_id'=>$users_id,
			'sentences_id'=>$sentences_id,
			'questions_id'=>$questions_id,
			'answers_id'=>$answers_id,
			'vsentences_id'=>$vsentences_id,
			'date'=>date('Y-m-d H:i:s'),
			));

		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		if ($sentences_id > 0) 
		{
			$this->load->model("sentences_model");
			if (!$this->sentences_model->increase_review_count($sentences_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing sentences review count"), 200);
		}
		if ($answers_id > 0) 
		{
			$this->load->model("answers_model");
			if (!$this->answers_model->increase_review_count($answers_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing answers review count"), 200);
		}
		if ($vsentences_id > 0) 
		{
			$this->load->model("vsentences_model");
			if (!$this->vsentences_model->increase_review_count($vsentences_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing video sentences review count"), 200);
		}

		$this->response(array("status"=>200, "message"=>"Successfully added into review list"), 200);
	}

	function remove_from_review_list_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
        $sentences_id	= $this->post('sentences_id');
		$questions_id	= $this->post('questions_id');
		$answers_id		= $this->post('answers_id');
		$vsentences_id	= $this->post('vsentences_id');

		$result = $this->review_model->delete_review(array(
			'users_id'=>$users_id,
			'sentences_id'=>$sentences_id,
			'questions_id'=>$questions_id,
			'answers_id'=>$answers_id,
			'vsentences_id'=>$vsentences_id,
			));

		if ($sentences_id > 0) 
		{
			$this->load->model("sentences_model");
			if (!$this->sentences_model->decrease_review_count($sentences_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing sentences review count"), 200);
		}
		if ($answers_id > 0) 
		{
			$this->load->model("answers_model");
			if (!$this->answers_model->decrease_review_count($answers_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing answers review count"), 200);
		}
		if ($vsentences_id > 0) 
		{
			$this->load->model("vsentences_model");
			if (!$this->vsentences_model->decrease_review_count($vsentences_id))
				$this->response(array("status"=>401, "message"=>"Errors occured while increasing video sentences review count"), 200);
		}

		$this->response(array("status"=>200, "message"=>"Successfully removed from review list"), 200);
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
		$query 		= $this->post('query');
		if (!$query)
			$query = "";
		$from 		= $this->post('from');
		if (!$from)
			$from = 0;
		$count 		= $this->post('count');
		if (!$count)
			$count = DEFAULT_REVIEW_COUNT;

		$this->load->model("users_model");
		$this->load->model("sentences_model");
        $this->load->model("questions_model");
        $this->load->model("vsentences_model");
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

		$result = $this->review_model->set_review_score($users_id, $reviewed_date, $reviewed_sentences_count, $reviewed_words_count, $reviewed_accuracy, $success, $from_date, $to_date);
		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully set review score"), 200);

	}


	//* Review progress *//

	function get_review_sentence_list($chapters_id) {
		$smart_sentences = $this->review_model->get_smart_course($chapters_id);
		
		//shuffle($smart_sentences);
		//$result = array_slice($smart_sentences, 0, 8);		
		return $smart_sentences;		
	}

	function get_sentence_by_id($sentences_id) {
		$sentence = $this->review_model->get_sentence_by_id($sentences_id);
		return $sentence;
	}

	function split_sentence_by_word($sentence = "", $fake_flag) {

		$word_arr = preg_split("/[\s,\.,\!,\?,]+/", $sentence);
		$filteredarray = array_values( array_filter($word_arr) );
		if ($fake_flag) {
			for ($i=0; $i < 4; $i++) { 
				$random_number = mt_rand(0, 250);
				$filteredarray = array_merge($filteredarray, array($this->optional_words_list[$random_number]));	
			}
			$filteredarray = array_merge($filteredarray, array(','));
		}
		//$this->response($filteredarray);
		shuffle($filteredarray);
		return $filteredarray;
	}
	function split_id($lists = "") {
		$id_arr = preg_split("/[\s,\.,\!,\?]+/", $lists);
		$filteredarray = array_values( array_filter($id_arr) );
		
		//$this->response($filteredarray);
		return $filteredarray;		
	}

	function get_simular_sentence_list($sentences_id) {
		$correct_sentences = array();
		$not_correct_sentences = array();
		$simular_sentence_list = $this->review_model->get_simular_sentence_list($sentences_id);
		foreach ($simular_sentence_list as $key => $sentence) {
			if ($key == 0) {
				array_push($correct_sentences, $sentence);
			} else {
				array_push($not_correct_sentences, $sentence);
			}
		}

		shuffle($not_correct_sentences);
		$not_correct_sentences = array_slice($not_correct_sentences, 0, 3);
		return array_merge($correct_sentences,$not_correct_sentences);
		
	}

	function get_simular_sentence_list_discussion_video($id, $en, $type) {
		$review_sentences = array();
		$correct_sentences = array();
		$not_correct_sentences = array();

		$simular_sentence_list = $this->review_model->get_random_sentence_list();
		if ($type == 'discussions') {
			$correct_sentences['en'] = $en;
			$correct_sentences['vsentence_id']= $id;
		} else {
			$correct_sentences['en'] = $en;
			$correct_sentences['questions_id']= $id;
		}
		foreach ($simular_sentence_list as $key => $sentence) {
			array_push($not_correct_sentences, $sentence);
		}

		shuffle($not_correct_sentences);
		$not_correct_sentences = array_slice($not_correct_sentences, 0, 3);
		array_push($review_sentences, $correct_sentences);
		foreach ($not_correct_sentences as $key => $sentence) {
			array_push($review_sentences, $sentence);
		}
		shuffle($review_sentences);
		return $review_sentences;
	}

	/*  Get Smart Course List Info*/

    function get_review_smart_chatper_id($users_id){
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

	function update_learning_history_post() {
		$users_id = $this->post('users_id');
		$courses_flag = $this->post('courses_flag');
		$chapters_id = $this->post('chapters_id');

		$this->learninghistory_model->update_chapter_confirm($users_id, $chapters_id, $courses_flag);
		$this->response(array("status"=>200, "result"=>"success"));
	}

	function get_level_favourite_sentences($users_id, $sentence) {
		$level_arr = array();
		$word_arr = preg_split("/[\s,\.,\!,\?]+/", $sentence);
		$filteredarray = array_values( array_filter($word_arr));
		array_unique($filteredarray);
		foreach ($filteredarray as $key => $word) {
			$word_id = $this->dictionary_model->get_word_id($word);
			if (!empty($word_id)) {
				$word_confidence = $this->knowledge_model->get_word_confidence($users_id, $word_id[0]['word_id']);
				if (!empty($word_confidence)) {
					if ($word_confidence[0]['confidence'] <= 30) {
						$level = 1;
					} else if ($word_confidence[0]['confidence'] > 30 && $word_confidence[0]['confidence'] <= 50) {
						$level = 2;
					} else if ($word_confidence[0]['confidence'] > 50 && $word_confidence[0]['confidence'] <= 70) {
						$level = 3;
					} else {
						$level = 4;
					}			
					array_push($level_arr, $level);
				}
			}
		}
		//$this->response(array("status"=>200, "result"=>$level_arr, "word"=>$filteredarray), 200);
		return min($level_arr);
	}

	function get_review_favourite_sentences_post() {
		
		$f_sentences = array();
		
		$users_id = $this->post('users_id');
		$json_sentences_data = $this->post('favourite_sentences');
		if (!$users_id || empty($json_sentences_data)) {
			$this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
		}
		$f_data = json_decode($json_sentences_data);
		foreach ($f_data as $key => $data) {
			$f_sentence = array();
			if ($data->types == 'discussions') {
				$f_sentence['types'] = 'discussions';
				$f_sentence['en_sentence'] = $data->questions_text;
				$f_sentence['zh-CN'] = $data->answers_text;
				$f_sentence['questions_id'] = $data->questions_id;
				$f_sentence['answers_id'] = $data->answers_id;
				$f_sentence['word_list'] = $this->split_sentence_by_word($f_sentence['en_sentence'], true);
				$f_sentence['audio'] = null;
				$f_sentence['grammar_list'] = [];
				$f_sentence['sentence_list'] = $this->get_simular_sentence_list_discussion_video($f_sentence['questions_id'], $f_sentence['en_sentence'], 'discussions');
				$f_sentence['level'] = $this->get_level_favourite_sentences($users_id, $f_sentence['en_sentence']);
			} else if ($data->types == 'sentences') {
				$f_sentence['types'] = 'sentences';
				$f_sentence['en_sentence'] = $data->target_text;
				$f_sentence['zh-CN'] = $data->source_text;
				$f_sentence['sentences_id'] = $data->sentences_id;
				$f_sentence['word_list'] = $this->split_sentence_by_word($f_sentence['en_sentence'], true);
				$f_sentence['audio'] = null;
				$f_sentence['grammar_list'] = [];
				$f_sentence['sentence_list'] = $this->get_simular_sentence_list($f_sentence['sentences_id']);
				$f_sentence['level'] = $f_sentence['level'] = $this->get_level_favourite_sentences($users_id, $f_sentence['en_sentence']);
			}
			else {
				$f_sentence['types'] = 'videos';
				$f_sentence['en_sentence'] = $data->source_text;
				$f_sentence['zh-CN'] = $data->target_text;
				$f_sentence['vsentences_id'] = $data->vsentences_id;
				$f_sentence['word_list'] = $this->split_sentence_by_word($f_sentence['en_sentence'], true);
				$f_sentence['audio'] = null;
				$f_sentence['grammar_list'] = [];
				$f_sentence['sentence_list'] = $this->get_simular_sentence_list_discussion_video($f_sentence['vsentences_id'], $f_sentence['en_sentence'], 'videos');
				$f_sentence['level'] = $f_sentence['level'] = $this->get_level_favourite_sentences($users_id, $f_sentence['en_sentence']);
			}

			array_push($f_sentences, $f_sentence);
				
		}
		$this->response(array("status"=>200, "reviews_sentences"=>$f_sentences), 200);
	}

	function get_review_smart_course_sentence_list_post(){
		$word_arr = array();
		$grammar_arr = array();
		$result_arr = array();
		$smart_course_sentence = array();
		$smart_course_sentences_arr = array();
		$result_temp_arr = array();
		$result_arr = array();
		$review_word_arr = array();

		$users_id = $this->post('users_id');
		
		$smart_courses_chapters = $this->get_review_smart_chatper_id($users_id);
		$smart_sentences = $this->get_review_sentence_list($smart_courses_chapters['chapters_id']);
		
		foreach ($smart_sentences as $key => $sentence) {	
			
			$word_level_arr = array();
			$grammar_level_arr = array();
			$review_word_arr = $this->split_sentence_by_word($sentence['en'], true);
			$word_arr = $this->split_id($sentence['words']);
			$grammar_arr = $this->split_id($sentence['grammars']);
			$simular_sentence_list = $this->get_simular_sentence_list($sentence['sentences_id']);
			
			
			foreach ($word_arr as $key => $words_id) {
				$query_words_id[] = $words_id;
			}
			$word_confidences_list = $this->knowledge_model->get_confidences_list_by_wordlist($users_id, $query_words_id);

			foreach ($word_confidences_list as $key => $word_confidences) {
				if ($word_confidences['confidence'] <= 30) {
					$level = 1;
				} else if ($word_confidences['confidence'] > 30 && $word_confidences['confidence'] <= 50) {
					$level = 2;
				} else if ($word_confidences['confidence'] > 50 && $word_confidences['confidence'] <= 70) {
					$level = 3;
				} else {
					$level = 4;
				}			
				array_push($word_level_arr, $level);
			}

			foreach ($grammar_arr as $key => $grammars_id) {
				$query_grammars_id[] = $grammars_id;
			}
			$grammar_confidences_list = $this->knowledge_model->get_confidences_list_by_grammarlist($users_id, $query_words_id);

			foreach ($grammar_confidences_list as $key => $grammar_confidences) {
				if ($grammar_confidences['confidence'] <= 30) {
					$level = 1;
				} else if ($grammar_confidences['confidence'] > 30 && $grammar_confidences['confidence'] <= 50) {
					$level = 2;
				} else if ($grammar_confidences['confidence'] > 50 && $grammar_confidences['confidence'] <= 70) {
					$level = 3;
				} else {
					$level = 4;
				}			
				array_push($grammar_level_arr, $level);
			}

			if (!empty($word_level_arr)) {
				$word_level = min($word_level_arr);
				if (!empty($grammar_level_arr)) {
					$grammar_level = min($grammar_level_arr);
					$total_level = min($word_level, $grammar_level);
				} else {
					$total_level = $word_level;
				}
			}
			else {
				if (!empty($grammar_level_arr)) {
					$grammar_level = min($grammar_level_arr);
					$total_level = $grammar_level;
				} else {
					$total_level = 1;
				}
			}
			shuffle($simular_sentence_list);
		
			$result_temp_arr = array(
				"sentence_id"=> $sentence['sentences_id'],
				"en_sentence"=> $sentence['en'],
				"zh-CN"=> $sentence['zh-CN'],
				"audio"=> $sentence['audio'],
				"word_list"=> $review_word_arr,
				"grammar_list"=> $grammar_arr,
				"sentence_list" => $simular_sentence_list,
				"level" => $total_level,
				"types" =>"sentences"
			);				
			array_push($result_arr, $result_temp_arr);
		}
	
		$level_key = array();
		foreach ($result_arr as $key => $row)
		{
			$level_key[$key] = $row['level'];
		}
		array_multisort($level_key, SORT_ASC, $result_arr);
		$this->response(array("status"=>200, "reviews_sentences"=>$result_arr), 200);
	}

	

	function get_review_general_course_sentence_list_post(){
		$word_arr = array();
		$grammar_arr = array();
		$result_arr = array();
		$smart_course_sentence = array();
		$smart_course_sentences_arr = array();
		$result_temp_arr = array();
		$result_arr = array();
		$review_word_arr = array();

		$chapters_id = $this->post('chapters_id');
		$users_id = $this->post('users_id');
			
		$smart_sentences = $this->get_review_sentence_list($chapters_id);
		
		foreach ($smart_sentences as $key => $sentence) {	
			
			$word_level_arr = array();
			$grammar_level_arr = array();
			$review_word_arr = $this->split_sentence_by_word($sentence['en'], true);
			$word_arr = $this->split_id($sentence['words']);
			$grammar_arr = $this->split_id($sentence['grammars']);
			$simular_sentence_list = $this->get_simular_sentence_list($sentence['sentences_id']);
			

			foreach ($word_arr as $key => $words_id) {
				$query_words_id[] = $words_id;
			}
			$word_confidences_list = $this->knowledge_model->get_confidences_list_by_wordlist($users_id, $query_words_id);

			foreach ($word_confidences_list as $key => $word_confidences) {
				if ($word_confidences['confidence'] <= 30) {
					$level = 1;
				} else if ($word_confidences['confidence'] > 30 && $word_confidences['confidence'] <= 50) {
					$level = 2;
				} else if ($word_confidences['confidence'] > 50 && $word_confidences['confidence'] <= 70) {
					$level = 3;
				} else {
					$level = 4;
				}			
				array_push($word_level_arr, $level);
			}

			foreach ($grammar_arr as $key => $grammars_id) {
				$query_grammars_id[] = $grammars_id;
			}
			$grammar_confidences_list = $this->knowledge_model->get_confidences_list_by_grammarlist($users_id, $query_words_id);

			foreach ($grammar_confidences_list as $key => $grammar_confidences) {
				if ($grammar_confidences['confidence'] <= 30) {
					$level = 1;
				} else if ($grammar_confidences['confidence'] > 30 && $grammar_confidences['confidence'] <= 50) {
					$level = 2;
				} else if ($grammar_confidences['confidence'] > 50 && $grammar_confidences['confidence'] <= 70) {
					$level = 3;
				} else {
					$level = 4;
				}			
				array_push($grammar_level_arr, $level);
			}
			
			if (!empty($word_level_arr)) {
				$word_level = min($word_level_arr);
				if (!empty($grammar_level_arr)) {
					$grammar_level = min($grammar_level_arr);
					$total_level = min($word_level, $grammar_level);
				} else {
					$total_level = $word_level;
				}
			}
			else {
				if (!empty($grammar_level_arr)) {
					$total_level = min($grammar_level_arr);
				} else {
					$total_level = 1;
				}
			}
			
			shuffle($simular_sentence_list);
			$review_flag = $this->review_model->check_sentence_review($sentence['sentences_id'], $users_id);
			$result_temp_arr = array(
				"sentence_id"=> $sentence['sentences_id'],
				"en_sentence"=> $sentence['en'],
				"zh-CN"=> $sentence['zh-CN'],
				"audio"=> $sentence['audio'],
				"word_list"=> $review_word_arr,
				"grammar_list"=> $grammar_arr,
				"sentence_list" => $simular_sentence_list,
				"level" => $total_level,
				"types" =>"sentences",
				"review_check" => $review_flag
			);				
			array_push($result_arr, $result_temp_arr);
		}
		
		$this->response(array("status"=>200, "reviews_sentences"=>$result_arr), 200);
	}

	
	function update_word_confidence($users_id, $word_id) {
		$current_confidence =  $this->knowledge_model->get_word_confidence($users_id, $word_id);
		$update_confidence['confidence'] = intval($current_confidence[0]['confidence']) + 10;
		$update_confidence['last_study_date'] = date('Y-m-d H:i:s');
		$result = $this->knowledge_model->update_word_confidence($users_id, $word_id, $update_confidence);
		return $result;
	}

	function update_grammar_confidence($users_id, $grammar_id) {
		$current_confidence =  $this->knowledge_model->get_grammar_confidence($users_id, $grammar_id);
		$update_confidence['confidence'] = intval($current_confidence[0]['confidence']) + 10;
		$update_confidence['last_study_date'] = date('Y-m-d H:i:s');
		$result = $this->knowledge_model->update_grammar_confidence($users_id, $grammar_id, $update_confidence);
		return $result;
	}


	function update_knowledge_confidence_post() {
		$users_id = $this->post('users_id');
		$sentences_id = $this->post('sentences_id');
		$courses_flag = $this->post('courses_flag');
		$success_flag = $this->post('success_flag');
		$types = $this->post('types');
		$reviewed_sentence = $this->post('reviewed_sentence');

		if ($types === 'sentences') {
			$sentence = $this->get_sentence_by_id($sentences_id);
			$chapters =$this->chapters_model->get_chapter_by_id($sentence[0]['chapters_id']);
			$word_arr = $this->split_sentence_by_word($sentence[0]['en'], false);
			$grammar_arr = $this->split_id($sentence[0]['grammars']);
			$learning_history = $this->learninghistory_model->check_learning_chapter_history($users_id, $sentence[0]['chapters_id']);
			if (empty($learning_history[0])) {
				$learning_history_newdata = array(
					'users_id' => $users_id,
					'smart_flag' => $courses_flag == 'smart' ? 1 : 0,
					'courses_id' => $chapters['courses_id'],
					'chapters_id' => $chapters['chapters_id'],
					'words' => $sentence[0]['words'],
					'grammars' => $sentence[0]['grammars'], 
					'chapter_sentences_count' => 1,
					'date' => date('Y-m-d H:i:s'),
					'success_flag' =>0,
				);
				$this->learninghistory_model->store_learning_chapter_history($learning_history_newdata);
			} else {
				$learning_history_updatedata = array(
					'users_id' => $users_id,
					'smart_flag' => $courses_flag == 'smart' ? 1 : 0,
					'courses_id' => $chapters['courses_id'],
					'chapters_id' => $chapters['chapters_id'],
					'words' => $learning_history[0]['words'].','.$sentence[0]['words'],
					'grammars' => $sentence[0]['grammars'] != '' ? $learning_history[0]['grammars'].','.$sentence[0]['grammars'] : $learning_history[0]['grammars'],
					'chapter_sentences_count' => $learning_history[0]['chapter_sentences_count'] + 1, 
					'date' => date('Y-m-d H:i:s'),
					'success_flag' =>0,
				);

				$this->learninghistory_model->update_learning_chapter_history($users_id, $sentence[0]['chapters_id'], $learning_history_updatedata);
			}	

			for ($i=0; $i < count($word_arr) ; $i++) { 
				$word_id = $this->dictionary_model->get_word_id($word_arr[$i]);
				if(!empty($word_id)) {
					$update_result = $this->update_word_confidence($users_id, $word_id[0]['word_id']);
				}
			}

			for ($i=0; $i < count($grammar_arr) ; $i++) { 
				$update_result = $this->update_grammar_confidence($users_id, $grammar_arr[$i]);
			}
		} else {
			 
			$word_arr = preg_split("/[\s,\.,\!,\?]+/", $reviewed_sentence);
			$filteredarray = array_values( array_filter($word_arr));
			array_unique($filteredarray);
			foreach ($filteredarray as $key => $word) {
				$word_id = $this->dictionary_model->get_word_id($word);
				if (!empty($word_id)) {
					$words_confidences_data[] = array(
						'confidence'=>100,
						'w_g_id' => $word_id[0]['word_id']
					);
				}
			}
			$update_result = $this->knowledge_model->update_word_confidence_list($users_id, $words_confidences_data);
		}

		$this->response(array("status"=>200, "result"=> $update_result), 200);

	}
}