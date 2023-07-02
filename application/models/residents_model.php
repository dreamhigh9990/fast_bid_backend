<?php
class Residents_model extends CI_Model {

    private $table = 'residents';
    public function __construct()
    {
        $this->load->database();
    }

    public function get_residents_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('residents');
		$this->db->where('residents_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function empty_table()
    {
        $this->db->empty_table('residents');
    }

    public function get_residents($residents_id_like=null, $resident_name_like=null, $residents_code_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('residents');

        if($residents_id_like){
            $this->db->like('residents_id', $residents_id_like);
        }
        if($resident_name_like){
            $this->db->like('name', $resident_name_like);
        }
        if($residents_code_like){
            $this->db->like('code', $residents_code_like);
        }
        $this->db->group_by('residents_id');

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

    function count_residents($residents_id_like=null, $residents_name_like=null, $residents_code_like=null)
    {
        $this->db->select('*');
        $this->db->from('residents');
        if($residents_id_like){
            $this->db->like('residents_id', $residents_id_like);
        }
        if($residents_name_like){
            $this->db->like('name', $residents_name_like);
        }
        if($residents_code_like){
            $this->db->like('code', $residents_code_like);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function store_resident($data)
    {
        $this->db->insert('residents', $data);
        return $this->db->insert_id();
    }

    function update_residents($residents_id, $data)
    {
        $this->db->where('residents_id', $residents_id);
        $this->db->update('residents', $data);
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
        $this->db->where('residents_id', $id);
        $this->db->delete('residents');
        $this->db->where('residents_id', $id);
        $this->db->delete('answers');
    }

    function search_discussion($users_id, $query, $s_language, $d_language, $from, $count) 
    {
        $query = str_replace("'", "''", $query);
        $sql = 'select a.*,q.resident as resident,q.viewed_count as viewed_count,u.user_name as asker_name,(select count(*)>0 from review r where r.`answers_id`=a.`answers_id` and r.users_id='.$users_id.') as reviewed_by_user,(select count(*) from answers a where a.residents_id=q.residents_id) as answered_count,(MATCH(resident) AGAINST ("'.$query.'")) AS relevance from answers a, residents q, users u where (MATCH(resident) AGAINST ("'.$query.'")) and q.residents_id=a.residents_id and u.users_id=q.asker_id order by relevance limit '.$from. ', '.$count;
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }

    function search_discussion_es($users_id, $query, $s_language, $d_language, $from, $count) {
        $params = [
            'index' => 'residents',
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

    function increase_view_count($residents_id)
    {
        $this->db->set('viewed_count', 'viewed_count + ' . 1, FALSE);
        $this->db->where('residents_id', $residents_id);
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

    function count_residents_by_user($users_id)
    {
        $this->db->select('*');
        $this->db->from('residents');
        $this->db->where('asker_id', $users_id);
        $query = $this->db->get();
        return $query->num_rows();        
    }

    public function get_recent_residents_history($users_id, $limit_start=null, $limit_count=null) {
        $this->db->select('resident, residents_id');
		$this->db->from('residents');
		$this->db->where('asker_id', $users_id);
        $this->db->order_by('date', 'Desc');
        $this->db->limit($limit_count, $limit_start);
        $query = $this->db->get();
		return $query->result_array(); 
    }
    public function get_resident_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('residents');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    
}