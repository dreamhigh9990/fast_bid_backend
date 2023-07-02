<?php
class Messages_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_messages_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->where('messages_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('messages');
    }

    public function get_messages($messages_id_like=null, $message_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('messages');

        if($messages_id_like){
            $this->db->like('messages_id', $messages_id_like);
        }
        if($message_like){
            $this->db->like('message', $message_like);
        }

        if ($sort){
            $this->db->order_by($sort, $order);
        } else {
            $this->db->order_by('created_date', 'desc');
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

    function count_messages($messages_id_like=null, $message_like=null)
    {
        $this->db->select('*');
        $this->db->from('messages');
        if($messages_id_like){
            $this->db->like('messages_id', $messages_id_like);
        }
        if($message_like){
            $this->db->like('message', $message_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_unread_messages()
    {
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->where('read_date IS NULL');
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_message($data)
    {
        $this->db->insert('messages', $data);
        return $this->db->insert_id();
    }

    function update_message($messages_id, $data)
    {
        $this->db->where('messages_id', $messages_id);
        $this->db->update('messages', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_all_message($data) {
        $this->db->where('read_date is NULL');
        $this->db->update('messages', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_message($id)
    {
        $this->db->where('messages_id', $id);
        $this->db->delete('messages');
    }
}