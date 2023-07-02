<?php
class Offers_model extends CI_Model {
 
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
    public function get_offers_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('offers');
		$this->db->where('offers_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_offers_by_id_array($id_array)
    {
        $this->db->select('*');
        $this->db->from('offers');
        $this->db->where_in('offers_id', $id_array);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_offer_detail($id)
    {
        $this->db->select('*');
        $this->db->from('offers');
        $this->db->where('offers_id', $id);
        $query = $this->db->get();
        $result_array = $query->result_array(); 
        if (count($result_array) <= 0)
            return null;
        $offer = $result_array[0];

        $strQuery = "SELECT COUNT(*) as favorites_count FROM favorites WHERE offers_id='$id'";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            $offer['favorites_count'] = $query->result_array()[0]['favorites_count'];
        }

        $strQuery = "SELECT COUNT(*) as likes_count FROM likes WHERE offers_id='$id'";
        $query = $this->db->query($strQuery);
        if ($query)
        {
            $offer['likes_count'] = $query->result_array()[0]['likes_count'];
        }
        return $offer;
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
    public function get_offers_by_stores_id($stores_id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('offers');
        $this->db->where('stores_id', $stores_id);

		if($search_string){
			$this->db->like('name', $search_string);
		}
		$this->db->group_by('offers_id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('offers_id', $order_type);
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

    public function get_offers($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('*');
        $this->db->from('offers');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('offers_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('offers_id', $order_type);
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

	function get_recommended_offers_by_users_id($user_id)
	{
		return $this->get_offers(null, null, 'Asc', 0, 5);
	}
	
	function get_deal_offers_by_users_id($user_id)
	{
		return $this->get_offers(null, null, 'Desc', 0, 5);
	}


    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_offers($stores_id, $search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('offers');
        $this->db->where('stores_id', $stores_id);
		if($search_string){
			$this->db->like('name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('offers_id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_offers_by_stores_id($stores_id, $search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('offers');
        $this->db->where('stores_id', $stores_id);
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('offers_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_offers($data)
    {
		$insert = $this->db->insert('offers', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_offers($offers_id, $data)
    {
        $this->db->where('offers_id', $offers_id);
		$this->db->update('offers', $data);
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
	function delete_offers($id){
		$this->db->where('offers_id', $id);
		$this->db->delete('offers'); 
	}

    function search($query=null, $stores_id=null, $categories_id=null, $sort_by=1, $price_low=null, $price_high=null, $in_stock=true)
    {
        $strQuery = "SELECT * FROM offers WHERE 1=1 ";
        $whereQuery = "";

        if ($query)
        {
            $whereQuery .= "AND (MATCH (name) AGAINST ('$query' with query expansion) OR MATCH (description) AGAINST ('$query dish' with query expansion))";
        }
        if ($stores_id)
        {
            $whereQuery .= "AND (stores_id='$stores_id')";
        }
        if ($categories_id)
        {
            $whereQuery .= "AND (categories_id='$categories_id')";
        }
        if ($price_low)
        {
            $whereQuery .= "AND (price>='$price_low')";
        }
        if ($price_high)
        {
            $whereQuery .= "AND (price<='$price_high')";
        }
        if ($in_stock)
        {
            $whereQuery .= "AND (quantity > 0)";
        }
        $sortQuery = "";
        switch ($sort_by) {
            case '1': // Relevancy
                break;
            case '2': // Price high to low
                $sortQuery = " ORDER BY price DESC";
                break;
            case '3': // Price low to high
                $sortQuery = " ORDER BY price";
                break;
            case '4': // Price high to low
                $sortQuery = " ORDER BY rating DESC";
                break;
            
            default:
                break;
        }

        // Offers
        $query = $this->db->query($strQuery . $whereQuery . $sortQuery);
        if ($query)
        {
            $offers_array = $query->result_array();
        }

        // echo $strQuery . $whereQuery . $sortQuery;
        // die;

        // Stores
        $strQuery = "SELECT `stores`.`name`, `stores`.`stores_id` FROM `stores`, (SELECT distinct(`offers`.`stores_id`) as `new_stores_id` FROM `offers` WHERE 1=1 ".$whereQuery.") AS `new_stores` WHERE `stores`.`stores_id`=`new_stores`.`new_stores_id`";

        $query = $this->db->query($strQuery);
        if ($query)
        {
            $stores_array = $query->result_array();
        }

        // Categories
        $strQuery = "SELECT `categories`.`name`, `categories`.`categories_id` FROM `categories`, (SELECT distinct(`offers`.`categories_id`) as `new_categories_id` FROM `offers` WHERE 1=1 ".$whereQuery.") AS `new_categories` WHERE `categories`.`categories_id`=`new_categories`.`new_categories_id`";

        $query = $this->db->query($strQuery);
        if ($query)
        {
            $categories_array = $query->result_array();
        }

        // Min, Max Price
        $strQuery = "SELECT min(price) as min_price, max(price) as max_price FROM offers WHERE 1=1 ";
        $query = $this->db->query($strQuery . $whereQuery);
        if ($query)
        {
            $min_max_array = $query->result_array();
        }

        $result_array = array('offers'=>$offers_array, 'stores'=>$stores_array, 'categories'=>$categories_array, 'min_max'=>$min_max_array);
        return $result_array;
    }
}