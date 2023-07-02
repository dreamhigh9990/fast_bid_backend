<?php
class Prayers_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    var $category = "";
    public function __construct()
    {
        $this->load->database();
    }

    public function select_category($category)
    {
        $this->category = $category;
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_prayers_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('prayers');
		$this->db->where('prayers_id', $id);
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
    public function get_prayers_by_owner_id($id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('prayers');
        $this->db->where('prayers_id', $id);

		if($search_string){
			$this->db->like('name', $search_string);
		}
		$this->db->group_by('prayers_id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('prayers_id', $order_type);
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

    public function get_prayers($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('*');
        $this->db->from('prayers');
        $this->db->where('category', $this->category);

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('prayers_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('prayers_id', $order_type);
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
    function count_prayers($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('prayers');
        $this->db->where('category', $this->category);
		if($search_string){
			$this->db->like('name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('prayers_id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_prayers($data)
    {
        $data['category'] = $this->category;
		$insert = $this->db->insert('prayers', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_prayers($id, $data)
    {
		$this->db->where('prayers_id', $id);
		$this->db->update('prayers', $data);
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
	function delete_prayers($id){
		$this->db->where('prayers_id', $id);
		$this->db->delete('prayers'); 
	}

    function delete_stores_by_owner_id($owner_id)
    {
        $this->db->where('owner_id', $owner_id);
        $this->db->delete('stores');
    }

    function get_first_prayers_content_by_category($category)
    {
        $this->db->select('*');
        $this->db->where('category', $category);
        $this->db->where('published', 1);
        $this->db->from('prayers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $prayer_content = $query->result_array();
            return $prayer_content[0];
        } else {
            return -1;
        }
    }

    function get_prayers_content_by_id($prayers_id)
    {
        $this->db->select('*');
        $this->db->where('prayers_id', $prayers_id);
        $this->db->where('published', 1);
        $this->db->from('prayers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $prayer_content = $query->result_array();
            return $prayer_content[0];
        } else {
            return -1;
        }
    }

    function get_sub_prayers_by_category($category)
    {
        $this->db->select('*');
        $this->db->where('category', $category);
        $this->db->from('prayers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $prayer_content = $query->result_array();
            return $prayer_content;
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