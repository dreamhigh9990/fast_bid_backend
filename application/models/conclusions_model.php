<?php
class Conclusions_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_conclusions_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('conclusions');
		$this->db->where('conclusions_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('conclusions');
    }

    public function get_conclusions($conclusions_id_like=null, $conclusions_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('conclusions');

        if($conclusions_id_like){
            $this->db->like('conclusions_id', $conclusions_id_like);
        }
        if($conclusions_template_like){
            $this->db->like('template', $conclusions_template_like);
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

    function count_conclusions($conclusions_id_like=null, $conclusions_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('conclusions');
        if($conclusions_id_like){
            $this->db->like('conclusions_id', $conclusions_id_like);
        }
        if($conclusions_template_like){
            $this->db->like('template', $conclusions_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_conclusion($data)
    {
        $this->db->insert('conclusions', $data);
        return $this->db->insert_id();
    }

    function update_conclusion($conclusions_id, $data)
    {
        $this->db->where('conclusions_id', $conclusions_id);
        $this->db->update('conclusions', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_conclusion($id)
    {
        $this->db->where('conclusions_id', $id);
        $this->db->delete('conclusions');
    }
}