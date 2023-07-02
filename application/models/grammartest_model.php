<?php
class Grammartest_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_grammar_test_question($grammar_test_id)
    {
		$this->db->select('*');
		$this->db->from('grammar_test');
		$this->db->where('grammar_test_id', $grammar_test_id);
        $query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_first_id() {
        $this->db->select('*');
        $this->db->from('grammar_test');
        $this->db->order_by('grammar_test_id','asc');
        $this->db->limit(1, 0);
        $query = $this->db->get();
		return $query->result_array();
    }

    public function get_random_id($id) {
        $this->db->select('*');
        $this->db->from('grammar_test');
        $this->db->where('grammar_test_id',$id);
        $query = $this->db->get();
		return $query->result_array();
    }

    public function get_lastest_id() {
        $this->db->select('*');
        $this->db->from('grammar_test');
        $this->db->order_by('grammar_test_id','desc');
        $this->db->limit(1, 0);
        $query = $this->db->get();
		return $query->result_array();
    }

    public function get_grammar_test_count() {
        $this->db->select('*');
        $this->db->from('grammar_test');
        $query = $this->db->get();
        return $query->num_rows();   
    }

    public function get_grammar_total_count() {
        $this->db->select('*');
        $this->db->from('grammar');
        $query = $this->db->get();
        return $query->num_rows();   
    }

    public function get_grammar_correct_answer_count() {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('confidence', 100);
        $this->db->where('confidence', 'grammar');
        $query = $this->db->get();
        return $query->num_rows();   
    }

    public function set_knowledge_grammar_confidence($grammar_id, $confidence) {
        $this->db->select('*');
        $this->db->from('knowledges');
        $this->db->where('w_g_id', $grammar_id);
        $this->db->where('w_g_flag', "grammar");
        $this->db->update('confidence', $confidence);
    
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }


    public function get_grammars_count() {
        $this->db->select('*');
        $this->db->from('grammar');
        $query = $this->db->get();
        return $query->num_rows(); 
    }

    public function get_all_grammars() {
        $this->db->select('*');
        $this->db->from('grammar');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }


    public function build_grammar_test() {
        $this->db->select('*');
        $this->db->from('grammar');
        $query = $this->db->get();
        $grammars = $query->result_array(); 


        foreach ($grammars as $key => $grammar) {
            $sql = "SELECT `zh-CN`, en FROM sentences WHERE (MATCH(grammars) AGAINST ('".$grammar['grammar_id']."')) LIMIT 1;";
            $query = $this->db->query($sql);
            $sentences = $query->result_array();
            $correct_sentence = $sentences[0];

            $random_grammar_ids = mt_rand(1,500).",".mt_rand(1,500).",".mt_rand(1,500).",".mt_rand(1,500);
            $sql = "
                SELECT en FROM sentences WHERE (MATCH(grammars) AGAINST ('".$random_grammar_ids." in boolean mode')) LIMIT 4;
                ";
            $query = $this->db->query($sql);
            $candidate_sentences = $query->result_array();

            $sql = "INSERT INTO `grammar_test` (
                  `grammar_test_id`,
                  `grammar_id`,
                  `question`,
                  `answer_1`,
                  `answer_2`,
                  `answer_3`,
                  `answer_4`,
                  `answer_5`
                ) 
                VALUES
                  (
                    NULL,
                    '".$grammar['grammar_id']."',
                    '".str_replace("'", "\\'", $correct_sentence['zh-CN'])."',
                    '".str_replace("'", "\\'", $correct_sentence['en'])."',
                    '".str_replace("'", "\\'", $candidate_sentences[0]['en'])."',
                    '".str_replace("'", "\\'", $candidate_sentences[1]['en'])."',
                    '".str_replace("'", "\\'", $candidate_sentences[2]['en'])."',
                    '".str_replace("'", "\\'", $candidate_sentences[3]['en'])."'
                  ) ;";
            $query = $this->db->query($sql);
        }
    }
}