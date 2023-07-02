<?php
class Dictionary_model extends CI_Model {

    private $table = 'dictionary';
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_all_words()
    {
		$this->db->select('*');
		$this->db->from('dictionary');
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_word($word_id)
    {
		$this->db->select('*');
        $this->db->from('dictionary');
        $this->db->where('word_id', $word_id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_word_id($word)
    {
		$this->db->select('*');
        $this->db->from('dictionary');
        $this->db->where('word', $word);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_lastest_id() {
        $this->db->select('*');
        $this->db->from('dictionary');
        $this->db->order_by('word_id','desc');
        $query = $this->db->get();
		return $query->result_array();
    }
    
    public function get_first_id() {
        $this->db->select('*');
        $this->db->from('dictionary');
        $this->db->order_by('word_id','asc');
        $query = $this->db->get();
		return $query->result_array();
    }

     public function get_word_test_result($word_id) {
        $this->db->select('*');
        $this->db->from('dictionary');
        $this->db->where('word_id <=', $word_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_pretest_result($users_id) {
        $this->db->select('*');
        $this->db->from('pretest_history');
        $this->db->where('users_id', $users_id); 
        $query = $this->db->get();
		return $query->result_array();
    }

    public function store_pretest_history($data)
    {
		$insert = $this->db->insert('pretest_history', $data);
	    return $this->db->insert_id();
	}

    public function update_test_result($users_id, $update_data) {
        $this->db->where('users_id', $users_id);
		$this->db->update('pretest_history', $update_data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function update_grammartest_result($users_id, $update_data) {
        $this->db->where('users_id', $users_id);
		$this->db->update('pretest_history', $update_data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function get_words_count() {
        $this->db->select('*');
        $this->db->from('dictionary');
        $query = $this->db->get();
        return $query->num_rows();
    }

     /**
    * Delete manufacturer
    * @param int $id - manufacture id
    * @return boolean
    */

	function delete_pretest_history($id){
		$this->db->where('users_id', $id);
		$this->db->delete('pretest_history'); 
	}

    
}