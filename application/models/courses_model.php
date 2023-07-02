<?php
class Courses_model extends CI_Model {
 
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
    public function get_courses_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('courses');
		$this->db->where('courses_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_course_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('courses');
        $this->db->where('courses_id', $id);
        $query = $this->db->get();
        $result = $query->result_array(); 
        if ($result)
            return $result[0];
        return $result;
    }  

    public function getNameById($id)
    {
        $this->db->select('*');
        $this->db->from('courses');
        $this->db->where('courses_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return "";
        $result = $query->result_array();
        return $result[0]['name']; 
    }

    public function get_courses($courses_id_like=null, $course_name_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('courses');

        if($courses_id_like){
            $this->db->like('courses_id', $courses_id_like);
        }
        if($course_name_like){
            $this->db->like('name', $course_name_like);
        }
        $this->db->group_by('courses_id');

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

    public function get_all_courses()
    {
        $this->db->select('*');
        $this->db->from('courses');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    function count_courses($courses_id_like=null, $course_name_like=null)
    {
        $this->db->select('*');
        $this->db->from('courses');
        if($courses_id_like){
            $this->db->like('courses_id', $courses_id_like);
        }
        if($course_name_like){
            $this->db->like('name', $course_name_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_all_courses($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('courses');
        if($search_string){
            $this->db->like('name', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('courses_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_courses($data)
    {
		$insert = $this->db->insert('courses', $data);
	    return $insert;
	}

    /**
    * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_courses($courses_id, $data)
    {
        $this->db->where('courses_id', $courses_id);
		$this->db->update('courses', $data);
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
	function delete_courses($id){
		$this->db->where('courses_id', $id);
		$this->db->delete('courses'); 
	}


    /*  Get Course info List*/
    
    public function get_courses_info($users_id=null, $limit_start=null, $limit_end=null) {
        $this->db->select('*');
        $this->db->from('courses');
        $this->db->order_by('orders_id', 'Asc');
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

    public function get_course_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('courses');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_course_studied_history($limit_start=null, $limit_end=null, $sort=null, $order=null) {
        $ret = array();
        $this->db->select('c.name, AVG(l.boost_score) as boost_score, l.users_id, l.date, l.courses_id, COUNT(DISTINCT(users_id)) as u_cnt');
        $this->db->from('courses as c');
        if ($sort){
            $this->db->order_by($sort, $order);
        }
        $this->db->join('learning_history as l', 'c.courses_id = l.courses_id', 'left');
        $this->db->group_by('c.courses_id');
        if($limit_start && $limit_end){
            $this->db->limit($limit_start, $limit_end);   
        }
        if($limit_start != null){
            $this->db->limit($limit_start, $limit_end);    
        }
        $query = $this->db->get();
        $result_array = $query->result_array();     
        $ret['day'] = $result_array; 
        
        $this->db->select('courses_id, Month(date) as date, COUNT(DISTINCT(users_id)) as co_cnt');
        $this->db->from('learning_history');
        $this->db->group_by(array('Month(date)', 'courses_id'));
        $query = $this->db->get();
        $result_array = $query->result_array();
        $ret['month'] = $result_array; 
        return $ret;
    }
 
}