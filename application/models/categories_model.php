<?php
class Categories_model extends CI_Model {
 
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
    public function get_categories_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('categories_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function getNameById($id)
    {
        $this->db->select('*');
        $this->db->from('categories');
        $this->db->where('categories_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return "";
        $result = $query->result_array();
        return $result[0]['name']; 
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
    public function get_sub_categories_by_categories_id($categories_id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('categories_id, name');
		$this->db->from('categories');
        $this->db->where('parent', $categories_id);

		if($search_string){
			$this->db->like('name', $search_string);
		}
		$this->db->group_by('categories_id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('categories_id', $order_type);
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

    public function get_parent_category($sub_categories_id)
    {
        $this->db->select('parent');
        $this->db->from('categories');
        $this->db->where('categories_id', $sub_categories_id);
        $query = $this->db->get();
        $result_array = $query->result_array();
        return $result_array[0]['parent'];
    }

    public function get_categories($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('categories');
//        $this->db->where('parent', 0);

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('categories_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('categories_id', $order_type);
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

    public function get_all_categories()
    {
        $this->db->select('*');
        $this->db->from('categories');
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
    function count_sub_categories($categories_id, $search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('categories');
        $this->db->where('categories_id', $categories_id);
		if($search_string){
			$this->db->like('name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('categories_id', 'Asc');
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
    function count_categories($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('categories');
        $this->db->where('parent', 0);
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('categories_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_all_categories($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('categories');
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('categories_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_categories($data)
    {
		$insert = $this->db->insert('categories', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_categories($categories_id, $data)
    {
        $this->db->where('categories_id', $categories_id);
		$this->db->update('categories', $data);
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
	function delete_categories($id){
		$this->db->where('categories_id', $id);
		$this->db->delete('categories'); 
	}
 
}