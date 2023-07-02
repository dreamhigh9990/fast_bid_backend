<?php
class Medications_model extends CI_Model {

    private $table = 'medications';
    public function __construct()
    {
        $this->load->database();
    }

    public function get_medications_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('medications');
		$this->db->where('medications_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('medications');
    }

    public function get_rx_chart_files($residents_id)
    {
        $this->db->select('rx_chart_file_path, started_date');
		$this->db->from('medications');
        $this->db->where('residents_id', $residents_id);
		$this->db->where('rx_chart_file_path is not NULL');
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_medications($medications_id_like=null, $residents_id_like=null, $residents_name_like=null, $medication_text_like=null, $status_like=null, $only_running=null, $overdue=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('medications');

        if($medications_id_like){
            $this->db->like('medications_id', $medications_id_like);
        }
        if($residents_id_like){
            $this->db->like('residents_id', $residents_id_like);
        }
        if($residents_name_like){
            $this->db->like('residents_name', $residents_name_like);
        }
        if($medication_text_like){
            $this->db->like('medication_text', $medication_text_like);
        }
        if($status_like && !$only_running){
            $this->db->like('status', $status_like);
        }
        if ($only_running) {
            $this->db->where('ended_date is NULL');
        }
        if ($overdue) {
            $this->db->where('TIMESTAMPDIFF(HOUR, started_date, curdate()) > 24');
        }
        $this->db->group_by('medications_id');

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

    function count_medications($medications_id_like=null, $residents_id_like=null, $residents_name_like=null, $medication_text_like=null, $status_like=null, $only_running=null, $overdue=null)
    {
        $this->db->select('*');
        $this->db->from('medications');
        if($medications_id_like){
            $this->db->like('medications_id', $medications_id_like);
        }
        if($residents_id_like){
            $this->db->like('residents_id', $residents_id_like);
        }
        if($residents_name_like){
            $this->db->like('residents_name', $residents_name_like);
        }
        if($medication_text_like){
            $this->db->like('medication_text', $medication_text_like);
        }
        if($status_like && !$only_running){
            $this->db->like('status', $status_like);
        }
        if ($only_running) {
            $this->db->where('ended_date is NULL');
        }
        if ($overdue) {
            $this->db->where('TIMESTAMPDIFF(HOUR, started_date, curdate()) > 24');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function store_medication($data)
    {
        $this->db->insert('medications', $data);
        return $this->db->insert_id();
    }

    function update_medications($medications_id, $data)
    {
        $this->db->where('medications_id', $medications_id);
        $this->db->update('medications', $data);
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
    function delete_resident($id)
    {
        $this->db->where('medications_id', $id);
        $this->db->delete('medications');
        $this->db->where('medications_id', $id);
        $this->db->delete('answers');
    }

    function search_discussion($users_id, $query, $s_language, $d_language, $from, $count) 
    {
        $query = str_replace("'", "''", $query);
        $sql = 'select a.*,q.resident as resident,q.viewed_count as viewed_count,u.user_name as asker_name,(select count(*)>0 from review r where r.`answers_id`=a.`answers_id` and r.users_id='.$users_id.') as reviewed_by_user,(select count(*) from answers a where a.medications_id=q.medications_id) as answered_count,(MATCH(resident) AGAINST ("'.$query.'")) AS relevance from answers a, medications q, users u where (MATCH(resident) AGAINST ("'.$query.'")) and q.medications_id=a.medications_id and u.users_id=q.asker_id order by relevance limit '.$from. ', '.$count;
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }

    function search_discussion_es($users_id, $query, $s_language, $d_language, $from, $count) {
        $params = [
            'index' => 'medications',
            'body' => [
                'query' => [
                    'match' => [
                        'resident' => $query,
                    ]
                ],
                'size' => $count,
                'from' => $from
            ]
        ];

        $response = $this->config->item('es_client')->search($params);
        return $response;
    }

    function increase_view_count($medications_id)
    {
        $this->db->set('viewed_count', 'viewed_count + ' . 1, FALSE);
        $this->db->where('medications_id', $medications_id);
        $this->db->update($this->table);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if($report !== 0){
            return true;
        }else{
            return false;
        }
    }

    function count_medications_by_user($users_id)
    {
        $this->db->select('*');
        $this->db->from('medications');
        $this->db->where('asker_id', $users_id);
        $query = $this->db->get();
        return $query->num_rows();        
    }

    public function get_recent_medications_history($users_id, $limit_start=null, $limit_count=null) {
        $this->db->select('resident, medications_id');
		$this->db->from('medications');
		$this->db->where('asker_id', $users_id);
        $this->db->order_by('date', 'Desc');
        $this->db->limit($limit_count, $limit_start);
        $query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_resident_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('medications');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }
}