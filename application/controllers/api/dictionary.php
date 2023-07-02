<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class dictionary extends REST_Controller
{	
  	public $increase_rate = array(0 => 1.1, 1=> 1.2, 2=> 1.3, 3=> 1.4, 4=> 1.5, 5=> 1.55, 6=> 1.6, 7=> 1.65, 8=>1.7, 9=>1.75);

	public $decrease_rate = array(0=>0.9, 1=>0.8, 2=>0.7, 3=>0.6, 4=>0.5, 5=>0.4, 6=>0.3, 7=>0.2, 8=>0.1);
	
	public $pattern1_1 = array(0, 1, 0, 1, 0, 1);
	public $pattern1_2 = array(0, 1, 0, 1, 0, 0);
	public $pattern1_3 = array(0, 1, 0, 0);
	public $pattern1_4 = array(0, 1, 0, 0, 1, 0);
	
	public $patten2_1 = array(1, 0, 1, 0, 1, 0);
	public $patten2_2 = array(1, 0, 1, 0, 0);
	public $patten2_3 = array(1, 0, 0, 1, 0, 0);

	public $pattern3_1 = array(0, 0, 0);

    
	private $fake_words_list = array(
		'hybufe', 'fece', 'gohug', 'dadyd', 'hibodyhoh', 'dycuc', 'cagic', 'fybihyg', 'gaheco', 'boho', 'bafibeg', 'bygegy', 'cocy', 'cobocedo', 'gahuci', 'hegi', 'dufyfyh', 
		'cocu', 'docy', 'facihoha', 'ducyci', 'fifyc', 'fahyfy', 'gifo', 'gohef', 'hocehy', 'gubyb', 'dihaco', 'digag', 'degef', 'cuhehyga', 'hehah', 'dehad', 'hihy', 'dydyh', 
		'dybadud', 'cocobabu', 'dubybac', 'cigyfic', 'gihyg', 'biceh', 'cuducah', 'cybify', 'cogidic', 'fafaca', 'bigegeg', 'fahid', 'gefyf', 'gedyhu', 'gubeg', 'cybaf', 
		'gafybub', 'fihe', 'bygogafu', 'habebe', 'bufodog', 'hacefa', 'fube', 'hifybu', 'gygyg', 'dobofu', 'hogodid', 'fidud', 'fugeguc', 'dugaf', 'cafugo', 'babyb', 'cegu', 
		'dagy', 'hifub', 'fihagu', 'gefehad', 'hobugeb', 'fufudo', 'fefyh', 'hugag', 'huby', 'fyhufo', 'gaceduga', 'hugudyd', 'dudadog', 'cihuda', 'figygi', 'bibed', 'fogicu', 
		'biha', 'hece', 'dehagadu', 'cehife', 'bugif', 'hydufac', 'fihohi', 'fabeheh', 'fyhub', 'cugyd', 'fefod', 'bafyd', 'gyhe', 'fydad', 'dugedic', 'gibid', 'bubuh', 'cobyc', 
		'bufe', 'cafihob', 'habygeh', 'bacog', 'gabebic', 'gehob', 'gugufyg', 'gehuf', 'becefab', 'fagyb', 'gebudu', 'difagofy', 'hecaf', 'gubogad', 'cuhoh', 'dufid', 'gogaf', 
		'buhuhacy', 'hydeged', 'gucaci', 'hefohy', 'dubada', 'bagudifi', 'cadegoc', 'hicecac', 'hugagyb', 'bibado', 'cebe', 'fegyb', 'cofagyh', 'hyfi', 'hafade', 'befyfi', 'cygy', 
		'degideb', 'hoguc', 'fefifobo', 'febih', 'dacoci', 'dibyhu', 'cadudicu', 'gugoc', 'gufeh', 'fifug', 'fygih', 'dafehog', 'gyfohy', 'byhyc', 'gebeb', 'gufi', 'cudeby',
		'zoolater','summitless','quarnero','vitoria','transcorporeal','awakener','subtrigonal','aedilberct','scenic','noncatarrhal','breveting','nonsynoptic','cygnet','mocambique',
		'bibliotaphic','zealand','curdiness','knobbler','preorganically','unlooted','conceptualized','autosuggestible','deplorably','contoise','vase','uniflorous','cycladic','papaya',
		'gramme','semipacifistic','neonate','calorifacient','lasse','remillable','unrelishing','recollected','achaemenids','nonconflicting','palacelike','cï¾¾dmon','predeliberate','shogging',
		'skintight','quercetin','rialto','intramuscular','siddons','iliocostalis','dyophysitic','automatize','cocainized','gibbously','submicroscopic',
		'turgency','kebar','wobbly','preoverthrowing','jacobsen','saurel','beerhouses','insaneness','bindery','reservedly','sniff','lemonnier','delsarte','acarologist','shelleyan','troubleshooted',
		'imprisonment','cardea','relatively','claypan','aborigine','lynda','scratchably','reharmonizing','unaudacious','vickie','wrest','deimos','leschetizky','vietcong','sentimentaliser',
		'assurance','barbarising','omphalocele','unabsolved','delate','syllagising','lev','lignify','vaulted','rudimental','anagrammatic','pewage','thermalgesia','outpry','hyperexcitement','masorah'
	);


	public function __construct() {
		parent::__construct();
		$this->load->model('dictionary_model'); 
		$this->load->model('review_model');
		$this->load->model('knowledge_model'); 
		$lastest_id = $this->dictionary_model->get_lastest_id();
		$first_id = $this->dictionary_model->get_first_id();
		$this->session->set_userdata('lastest_id', $lastest_id[0]['word_id']);
		$this->session->set_userdata('first_id', $first_id[0]['word_id']);
		   
	}
	
	/* Check pattern*/
	function word_test_stop($test_result, $pattern) {
		$pattern_str = implode("", $pattern); 
		if (strlen(strpos($test_result, $pattern_str)) > 0) {
    		return false; 
		}
		else {
			return true;
		}
	}

	/*   Get rate Key */
	function get_rate_key($current_test_result, $flag) {
		$rate_key = 0;
		if ($flag) {
			for ($i = strlen($current_test_result)-1; $i >= 0; $i--) { 
				if ($current_test_result[$i] === "1") {
					$rate_key = $rate_key + 1;	
				} else {
					break;
				}
			}
			return $rate_key - 1;
		}
		else {
			for ($i = strlen($current_test_result)-1; $i >= 0; $i--) { 
				if ($current_test_result[$i] === "0") {
					$rate_key = $rate_key + 1;	
				} else {
					break;
				}
			}
			return $rate_key;
			
		}
	}

	/*   Get word by word ID */
	function get_word($word_id=1) {
		$word_arr = $this->dictionary_model->get_word($word_id);
		return  $word_arr;
	}
	

	/*   Get all word  */
	function get_all_words_post() {
		$all_word = $this->dictionary_model->get_all_words();
		$this->response(array("status"=>200, "response"=>$all_word), 200);
	}

	function generate_random_fake_word() {
		$random_number = mt_rand(0, 250);
		return $this->fake_words_list[$random_number];
	}
	
	
	function get_random_answer_list($test_word = array())
	{
		$word_array = array();
		for ($i=0; $i < 4; $i++) { 
			if ($i == 0) {
				$answer_list = array(
					"content" => $test_word[0]['word'], 
					"id"=> (int)$test_word[0]['word_id']
				);
			} else {
				$answer_list = array(
					"content" => $this->generate_random_fake_word(), 
					"id"=>  ($test_word[0]['word_id'] + $i),
				);
			}
			array_push($word_array, $answer_list);
		}
		shuffle($word_array);
		return $word_array;
	}

	function get_first_test_word_post(){
		$first_id = $this->session->userdata('first_id');
		$random_id = rand($first_id, 10);
		$test_word = $this->get_word($random_id);
		$word_array = $this->get_random_answer_list($test_word);

		$response = array(
			"question"=> $test_word[0]['zh-CN'],
			"word_id" => $test_word[0]['word_id'],
			'answer_list' => $word_array,
			'total_count' => $this->dictionary_model->get_words_count(),
		);
		$this->response(array("status"=>"success", "new_word"=>$response), 200);
	}

	
	function get_next_test_word_post() {
		$current_test_result = '';
		$test_result = $this->post('test_result');
		$current_word_id = $this->post('current_word_id');
		$users_id = $this->post('users_id');

		$test_result_db = $this->dictionary_model->get_pretest_result($users_id);
		if (!empty($test_result_db)) {
			if ($test_result_db[0]['word_test_history'] != null) {
				$current_test_result = $test_result_db[0]['word_test_history'].$test_result;
			} else {
				$current_test_result = $test_result;
			}
			$data_for_wordtest = array(
				'word_test_history' => $current_test_result,
			);
			$this->dictionary_model->update_test_result($users_id, $data_for_wordtest);
		} else {
			$current_test_result = $test_result;
			$data_for_wordtest = array(
				'word_test_history' => $current_test_result,
				'users_id' => $users_id,
			);
			$this->dictionary_model->store_pretest_history($data_for_wordtest);
		}
		
		if ($this->word_test_stop($current_test_result, $this->pattern1_1) &&
			$this->word_test_stop($current_test_result, $this->pattern1_2) &&
			$this->word_test_stop($current_test_result, $this->pattern1_3) &&
			$this->word_test_stop($current_test_result, $this->pattern1_4) &&
			$this->word_test_stop($current_test_result, $this->patten2_1) &&
			$this->word_test_stop($current_test_result, $this->patten2_2) &&
			$this->word_test_stop($current_test_result, $this->patten2_3) &&
			$this->word_test_stop($current_test_result, $this->pattern3_1) ) {
			if ($test_result === "1") {
				/* test result is true */
				$rate_key = $this->get_rate_key($current_test_result, true);
				if ($rate_key >= count($this->increase_rate)) {
					$rate_key = count($this->increase_rate)-1;
				}
				
				$temp = floatval($current_word_id * $this->increase_rate[$rate_key]); 
				if($temp && intval($temp) != $temp) 
				{
				    // $num is a float
				    $temp += 1;
				    $temp = intval($temp); 
				}else {
				    // $num is an integer
				    $temp = $temp;
				}
				$next_word_id = $temp;
				if ($next_word_id > $this->session->userdata('lastest_id')){
					$next_word_id = $this->session->userdata('lastest_id') - 1;
					$this->response(array("status"=>"finish", 
							  "word_count"=>$this->dictionary_model->get_word_test_result($next_word_id), 
							  "word_total_count"=> $this->dictionary_model->get_words_count()), 200);					
				} else {
					$this->session->set_userdata('next_word_id', $next_word_id);
					$test_word = $this->get_word($next_word_id);
					$word_array = $this->get_random_answer_list($test_word);
					$response = array(
						"question"=> $test_word[0]['zh-CN'],
						"word_id" => $test_word[0]['word_id'],
						'answer_list' => $word_array,
						'total_count' => $this->dictionary_model->get_words_count(),
					);
					$this->response(array("status"=>"continue", "new_test_word"=>$response), 200);
				}
			} else {
				/* test result is false */
				$rate_key = $this->get_rate_key($current_test_result, false);
				if ($rate_key > count($this->decrease_rate)) {
					$rate_key = 0;
				}
				// if ($test_result_arr[count($test_result_arr)-2] == '1') {
				// 	$current_word_id = 	$this->session->userdata('next_word_id');	
				// }
				$temp = floatval($current_word_id * $this->decrease_rate[$rate_key]); 
				
				if($temp && intval($temp) != $temp) 
				{
				    // $num is a float
				    $temp += 1;
				    $temp = intval($temp); 
				}else {
				    // $num is an integer
				    $temp = $temp;
				}
				$next_word_id = $temp;
				if ($next_word_id * $this->increase_rate[$rate_key] < $this->session->userdata('first_id')){
					$next_word_id = $this->session->userdata('first_id') + 1;
				}
				$this->session->set_userdata('next_word_id', $next_word_id);
				$test_word = $this->get_word($next_word_id);
				$word_array = $this->get_random_answer_list($test_word);
				$response = array(
					"question"=> $test_word[0]['zh-CN'],
					"word_id" => $test_word[0]['word_id'],
					'answer_list' => $word_array,
					'total_count' => $this->dictionary_model->get_words_count(),
				);
				$this->response(array("status"=>"continue","new_test_word"=>$response), 200);
			}
		}
		$this->dictionary_model->delete_pretest_history($users_id);
		$this->response(array("status"=>"finish", 
							  "word_count"=>$this->dictionary_model->get_word_test_result($current_word_id), 
							  "word_total_count"=> $this->dictionary_model->get_words_count()), 200);
	}	
}