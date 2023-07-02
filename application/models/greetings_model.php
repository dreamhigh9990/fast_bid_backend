<?php
class Greetings_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_greetings_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('greetings');
		$this->db->where('greetings_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('greetings');
    }

    public function get_greetings($greetings_id_like=null, $greetings_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('greetings');

        if($greetings_id_like){
            $this->db->like('greetings_id', $greetings_id_like);
        }
        if($greetings_template_like){
            $this->db->like('template', $greetings_template_like);
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

    function count_greetings($greetings_id_like=null, $greetings_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('greetings');
        if($greetings_id_like){
            $this->db->like('greetings_id', $greetings_id_like);
        }
        if($greetings_template_like){
            $this->db->like('template', $greetings_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_greeting($data)
    {
        $this->db->insert('greetings', $data);
        return $this->db->insert_id();
    }

    function update_greeting($greetings_id, $data)
    {
        $this->db->where('greetings_id', $greetings_id);
        $this->db->update('greetings', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_greeting($id)
    {
        $this->db->where('greetings_id', $id);
        $this->db->delete('greetings');
    }
}