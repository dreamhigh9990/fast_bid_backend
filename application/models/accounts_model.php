<?php
class Accounts_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_accounts_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('accounts_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('accounts');
    }

    public function get_accounts($accounts_id_like=null, $accounts_user_name_like=null, $accounts_full_name_like=null, $accounts_email_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('accounts');

        if($accounts_id_like){
            $this->db->like('accounts_id', $accounts_id_like);
        }
        if($accounts_user_name_like){
            $this->db->like('user_name', $accounts_user_name_like);
        }
        if($accounts_full_name_like){
            $this->db->like('full_name', $accounts_full_name_like);
        }
        if($accounts_email_like){
            $this->db->like('email', $accounts_email_like);
        }
        $this->db->group_by('accounts_id');

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

    function count_accounts($accounts_id_like=null, $accounts_user_name_like=null, $accounts_full_name_like=null, $accounts_email_like=null)
    {
        $this->db->select('*');
        $this->db->from('accounts');
        if($accounts_id_like){
            $this->db->like('accounts_id', $accounts_id_like);
        }
        if($accounts_user_name_like){
            $this->db->like('user_name', $accounts_user_name_like);
        }
        if($accounts_full_name_like){
            $this->db->like('full_name', $accounts_full_name_like);
        }
        if($accounts_email_like){
            $this->db->like('email', $accounts_email_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_account($data)
    {
        $this->db->insert('accounts', $data);
        return $this->db->insert_id();
    }

    function update_account($accounts_id, $data)
    {
        $this->db->where('accounts_id', $accounts_id);
        $this->db->update('accounts', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_account_by_user_name($user_name, $data)
    {
        $this->db->where('user_name', $user_name);
        $this->db->update('accounts', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete_account($id)
    {
        $this->db->where('accounts_id', $id);
        $this->db->delete('accounts');
    }
}