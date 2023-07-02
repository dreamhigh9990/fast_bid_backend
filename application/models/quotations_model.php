<?php
class Quotations_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_quotations_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('quotations');
		$this->db->where('quotations_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('quotations');
    }

    public function get_quotations($quotations_id_like=null, $quotations_template_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('quotations');

        if($quotations_id_like){
            $this->db->like('quotations_id', $quotations_id_like);
        }
        if($quotations_template_like){
            $this->db->like('template', $quotations_template_like);
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

    function count_quotations($quotations_id_like=null, $quotations_template_like=null)
    {
        $this->db->select('*');
        $this->db->from('quotations');
        if($quotations_id_like){
            $this->db->like('quotations_id', $quotations_id_like);
        }
        if($quotations_template_like){
            $this->db->like('template', $quotations_template_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_quotation($data)
    {
        $this->db->insert('quotations', $data);
        return $this->db->insert_id();
    }

    function update_quotation($quotations_id, $data)
    {
        $this->db->where('quotations_id', $quotations_id);
        $this->db->update('quotations', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_quotation($id)
    {
        $this->db->where('quotations_id', $id);
        $this->db->delete('quotations');
    }
}