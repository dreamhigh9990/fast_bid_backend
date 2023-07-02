<?php
class Urls_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_urls_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('urls');
		$this->db->where('urls_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('urls');
    }

    public function get_urls($urls_id_like=null, $urls_url_like=null, $urls_description_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('urls');

        if($urls_id_like){
            $this->db->like('urls_id', $urls_id_like);
        }
        if($urls_url_like){
            $this->db->like('url', $urls_url_like);
        }
        if($urls_description_like){
            $this->db->like('description', $urls_description_like);
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

    function count_urls($urls_id_like=null, $urls_url_like=null, $urls_description_like=null)
    {
        $this->db->select('*');
        $this->db->from('urls');
        if($urls_id_like){
            $this->db->like('urls_id', $urls_id_like);
        }
        if($urls_url_like){
            $this->db->like('url', $urls_url_like);
        }
        if($urls_description_like){
            $this->db->like('description', $urls_description_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_url($data)
    {
        $this->db->insert('urls', $data);
        return $this->db->insert_id();
    }

    function update_url($urls_id, $data)
    {
        $this->db->where('urls_id', $urls_id);
        $this->db->update('urls', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_url($id)
    {
        $this->db->where('urls_id', $id);
        $this->db->delete('urls');
    }
}