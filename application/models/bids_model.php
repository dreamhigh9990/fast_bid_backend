<?php
class Bids_model extends CI_Model {

    private $table = 'bids';
    public function __construct()
    {
        $this->load->database();
    }

    public function get_bids_by_id($id)
    {
		$this->db->select('jobs.jobs_id, jobs.title, bids.proposal, bids.price');
		$this->db->from('bids, jobs');
		$this->db->where('bids_id', $id);
        $this->db->where('bids.jobs_id', 'jobs.jobs_id');
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('bids');
    }

    public function get_bids($bids_id_like=null, $bids_proposal_like=null, $bids_price_above=null, $only_new=false, $today=false, $accounts_user_name=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('bids_id, users_id, accounts_user_name, jobs.jobs_id, jobs.title, jobs.type, bids.bid_at, bids.created_at, bids.proposal, bids.period, bids.price');
		$this->db->from('bids, jobs');
        $this->db->where('bids.jobs_id = jobs.jobs_id');

        if($bids_id_like){
            $this->db->like('bids_id', $bids_id_like);
        }
        if($bids_proposal_like){
            $this->db->like('proposal', $bids_proposal_like);
        }
        if($bids_price_above){
            $this->db->where('price', '>', $bids_price_above);
        }
        if ($only_new) {
            $this->db->where('bid_at is NULL');
            $this->db->where('TIMESTAMPDIFF(MINUTE, bids.created_at, NOW()) < 10');
        }
        if ($today) {
            $this->db->where('DATE(bid_at) = CURDATE()');
        }
        if ($accounts_user_name) {
            $this->db->where('accounts_user_name', $accounts_user_name);
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

    function count_bids($bids_id_like=null, $bids_proposal_like=null, $bids_price_above=null, $only_new=false, $today=false, $accounts_user_name=null)
    {
        $this->db->select('*');
        $this->db->from('bids');
        if($bids_id_like){
            $this->db->like('bids_id', $bids_id_like);
        }
        if($bids_proposal_like){
            $this->db->like('proposal', $bids_proposal_like);
        }
        if($bids_price_above){
            $this->db->where('price', '>', $bids_price_above);
        }
        if ($only_new) {
            $this->db->where('bid_at is NULL');
        }
        if ($today) {
            $this->db->where('DATE(bid_at) = CURDATE()');
        }
        if ($accounts_user_name) {
            $this->db->where('accounts_user_name', $accounts_user_name);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_bid($data)
    {
        $this->db->insert('bids', $data);
        return $this->db->insert_id();
    }

    function update_bid($jobs_id, $data)
    {
        $this->db->where('jobs_id', $jobs_id);
        $this->db->update('bids', $data);
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
    function delete_bid($id)
    {
        $this->db->where('bids_id', $id);
        $this->db->delete('bids');
    }   
}