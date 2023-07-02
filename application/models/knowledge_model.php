<?php
class Knowledge_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_knowledge_data($users_id, $limit_start, $limit_count) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'word');
        $this->db->join('dictionary', 'dictionary.word_id = knowledges.w_g_id', 'left');
        $this->db->order_by('confidence', 'DESC');
        
        if($limit_count && $limit_start){
          $this->db->limit($limit_count, $limit_start);   
        }

        if($limit_count != null){
          $this->db->limit($limit_count, $limit_start);    
        }
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }


    public function format_confidence($knowledge_id=null)
    {
        $this->db->where('knowledge_id', $knowledge_id);
        $data = array("confidence"=> 0);
		$this->db->update('knowledges', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function get_user_w_g_count($users_id) {
        $this->db->select('w_g_flag, COUNT(*) as cnt', false);
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('confidence >', 0);
        $this->db->group_by('w_g_flag');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_w_g_count_learned($users_id, $words, $grammars) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $words);
        $this->db->where('w_g_flag', 'word');
        $this->db->where('confidence >', 0);
        $query = $this->db->get();
        $word_count = $query->num_rows();
        
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $grammars);
        $this->db->where('w_g_flag', 'grammar');
        $this->db->where('confidence >', 0);
        $query = $this->db->get();
        $grammar_count = $query->num_rows();
        
        $ret['word'] = $word_count;
        $ret['grammar'] = $grammar_count;
        return $ret;
    }


    public function get_grammar_confidence($users_id, $grammars_id) {
        $this->db->select('knowledges.confidence');
        $this->db->from('knowledges');
        $this->db->where('w_g_id', $grammars_id);
        $this->db->where('w_g_flag', 'grammar');
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_word_confidence($users_id, $word_id) {

        $this->db->select('knowledges.confidence');
        $this->db->from('knowledges');
        $this->db->where('w_g_id', $word_id);
        $this->db->where('w_g_flag', 'word');
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    function get_new_words_count($users_id, $word_id_list, $flag) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('w_g_flag', 'word');
        if ($flag === 'new') {
            $this->db->where('confidence =', 0);
        } else {
            $this->db->where('confidence >', 0);
        }
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $word_id_list);
        $query = $this->db->get();
        return $query->num_rows();     
    }

    function get_new_grammars_count($users_id, $grammar_id_list, $flag) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('w_g_flag', 'grammar');
        if ($flag === 'new') {
            $this->db->where('confidence =', 0);
        } else {
            $this->db->where('confidence >', 0);
        }
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $grammar_id_list);
        $query = $this->db->get();
        return $query->num_rows();     
    }

    public function update_word_confidence($users_id, $word_id, $update_confidence) {

        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_id', $word_id);
        $this->db->where('w_g_flag', 'word');
		$this->db->update('knowledges', $update_confidence);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function update_word_confidence_list($users_id, $update_confidence) {
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'word');
        $this->db->update_batch('knowledges', $update_confidence, 'w_g_id');
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function update_grammar_confidence_list($users_id, $update_confidence) {
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'grammar');
        $this->db->update_batch('knowledges', $update_confidence, 'w_g_id');
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function update_grammar_confidence($users_id, $grammar_id, $update_confidence) {

        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_id', $grammar_id);
        $this->db->where('w_g_flag', 'grammar');
		$this->db->update('knowledges', $update_confidence);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function create_knowledge_database($users_id, $word_count, $grammar_count){
        
        if ($word_count == 0 && $grammar_count == 0) {
            $word_sql = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT dictionary.word_id AS w_g_id,  CURDATE() as last_study_date, 0 as confidence, 0 as weight, 'word' as w_g_flag, " .$users_id. " as users_id FROM  dictionary";
            $query = $this->db->query($word_sql);
            $grammar_sql = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT grammar.grammar_id AS w_g_id,  CURDATE() as last_study_date, 0 as confidence, 0 as weight, 'grammar' as w_g_flag, " .$users_id . " as users_id FROM  grammar";
            $query = $this->db->query($grammar_sql);
        } else {
            $word_sql_0 = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT dictionary.word_id AS w_g_id,  CURDATE() as last_study_date, 0 as confidence, 0 as weight, 'word' as w_g_flag, " .$users_id. " as users_id FROM  dictionary WHERE dictionary.word_id > ".$word_count;
            $query = $this->db->query($word_sql_0);
            
            $word_sql_100 = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT dictionary.word_id AS w_g_id,  CURDATE() as last_study_date, 100 as confidence, 0 as weight, 'word' as w_g_flag, " .$users_id. " as users_id FROM  dictionary WHERE dictionary.word_id <= ".$word_count;
            $query = $this->db->query($word_sql_100);
            
            
            $grammar_sql_100 = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT grammar.grammar_id AS w_g_id,  CURDATE() as last_study_date, 0 as confidence, 0 as weight, 'grammar' as w_g_flag, " .$users_id . " as users_id FROM  grammar WHERE grammar.grammar_id >".$grammar_count;
            $query = $this->db->query($grammar_sql_100);

            $grammar_sql_0 = "INSERT INTO knowledges(w_g_id, last_study_date, confidence, weight, w_g_flag, users_id) 
                    SELECT grammar.grammar_id AS w_g_id,  CURDATE() as last_study_date, 100 as confidence, 0 as weight, 'grammar' as w_g_flag, " .$users_id . " as users_id FROM  grammar WHERE grammar.grammar_id <=".$grammar_count;
            $query = $this->db->query($grammar_sql_0);
        }

        return true;
	}

    public function get_knowledges_words($users_id) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'word');
        $this->db->where('confidence <', 100);
        $this->db->order_by('w_g_id', 'asc');
        $this->db->limit(10, 0);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }
    
    public function get_knowledges_grammars($users_id) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'grammar');
        $this->db->where('confidence <', 100);
        $this->db->order_by('w_g_id', 'asc');
        $this->db->limit(10, 0);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }

    public function get_smart_chapter_id($words, $grammars) {
        if (empty($grammars))
        {
           $sql = "SELECT chapters_id, courses_id, words, grammars FROM chapters WHERE (MATCH(words) AGAINST ('".$words."' IN BOOLEAN MODE)) LIMIT 1";
        }
        else {
           $sql = "SELECT chapters_id, courses_id, words, grammars FROM chapters WHERE (MATCH(grammars) AGAINST ('".$grammars."' IN BOOLEAN MODE)) AND (MATCH(words) AGAINST ('".$words."' IN BOOLEAN MODE)) LIMIT 1";
        }
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }


    function get_confidences_list_by_wordlist($users_id, $word_id_list) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('w_g_flag', 'word');
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $word_id_list);
        $query = $this->db->get();
        $result_array = $query->result_array();
        return $result_array;     
    }

    function get_confidences_list_by_grammarlist($users_id, $grammar_id_list) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('w_g_flag', 'grammar');
        $this->db->where('users_id', $users_id);
        $this->db->where_in('w_g_id', $grammar_id_list);
        $query = $this->db->get();
        $result_array = $query->result_array();
        return $result_array;    
    }

    function get_knowledge_statistic_data($users_id, $week_ago) {
        $ret = array();
        $this->db->select('DATE(last_study_date) as date, COUNT(*) as cnt');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'word');
        //$this->db->where('last_study_date >=', $week_ago);
        $this->db->where('confidence >', 0);
        $this->db->group_by('last_study_date');
        $query = $this->db->get();
        $word_data = $query->result_array();
        $ret['word_data'] = $word_data;

        $this->db->select('DATE(last_study_date) as date, COUNT(*) as cnt');
        $this->db->from('knowledges');
        $this->db->where('users_id', $users_id);
        $this->db->where('w_g_flag', 'grammar');
        //$this->db->where('last_study_date >=', $week_ago);
        $this->db->where('confidence >', 0);
        $this->db->group_by('last_study_date');
        $query = $this->db->get();
        $grammar_data = $query->result_array();
        $ret['grammar_data'] = $grammar_data;

        return $ret;
    }

}