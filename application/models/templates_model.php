<?php
class Templates_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_templates_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('templates');
		$this->db->where('templates_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('templates');
    }

    public function get_templates($templates_id_like=null, $templates_name_like=null, $templates_text_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('templates');

        if($templates_id_like){
            $this->db->where('templates_id', $templates_id_like);
        }
        if($templates_name_like){
            $this->db->like('name', $templates_name_like);
        }
        if($templates_text_like){
            $this->db->like('text', $templates_text_like);
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

    function count_templates($templates_id_like=null, $templates_name_like=null, $templates_text_like=null)
    {
        $this->db->select('*');
        $this->db->from('templates');
        if($templates_id_like){
            $this->db->where('templates_id', $templates_id_like);
        }
        if($templates_name_like){
            $this->db->like('name', $templates_name_like);
        }
        if($templates_text_like){
            $this->db->like('text', $templates_text_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_template($data)
    {
        $this->db->insert('templates', $data);
        return $this->db->insert_id();
    }

    function update_template($templates_id, $data)
    {
        $this->db->where('templates_id', $templates_id);
        $this->db->update('templates', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_template($id)
    {
        $this->db->where('templates_id', $id);
        $this->db->delete('templates');
    }
}