<?php
class Books_model extends CI_Model {
 
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
    public function get_books_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('books');
		$this->db->where('books_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function get_book_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('books_id', $id);
        $query = $this->db->get();
        $result = $query->result_array(); 
        if ($result)
            return $result[0];
        return $result;
    } 

    public function getNameById($id)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('books_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return "";
        $result = $query->result_array();
        return $result[0]['name']; 
    }

    public function get_books($courses_id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('courses_id >', '0');
        $this->db->where('courses_id', $courses_id);

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('books_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('books_id', $order_type);
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

    public function get_ibooks($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('courses_id <=', '0');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('books_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('books_id', $order_type);
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

    public function get_all_books()
    {
        $this->db->select('*');
        $this->db->from('books');
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
    function count_books($courses_id, $search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('courses_id >', '0');
        $this->db->where('courses_id', $courses_id);
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('books_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_ibooks($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('courses_id <=', '0');
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('books_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_books($data)
    {
		$insert = $this->db->insert('books', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_books($books_id, $data)
    {
        $this->db->where('books_id', $books_id);
		$this->db->update('books', $data);
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
	function delete_books($id){
		$this->db->where('books_id', $id);
		$this->db->delete('books'); 
	}
 
}