<?php
class Experiences_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_experiences_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('experiences');
		$this->db->where('experiences_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('experiences');
    }

    public function get_experiences($experiences_id_like=null, $experiences_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('experiences');

        if($experiences_id_like){
            $this->db->like('experiences_id', $experiences_id_like);
        }
        if($experiences_template_like){
            $this->db->like('template', $experiences_template_like);
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

    function count_experiences($experiences_id_like=null, $experiences_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('experiences');
        if($experiences_id_like){
            $this->db->like('experiences_id', $experiences_id_like);
        }
        if($experiences_template_like){
            $this->db->like('template', $experiences_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_experience($data)
    {
        $this->db->insert('experiences', $data);
        return $this->db->insert_id();
    }

    function update_experience($experiences_id, $data)
    {
        $this->db->where('experiences_id', $experiences_id);
        $this->db->update('experiences', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_experience($id)
    {
        $this->db->where('experiences_id', $id);
        $this->db->delete('experiences');
    }
}