<?php
class Like_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_liked_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('liked');
		$this->db->where('liked_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    /**
    * Fetch manufacturers data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_liked_by_users_id($users_id)
    {
		$this->db->select('*');
		$this->db->from('liked');
        $this->db->where('users_id', $users_id);
		$query = $this->db->get();
		$result_array = $query->result_array(); 	
        return $result_array;
    }

    public function store_liked($data)
    {
        $this->db->insert('liked', $data);
        return $this->db->insert_id();
    }

    public function delete_liked($data)
    {
        $this->db->where($data);
        $this->db->delete('liked');
    }

    public function get_liked_count($users_id)
    {
        $this->db->select('*');
        $this->db->from('liked');
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
}