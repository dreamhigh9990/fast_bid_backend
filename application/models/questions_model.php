<?php
class Questions_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_questions_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->where('questions_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('questions');
    }

    public function get_questions($questions_id_like=null, $questions_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('questions');

        if($questions_id_like){
            $this->db->like('questions_id', $questions_id_like);
        }
        if($questions_template_like){
            $this->db->like('template', $questions_template_like);
        }

        if ($sort){
            $this->db->order_by($sort, $order);
        }

        if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end);   
        }

        if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
        
        $query = $this->db->get();
        
        $result_array = $query->result_array();     

        return $result_array;
    }

    function count_questions($questions_id_like=null, $questions_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('questions');
        if($questions_id_like){
            $this->db->like('questions_id', $questions_id_like);
        }
        if($questions_template_like){
            $this->db->like('template', $questions_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_question($data)
    {
        $this->db->insert('questions', $data);
        return $this->db->insert_id();
    }

    function update_question($questions_id, $data)
    {
        $this->db->where('questions_id', $questions_id);
        $this->db->update('questions', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_question($id)
    {
        $this->db->where('questions_id', $id);
        $this->db->delete('questions');
    }
}