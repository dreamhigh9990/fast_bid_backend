<?php
class Viewed_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_viewed_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('viewed');
        $this->db->where('viewed_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_viewed_by_users_id($users_id)
    {
        $this->db->select('*');
        $this->db->from('viewed');
        $this->db->where('users_id', $users_id);
        $this->db->order_by('date', 'Asc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function store_viewed($data)
    {
        $this->db->insert('viewed', $data);
        return $this->db->insert_id();
    }
}