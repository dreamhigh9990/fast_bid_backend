<?php
class Introduces_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_introduces_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('introduces');
		$this->db->where('introduces_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('introduces');
    }

    public function get_introduces($introduces_id_like=null, $introduces_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('introduces');

        if($introduces_id_like){
            $this->db->like('introduces_id', $introduces_id_like);
        }
        if($introduces_template_like){
            $this->db->like('template', $introduces_template_like);
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

    function count_introduces($introduces_id_like=null, $introduces_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('introduces');
        if($introduces_id_like){
            $this->db->like('introduces_id', $introduces_id_like);
        }
        if($introduces_template_like){
            $this->db->like('template', $introduces_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_introduce($data)
    {
        $this->db->insert('introduces', $data);
        return $this->db->insert_id();
    }

    function update_introduce($introduces_id, $data)
    {
        $this->db->where('introduces_id', $introduces_id);
        $this->db->update('introduces', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_introduce($id)
    {
        $this->db->where('introduces_id', $id);
        $this->db->delete('introduces');
    }
}