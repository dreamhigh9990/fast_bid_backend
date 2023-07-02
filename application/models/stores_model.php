<?php
class Stores_model extends CI_Model {
 
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
    public function get_stores_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('stores');
		$this->db->where('stores_id', $id);
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
    public function get_stores_by_owner_id($owner_id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('stores');
        $this->db->where('owner_id', $owner_id);

		if($search_string){
			$this->db->like('name', $search_string);
		}
		$this->db->group_by('stores_id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('stores_id', $order_type);
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

    public function get_stores($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('*');
        $this->db->from('stores');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('stores_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('stores_id', $order_type);
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

    public function get_stores_with_distance($search_string=null, $latitude, $longitude, $limit_start=null, $limit_end=null)
    {
        
        $this->db->select("*, (((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance");
        $this->db->from('stores');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->order_by('distance', 'Asc');

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


    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_stores($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('stores');
		if($search_string){
			$this->db->like('gname', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('stores_id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_stores($data)
    {
		$insert = $this->db->insert('stores', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_stores($id, $data)
    {
		$this->db->where('owner_id', $id);
		$this->db->update('stores', $data);
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
	function delete_stores($id){
		$this->db->where('stores_id', $id);
		$this->db->delete('stores'); 
	}

    function delete_stores_by_owner_id($owner_id)
    {
        $this->db->where('owner_id', $owner_id);
        $this->db->delete('stores');
    }

    function get_stores_id_by_owner_id($owner_id)
    {
        $this->db->select('stores_id');
        $this->db->where('owner_id', $owner_id);
        $this->db->from('stores');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $stores_ids = $query->result_array();
            return $stores_ids[0]['stores_id'];
        } else {
            return -1;
        }
    }

    function get_nearby_stores($latitude, $longitude, $distance)
    {
        $sqlSearch = "SELECT *,(((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance FROM `stores` WHERE (((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) <= ".$distance;

        $query = $this->db->query($sqlSearch);
        if ($query)
        {
            $stores_array = $query->result_array();
        }
        return $stores_array;
    }
}