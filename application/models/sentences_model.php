<?php
    class Sentences_model extends CI_Model {

    /**
     * Responsable for auto load the database
    * @return void
    */

    private $table = 'sentences';
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get product by his is
    * @param int $product_id
    * @return array
    */
    public function get_sentences_by_id($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('sentences_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_sentences_by_users_id_and_id($users_id, $id)
    {
        $this->db->select('*, "sentences" as type, (SELECT COUNT(*)>0 FROM review r WHERE r.`sentences_id`=s.`sentences_id` AND r.users_id='.$users_id.') as reviewed_by_user', false);
        $this->db->from('sentences s');
        $this->db->where('sentences_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return array();
        $result = $query->result_array();
        return $result[0];
    }

    public function get_sentences_by_users_id_and_chapters_id($users_id, $chapters_id)
    {
        $this->db->select('*, (SELECT COUNT(*)>0 FROM review r WHERE r.`sentences_id`=s.`sentences_id` AND r.users_id='.$users_id.') as reviewed_by_user');
        $this->db->from('sentences s');
        $this->db->where('chapters_id >', '0');
        $this->db->where('chapters_id', $chapters_id);
        $query = $this->db->get();
        $result_array = $query->result_array();

        return $result_array;
    }

    public function get_sentences($chapters_id, $sentences_id_like=null, $en_like=null, $zh_CN_like=null, $limit_start=null, $limit_end=null, $sort=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('chapters_id', $chapters_id);

        if($sentences_id_like){
            $this->db->like('sentences_id', $sentences_id_like);
        }
        if($en_like){
            $this->db->like('en', $en_like);
        }
        if($zh_CN_like){
            $this->db->like('zh-CN', $zh_CN_like);
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

    public function get_isentences($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('chapters_id <=', '0');

        if($search_string){
            $this->db->like('name', $search_string);
        }
        $this->db->group_by('sentences_id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('sentences_id', $order_type);
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

    public function get_all_sentences()
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
    
    function count_sentences($chapters_id, $sentences_id_like=null, $en_like=null, $zh_CN_like=null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('chapters_id', $chapters_id);
        if($sentences_id_like){
            $this->db->like('sentences_id', $sentences_id_like);
        }
        if($en_like){
            $this->db->like('en', $en_like);
        }
        if($zh_CN_like){
            $this->db->like('zh-CN', $zh_CN_like);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean
    */
    public function store_sentences($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
        }

    /**
     * Update manufacture
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_sentences($sentences_id, $data)
    {
        // $this->db->where('sentences_id', $sentences_id);
        // $this->db->update($this->table, array(
        //     "s_en"=>"",
        //     "s_zh-CN"=>"",
        //     "s_ko"=>"",
        //     "s_de"=>"",
        //     "d_zh-CN"=>"",
        //     "d_en"=>"",
        //     "d_ko"=>"",
        //     "d_de"=>"",
        // ));
        $this->db->where('sentences_id', $sentences_id);
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

    function increase_view_count($sentences_id)
    {
        $this->db->set('viewed_count', 'viewed_count + ' . 1, FALSE);
        $this->db->where('sentences_id', $sentences_id);
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

    function increase_review_count($sentences_id)
    {
        $this->db->set('reviewed_count', 'reviewed_count + ' . 1, FALSE);
        $this->db->where('sentences_id', $sentences_id);
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

    function decrease_review_count($sentences_id)
    {
        $this->db->set('reviewed_count', 'reviewed_count - ' . 1, FALSE);
        $this->db->where('sentences_id', $sentences_id);
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

    /**
     * Delete manufacturer
    * @param int $id - manufacture id
    * @return boolean
    */
        function delete_sentences($id){
            $this->db->where('sentences_id', $id);
            $this->db->delete($this->table);
        }

    function search_sentences($users_id, $query, $s_language, $d_language, $from, $count) {
        $query = str_replace("'", "''", $query);
        $sql = "SELECT *, (SELECT COUNT(*)>0 FROM review r WHERE r.`sentences_id`=s.`sentences_id` AND r.users_id=$users_id) AS reviewed_by_user, (MATCH(s_en) AGAINST ('$query')) AS relevance FROM sentences s WHERE MATCH(s_en) AGAINST ('$query') ORDER BY relevance DESC LIMIT {$from}, {$count}";
        var_dump($sql);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function search_sentences_es($users_id, $query, $s_language, $d_language, $from, $count) {
        $params = [
            'index' => 'sentences',
            'body' => [
                'query' => [
                    'match' => [
                        's_en' => $query,
                    ]
                ],
                'size' => $count,
                'from' => $from
            ]
        ];

        $response = $this->config->item('es_client')->search($params);
        return $response;
    }

    function search_all($query, $from, $count)
    {
        $query = str_replace("'", "''", $query);
        $sql = "
        SELECT * FROM (
        SELECT 
            sentences_id AS id, 'sentences' AS 'type',
            (MATCH(s_en) AGAINST ('$query')) AS relevance
            FROM sentences
        UNION
        SELECT 
            questions_id AS id, 'questions' AS 'type',
            (MATCH(question) AGAINST ('$query')) AS relevance
            FROM questions q
            WHERE (SELECT COUNT(*) FROM answers a WHERE a.questions_id=q.questions_id) > 0
        UNION
        SELECT 
            vsentences_id AS id, 'vsentences' AS 'type', 
            (MATCH(en) AGAINST ('$query')) AS relevance
            FROM vsentences
        )
        AS merged WHERE relevance > 0 ORDER BY relevance DESC LIMIT {$from}, {$count};
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function search_all_es($query, $from, $count)
    {
        $params = [
            'index' => 'sentences,vsentences',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ["en^4", "en^7"]
                    ]
                ],
                'size' => $count,
                'from' => $from
            ]
        ];

        $response = $this->config->item('es_client')->search($params);
        return $response;
    }

    public function get_sentence_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(*) as co_cnt');
        $this->db->from('sentences');
        $this->db->where('words != ', 'NULL');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;        
    }
}