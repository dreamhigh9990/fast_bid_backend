<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class grammar_test extends REST_Controller
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
	
	public function __construct() {
		parent::__construct();
		$this->load->model('grammartest_model');
		$this->load->model('dictionary_model');
		$this->load->model('review_model'); 
		$this->load->model('knowledge_model');    
	}
	
	/* Check pattern */
	function grammar_test_stop($test_result, $pattern) {
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

	
	function get_grammar_test_problem($grammar_test_id) {
		return $this->grammartest_model->get_grammar_test_question($grammar_test_id);
	}

	function set_knowledge_confidence_post() {
		$words_count = $this->post('words_count');
		$grammars_count = $this->post('grammars_count');
		$users_id = $this->post('users_id');
		
		$first_id = $this->dictionary_model->get_first_id();
		$words_confidences_data = array();
		$grammars_confidences_data = array();

		for ($i = (int)$first_id[0]['word_id']; $i <= $words_count; $i++) {

			 $words_confidences_data[] = array(
				'confidence'=>100,
				'w_g_id' => $i
			);
		}
		$word_ret = $this->knowledge_model->update_word_confidence_list($users_id, $words_confidences_data);
		for ($i = 1; $i <= $grammars_count; $i++) { 
			$grammars_confidences_data[] = array(
				'confidence'=>100,
				'w_g_id' => $i
			);
		}
		$grammars_ret = $this->knowledge_model->update_grammar_confidence_list($users_id, $grammars_confidences_data);
		$this->response(array("status"=>200, "words_ret"=>$word_ret, "grammar_ret"=>$grammars_ret),200);
	}

	function get_first_grammar_test_problem_post() {
		 
		 $temp_arr = array();
		 $id = rand(1, 10);
		 $grammar_first_id = $this->grammartest_model->get_random_id($id);
		 $test_grammar = $this->grammartest_model->get_grammar_test_question($grammar_first_id[0]['grammar_test_id']);
		 for ($i=0; $i < 4; $i++) { 
		 	if ($i == 0 ) {
		 		array_push($temp_arr, array("id"=>1, "content"=>$test_grammar[0]["answer_1"]));
		 	}
		 	else {
		 		array_push($temp_arr, array("id"=>$i+1, "content"=>$test_grammar[0]["answer_".($i+1)]));
		 	}
		 }
		 shuffle($temp_arr);
		 $grammar_test_count = $this->grammartest_model->get_grammar_test_count();
		 $response = array(
			"question"=> $test_grammar[0]['question'],
			"grammar_test_id" => $test_grammar[0]['grammar_test_id'],
			'answer_list' => $temp_arr,
			"grammar_id" => $test_grammar[0]['grammar_id'],
			'total_count' => $grammar_test_count,
		);

		 $this->response(array("status"=>400, "grammar_test_problem"=> $response), 200);
	}

	

	function get_next_grammar_test_problem_post() {
		
		$temp_arr = array();	
		$current_test_result = '';
		
		$grammar_test_id = $this->post('grammar_test_id');
		$grammar_id = $this->post('grammar_id');
		$test_result = $this->post('answer_result');
		$users_id = $this->post('users_id');

		$test_result_db = $this->dictionary_model->get_pretest_result($users_id);
		
		if (!empty($test_result_db[0])) {
			if ($test_result_db[0]['grammar_test_history'] != null) {
				$current_test_result = $test_result_db[0]['grammar_test_history'].$test_result;
			} else {
				$current_test_result = $test_result;
			}
			$data_for_grammartest = array(
				'grammar_test_history' => $current_test_result,
			);
			$this->dictionary_model->update_test_result($users_id, $data_for_grammartest);
		} else {
			$current_test_result = $test_result;
			$data_for_grammartest = array(
				'grammar_test_history' => $current_test_result,
				'users_id' => $users_id,
			);
			$this->dictionary_model->store_pretest_history($data_for_grammartest);
		}

		$grammar_test_count = $this->grammartest_model->get_grammar_test_count();
		$grammar_first_id = $this->grammartest_model->get_first_id();
		$grammar_last_id = $this->grammartest_model->get_lastest_id();
		
		if ($this->grammar_test_stop($current_test_result, $this->pattern1_1) &&
			$this->grammar_test_stop($current_test_result, $this->pattern1_2) &&
			$this->grammar_test_stop($current_test_result, $this->pattern1_3) &&
			$this->grammar_test_stop($current_test_result, $this->pattern1_4) &&
			$this->grammar_test_stop($current_test_result, $this->patten2_1) &&
			$this->grammar_test_stop($current_test_result, $this->patten2_2) &&
			$this->grammar_test_stop($current_test_result, $this->patten2_3) &&
			$this->grammar_test_stop($current_test_result, $this->pattern3_1)) {
			if ($test_result === "1") {
				$rate_key = $this->get_rate_key($current_test_result, true);
				if ($rate_key >= count($this->increase_rate)) {
					$rate_key = count($this->increase_rate)-1;
				}
				$temp = floatval($grammar_test_id * $this->increase_rate[$rate_key]); 
				if($temp && intval($temp) != $temp) 
				{
				    // $num is a float
				    $temp += 1;
				    $temp = intval($temp); 
				}else {
				    // $num is an integer
				    $temp = $temp;
				}
				$next_grammars_id = $temp;
				if ($next_grammars_id > $grammar_last_id[0]['grammar_test_id']){
					$next_grammars_id = $grammar_last_id[0]['grammar_test_id'] - 1;
					
					$this->dictionary_model->delete_pretest_history($users_id);
					$this->response(array("status"=>"finish", "grammar_count"=>(int)($next_grammars_id), 
							"grammar_total_count"=> (int)$grammar_test_count), 200);
				} else {
					$grammar_test_problem = $this->get_grammar_test_problem($next_grammars_id);
					for ($i=0; $i < 4; $i++) { 
						if ($i == 0 ) {
							array_push($temp_arr, array("id"=>1, "content"=>$grammar_test_problem[0]["answer_1"]));
						}
						else {
							array_push($temp_arr, array("id"=>$i+1, "content"=>$grammar_test_problem[0]["answer_".($i+1)]));
						}
					}
					
					shuffle($temp_arr);
					$response = array(
						"question"=> $grammar_test_problem[0]['question'],
						"grammar_test_id" => $grammar_test_problem[0]['grammar_test_id'],
						'answer_list' => $temp_arr,
						"grammar_id" => $grammar_test_problem[0]['grammar_id'],
						'total_count' => (int)$grammar_test_count,
						'$grammar_last_id' =>$grammar_last_id
					);
				
					$this->response(array("status"=>"continue", "grammar_test_problem"=>$response), 200);
				}
			} else {
				$rate_key = $this->get_rate_key($current_test_result, false);
				if ($rate_key > count($this->decrease_rate)) {
					$rate_key = 0;
				}
				$temp = floatval($grammar_test_id * $this->decrease_rate[$rate_key]); 
				
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
				if ($next_word_id < $grammar_first_id[0]['grammar_test_id']){
					$next_word_id = $grammar_first_id[0]['grammar_test_id'] + 1;
				}
				$grammar_test_problem = $this->get_grammar_test_problem($next_word_id);
				for ($i=0; $i < 4; $i++) { 
					if ($i == 0 ) {
						array_push($temp_arr, array("id"=>1, "content"=>$grammar_test_problem[0]["answer_1"]));
					}
					else {
						array_push($temp_arr, array("id"=>$i+1, "content"=>$grammar_test_problem[0]["answer_".($i+1)]));
					}
				}
				shuffle($temp_arr);
				$response = array(
					"question"=> $grammar_test_problem[0]['question'],
					"grammar_test_id" => $grammar_test_problem[0]['grammar_test_id'],
					'answer_list' => $temp_arr,
					"grammar_id" => $grammar_test_problem[0]['grammar_id'],
					'total_count' => (int)$grammar_test_count,
				);
			
				$this->response(array("status"=>"continue", "grammar_test_problem"=>$response), 200);
			}
		} else {
			$this->dictionary_model->delete_pretest_history($users_id);
			$this->response(array("status"=>"finish", "grammar_count"=>(int)($grammar_test_id), 
					"grammar_total_count"=> (int)$grammar_test_count), 200);
		}
        
	}
}