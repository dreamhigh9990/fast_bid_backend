<?php
class Answers_model extends CI_Model {

  /**
  * Responsable for auto load the database
  * @return void
  */

  private $table = 'answers';
  public function __construct()
  {
      $this->load->database();
  }

  /**
  * Get product by his is
  * @param int $product_id
  * @return array
  */
  public function get_answers_by_id($id)
  {
    $this->db->select('a.*, u.user_name as answerer_name');
    $this->db->from('answers a');
    $this->db->join('users u', 'u.users_id=a.answerer_id');
    $this->db->where('answers_id', $id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function get_answers($question_id) {
    $this->db->select('a.*, u.user_name as answerer_name');
    $this->db->from('answers a');
    $this->db->join('users u', 'u.users_id=a.answerer_id');
    $this->db->where('questions_id', $question_id);
    $query = $this->db->get();
    $result_array = $query->result_array();
    return $result_array;
  }

  function get_answers_by_users_id($users_id, $question_id) {
    $this->db->select('a.*, u.user_name as answerer_name, (select count(*)>0 from review r where r.`answers_id`=a.`answers_id` and r.users_id='.$users_id.') as reviewed_by_user, (select count(*)>0 from liked l where l.`answers_id`=a.`answers_id` and l.users_id='.$users_id.') as liked_by_user');
    $this->db->from('answers as a, users as u');
    $this->db->where('u.users_id=a.answerer_id', null, false);
    $this->db->where('questions_id', $question_id);
    $query = $this->db->get();
    $result_array = $query->result_array();
    return $result_array;
  }

  function store_answers($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
	}

  function update_answers($answers_id, $data)
  {
    $this->db->where('answers_id', $answers_id);
    $this->db->update('answers', $data);
    $report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    if ($report !== 0) {
        return true;
    } else {
        return false;
    }
	}

	function delete_answer($id){
		$this->db->where('answers_id', $id);
		$this->db->delete($this->table);
	}
 
  function increase_review_count($answers_id)
  {
    $this->db->set('reviewed_count', 'reviewed_count + ' . 1, FALSE);
    $this->db->where('answers_id', $answers_id);
    $this->db->update($this->table);
    $report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    if($report !== 0){
      return true;
    }else{
      return false;
    }
  }

  function decrease_review_count($answers_id)
  {
    $this->db->set('reviewed_count', 'reviewed_count - ' . 1, FALSE);
    $this->db->where('answers_id', $answers_id);
    $this->db->update($this->table);
    $report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    if($report !== 0){
      return true;
    }else{
      return false;
    }
  }

  function increase_liked_count($answers_id)
  {
    $this->db->set('liked_count', 'liked_count + ' . 1, FALSE);
    $this->db->where('answers_id', $answers_id);
    $this->db->update($this->table);
    $report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    if($report !== 0){
      return true;
    }else{
      return false;
    }
  }

  function decrease_liked_count($answers_id)
  {
    $this->db->set('liked_count', 'liked_count - ' . 1, FALSE);
    $this->db->where('answers_id', $answers_id);
    $this->db->update($this->table);
    $report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    if($report !== 0){
      return true;
    }else{
      return false;
    }
  }

  function count_answers_by_user($users_id)
    {
        $this->db->select('*');
        $this->db->from('answers');
        $this->db->where('answerer_id', $users_id);
        $query = $this->db->get();
        return $query->num_rows();        
    }

    public function get_recent_answers_history($users_id, $limit_start=null, $limit_count=null) {
        $this->db->select('answer, answers_id, questions_id');
        $this->db->from('answers');
        $this->db->where('answerer_id', $users_id);
        $this->db->order_by('date', 'Desc');
        $this->db->limit($limit_count, $limit_start);
        $query = $this->db->get();
	  	  return $query->result_array(); 
    }

  public function get_answer_count_per_day() {
      $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
      $this->db->from('answers');
      $this->db->group_by('DATE(date)');
      $query = $this->db->get();
      $result_array = $query->result_array();     
      return $result_array;
  }

}