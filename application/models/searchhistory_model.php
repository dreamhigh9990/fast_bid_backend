<?php
class Searchhistory_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_total_search_count($users_id) {
        $this->db->select('*');
		$this->db->from('search_history');
		$this->db->where('users_id', $users_id);
        $query = $this->db->get();
		return $query->num_rows(); 
    }

      public function get_recent_search_history($users_id, $limit_start=null, $limit_count=null) {
        $this->db->select('*');
		$this->db->from('search_history');
		$this->db->where('users_id', $users_id);
        $this->db->order_by('date');
        $this->db->limit($limit_count, $limit_start);
        $query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_search_user_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(DISTINCT(users_id)) as co_cnt');
        $this->db->from('search_history');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }
    
    public function get_search_total_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('search_history');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }
}