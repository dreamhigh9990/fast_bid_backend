<?php
class Videos_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */

    private $table = 'videos';

    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_video_by_id($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('videos_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTitleById($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('videos_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return "";
        $result = $query->result_array();
        return $result[0]['title'];
    }

    public function get_videos_old($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);

        if($search_string){
            $this->db->like('title', $search_string);
        }
        $this->db->group_by('videos_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('videos_id', $order_type);
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

    public function get_videos($videos_id_like=null, $videos_link_like=null, $videos_title_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
      $this->db->select('*');
      $this->db->from($this->table);

      if($videos_id_like){
          $this->db->like('videos_id', $videos_id_like);
      }
      if($videos_link_like){
          $this->db->like('link', $videos_link_like);
      }
      if($videos_title_like){
          $this->db->like('title', $videos_title_like);
      }
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

    public function get_all_videos()
    {
        $this->db->select('*');
        $this->db->from($this->table);
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
    function count_videos_old($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if($search_string){
            $this->db->like('title', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('videos_id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function count_videos($videos_id_like=null, $videos_link_like=null, $videos_title_like=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if($videos_id_like){
            $this->db->like('videos_id', $search_string);
        }
        if($videos_link_like){
            $this->db->like('link', $search_string);
        }
        if($videos_title_like){
            $this->db->like('title', $search_string);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_videos($data)
    {
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
    }

    /**
    * Update manufacture
    * @param integer $videos_id - associative array with data to store
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_videos($id, $data)
    {
        $this->db->where('videos_id', $id);
        $this->db->update($this->table, $data);
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
    function delete_videos($id){
        $this->db->where('videos_id', $id);
        $this->db->delete($this->table);
    }
      
    public function get_video_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('videos');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }

}
