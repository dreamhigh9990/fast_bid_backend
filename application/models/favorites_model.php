<?php
class Favorites_model extends CI_Model {
 
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
    public function get_favorites_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('favorites');
		$this->db->where('favorites_id', $id);
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
    public function get_favorites_by_users_id($users_id)
    {
		$this->db->select('*');
		$this->db->from('favorites');
        $this->db->where('users_id', $users_id);
        $this->db->where('unfavorited', 0);
		$query = $this->db->get();
		$result_array = $query->result_array(); 	
        return $result_array;
    }

    public function get_favorites_by_offers_id($offers_id)
    {
        $this->db->select('*');
        $this->db->from('favorites');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('unfavorited', 0);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function is_favorited($offers_id, $users_id)
    {
        $this->db->select('unfavorited');
        $this->db->from('favorites');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return false;
        $result_array = $query->result_array();
        return ($result_array[0]['unfavorited'] == 0);
    }

    public function favorited_count($offers_id)
    {
        $this->db->select('*');
        $this->db->from('favorites');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('unfavorited', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    public function favorite_offer($users_id, $offers_id)
    {
        $this->db->select('*');
        $this->db->from('favorites');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        if ($query->num_rows() >0) 
        {
            $data = array('unfavorited'=> 0, 'date'=> date("Y-m-d H:i:s"));
            $this->db->where('offers_id', $offers_id);
            $this->db->where('users_id', $users_id);
            return $this->db->update('favorites', $data);
        }
        
        $data = array('users_id'=> $users_id, 'offers_id'=> $offers_id, 'date'=> date("Y-m-d H:i:s"));
        $insert = $this->db->insert('favorites', $data);
        return $insert;
	}

    public function unfavorite_offer($users_id, $offers_id)
    {
        $data = array('unfavorited'=> 1, 'date'=> date("Y-m-d H:i:s"));
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        return $this->db->update('favorites', $data);
    }
}