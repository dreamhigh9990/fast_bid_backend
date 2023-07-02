<?php
class Learninghistory_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_learning_history($users_id) {
        $this->db->select('smart_flag, COUNT(*) as chaper_cnt, SUM(chapter_sentences_count) as sentences_cnt, GROUP_CONCAT(words SEPARATOR ",") as words_cnt, GROUP_CONCAT(grammars SEPARATOR ",") as grammars_cnt', false);
		$this->db->from('learning_history');
		$this->db->where('users_id', $users_id);
        $this->db->group_by('smart_flag');
        $query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_courses_learned($users_id) {
        $this->db->select('courses_id, (select COUNT(*) from learning_history where success_flag=1) as chaper_cnt, SUM(chapter_sentences_count) as sentences_cnt, GROUP_CONCAT(words SEPARATOR ",") as words_cnt, GROUP_CONCAT(grammars SEPARATOR ",") as grammars_cnt', false);
		$this->db->from('learning_history');
		$this->db->where('users_id', $users_id);
        $this->db->where('smart_flag', 0);
        $this->db->group_by('courses_id');
        $query = $this->db->get();
		return $query->result_array();
    }

    public function store_learning_chapter_history($new_learning_history_data) {
        $insert = $this->db->insert('learning_history', $new_learning_history_data);
	    return $insert;
    }

    public function update_learning_chapter_history($users_id, $chapters_id, $update_learning_history_data) {
        $this->db->where('users_id', $users_id);
        $this->db->where('chapters_id', $chapters_id);
		$this->db->update('learning_history', $update_learning_history_data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function update_chapter_confirm($users_id, $chapters_id, $courses_flag) {
        $flag = $courses_flag == 'smart' ? 1: 0;
        $update_data['success_flag'] = 1;
		
        $this->db->where('users_id', $users_id);
        $this->db->where('chapters_id', $chapters_id);
        $this->db->where('smart_flag', $flag);
        $this->db->update('learning_history', $update_data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }

    public function check_learning_chapter_history($users_id, $chapters_id, $success_flag=null) {
        $this->db->select('*');
        $this->db->from('learning_history');
        $this->db->where('users_id', $users_id);
        $this->db->where('chapters_id', $chapters_id);
        if ($success_flag) {
            $this->db->where('success_flag', 1);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_user_acive_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(DISTINCT(users_id)) as u_cnt');
        $this->db->from('learning_history');
        $this->db->where('success_flag', 1);
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
		return $query->result_array();
    }

    function get_user_smart_course_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(DISTINCT(users_id)) as u_cnt');
        $this->db->from('learning_history');
        $this->db->where('smart_flag', 1);
        $this->db->where('success_flag', 1);
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
		return $query->result_array();
    }
   
}