<?php
class Chapters_model extends CI_Model {
 
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
    public function get_chapters_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('chapters');
		$this->db->where('chapters_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function get_chapter_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('chapters_id', $id);
        $query = $this->db->get();
        $result = $query->result_array(); 
        if ($result)
            return $result[0];
        return $result;
    }  

    public function getNameById($id)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('chapters_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return "";
        $result = $query->result_array();
        return $result[0]['name']; 
    }

    public function get_chapters_old($books_id, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('books_id >', '0');
        $this->db->where('books_id', $books_id);

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('chapters_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('chapters_id', $order_type);
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

    public function get_chapters($courses_id, $chapters_id_like=null, $chapter_name_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('courses_id', $courses_id);

        if($chapters_id_like){
            $this->db->like('chapters_id', $chapters_id_like);
        }
        if($chapter_name_like){
            $this->db->like('name', $chapter_name_like);
        }
        $this->db->group_by('chapters_id');

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

    public function get_ichapters($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('books_id <=', '0');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('chapters_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('chapters_id', $order_type);
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

    public function get_all_chapters_by_course_id($courses_id)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('courses_id', $courses_id);
        $this->db->order_by('chapters_id', 'asc');
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
    function count_chapters_old($books_id, $search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('books_id >', '0');
        $this->db->where('books_id', $books_id);
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('chapters_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_chapters($courses_id, $chapters_id_like=null, $chapter_name_like=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('courses_id', $courses_id);
        if($chapter_name_like){
            $this->db->like('name', $chapter_name_like);
        }
        if($chapters_id_like){
            $this->db->like('chapters_id', $chapters_id_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_ichapters($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('chapters');
        $this->db->where('books_id <=', '0');
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('chapters_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_chapters($data)
    {
		$insert = $this->db->insert('chapters', $data);
	    return $this->db->insert_id();
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_chapters($chapters_id, $data)
    {
        $this->db->where('chapters_id', $chapters_id);
		$this->db->update('chapters', $data);
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
	function delete_chapters($id){
		$this->db->where('chapters_id', $id);
		$this->db->delete('chapters'); 
	}


    public function get_chapters_by_chapters_id($chapters_id) {
        $this->db->select('*, chapters.name as chapters_name, courses.name as courses_name');
        $this->db->from('chapters');
        $this->db->where('chapters_id', $chapters_id);
        $this->db->join('courses', 'courses.courses_id = chapters.courses_id', 'left');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;

    }
    

    Public function get_next_chapter($users_id, $courses_id) {
        $this->db->select('c.chapters_id, l.success_flag, l.users_id');
        $this->db->from('chapters c');
        $this->db->where('c.courses_id', $courses_id);
        $this->db->join('learning_history l', 'l.chapters_id = c.chapters_id', 'left');
        $this->db->where('l.users_id', $users_id);
        $this->db->where('l.smart_flag', 0);
        $this->db->order_by('l.chapters_id', 'asc');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_chapter_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('chapters');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }
}