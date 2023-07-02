<?php
class Jobs_model extends CI_Model {

    private $table = 'jobs';
    public function __construct()
    {
        $this->load->database();
    }

    public function get_jobs_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('jobs');
		$this->db->where('jobs_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('jobs');
    }

    public function get_jobs($jobs_id_like=null, $jobs_title_like=null, $jobs_country_like=null, $payment_like=null, $currency_like=null, $min_budget_like=null, $max_budget_like=null, $reviews_like=null, $score_like=null, $type_like=null, $created_at_like=null, $is_favorite=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('jobs');

        if($jobs_id_like){
            $this->db->like('jobs_id', $jobs_id_like);
        }
        if($jobs_title_like){
            $this->db->like('title', $jobs_title_like);
        }
        if($jobs_country_like){
            $this->db->where('country', $jobs_country_like);
        }
        if($is_favorite) {
            $this->db->where_in('country', ["Australia", "United States", "United Kingdom", "Canada", "South Africa", "New Zealand", "United Arab Emirates", "Japan", "Singapore", "Qatar", "Hong Kong", "Taiwan", "Luxembourg", "Norway", "Austria", "Denmark", "Finland", "Belgium", "Germany", "France", "Italy", "Portugal", "Netherlands", "Ireland"]);
            $this->db->where("((min_budget >= 10 AND type='hourly') OR (min_budget >= 100 AND type='fixed'))", NULL, FALSE);
            $this->db->where("currency <> ", 'INR');
            $today = date('Y-m-d');
            $this->db->where("(DATEDIFF('$today', since)<3 OR payment=1)", NULL, FALSE);
        }
        if($payment_like){
            $this->db->where('payment', $payment_like);
        }
        if($currency_like){
            $this->db->where('currency', $currency_like);
        }
        if($min_budget_like){
            $this->db->where('min_budget >=', $min_budget_like);
        }
        if($max_budget_like){
            $this->db->where('max_budget <=', $max_budget_like);
        }
        if($reviews_like){
            $this->db->where('reviews >=', $reviews_like);
        }
        if($score_like){
            $this->db->where('score >=', $score_like);
        }
        if($type_like){
            $this->db->like('type', $type_like);
        }
        if($created_at_like){
            $this->db->where('Date(created_at)', $created_at_like);
        }

        if ($sort){
            $this->db->order_by($sort, $order);
        } else {
            $this->db->order_by('created_at', 'desc');
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

    function count_jobs($jobs_id_like=null, $jobs_title_like=null, $jobs_country_like=null, $payment_like=null, $currency_like=null, $min_budget_like=null, $max_budget_like=null, $reviews_like=null, $score_like=null, $type_like=null, $created_at_like=null, $is_favorite=null)
    {
        $this->db->select('*');
        $this->db->from('jobs');
        if($jobs_id_like){
            $this->db->like('jobs_id', $jobs_id_like);
        }
        if($jobs_title_like){
            $this->db->like('title', $jobs_title_like);
        }
        if($jobs_country_like){
            $this->db->where('country', $jobs_country_like);
        }
        if($is_favorite) {
            $this->db->where_in('country', ["Australia", "United States", "United Kingdom", "Canada", "South Africa", "New Zealand", "United Arab Emirates", "Japan", "Singapore", "Qatar", "Hong Kong", "Taiwan", "Luxembourg", "Norway", "Austria", "Denmark", "Finland", "Belgium", "Germany", "France", "Italy", "Portugal", "Netherlands", "Ireland"]);
            $this->db->where("(min_budget >= 10 AND type='hourly') OR (min_budget >= 100 AND type='fixed')", NULL, FALSE);
            $this->db->where("currency <> ", 'INR');
            $today = date('Y-m-d');
            $this->db->where("(DATEDIFF('$today', since)<3 OR payment=1)", NULL, FALSE);
        }
        if($payment_like){
            $this->db->where('payment', $payment_like);
        }
        if($currency_like){
            $this->db->where('currency', $currency_like);
        }
        if($min_budget_like){
            $this->db->where('min_budget >=', $min_budget_like);
        }
        if($max_budget_like){
            $this->db->where('max_budget <=', $max_budget_like);
        }
        if($reviews_like){
            $this->db->where('reviews >=', $reviews_like);
        }
        if($score_like){
            $this->db->where('score >=', $score_like);
        }
        if($type_like){
            $this->db->like('type', $type_like);
        }
        if($created_at_like){
            $this->db->where('Date(created_at)', $created_at_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_job($data)
    {
        $this->db->insert('jobs', $data);
        return $this->db->insert_id();
    }

    function update_job($jobs_id, $data)
    {
        $this->db->where('jobs_id', $jobs_id);
        $this->db->update('jobs', $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete manufacturer
     * @param int $id - manufacture id
     * @return boolean
     */
    function delete_job($id)
    {
        $this->db->where('jobs_id', $id);
        $this->db->delete('jobs');
    }
}