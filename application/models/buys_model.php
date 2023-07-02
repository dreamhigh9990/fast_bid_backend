<?php
class Buys_model extends CI_Model {
 
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
    public function get_buys_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('buys');
		$this->db->where('buys_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function get_offers_by_users_id($users_id, $status, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('buys');
        $this->db->where('users_id', $users_id);
        $this->db->where('status', $status);

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('users_id', $order_type);
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

    public function get_users_by_offers_id($offers_id, $status, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    $strQuery = "SELECT buys.*, users.name, users.email FROM buys, users WHERE buys.offers_id='$offers_id' AND buys.users_id=users.user_id AND buys.status='$status'";
        
        if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end);   
        }

        if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
        $query = $this->db->query($strQuery);
        return $query->result_array();
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_buys($data)
    {
		$insert = $this->db->insert('buys', $data);
	    return $insert;
	}

    function add_offers_cart($users_id, $offers_id)
    {
        $data = array("users_id"=> $users_id, "offers_id"=> $offers_id, "status"=>1, "date"=>date("Y-m-d H:i:s"), "quantity"=> 1);
        return $this->store_buys($data);
    }

    function pend_offers($buys_id, $quantity=1)
    {
        $data = array("status"=>2, "date"=>date("Y-m-d H:i:s"), "quantity"=> $quantity);
        return $this->update_buys($buys_id, $data);
    }

    function cancel_pend_offers($buys_id)
    {
        $data = array("status"=>1, "date"=>date("Y-m-d H:i:s"));
        return $this->update_buys($buys_id, $data);
    }

    function buy_offers($buys_id, $quantity=1)
    {
        $data = array("status"=>3, "date"=>date("Y-m-d H:i:s"), "quantity"=> $quantity);
        return $this->update_buys($buys_id, $data);
    }

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_buys($buys_id, $data)
    {
        $this->db->where('buys_id', $buys_id);
		$this->db->update('buys', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    /**
    * Delete manufacturer
    * @param int $id - manufacture id
    * @return boolean
    */
	function delete_buys($id){
		$this->db->where('buys_id', $id);
		$this->db->delete('buys'); 
	}

}