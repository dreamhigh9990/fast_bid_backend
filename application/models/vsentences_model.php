<?php

class VSentences_model extends CI_Model {
 
  private $table = 'vsentences';

  public function __construct()
  {
    $this->load->database();
    $this->load->dbforge();
  }

  /**
  * Get product by his is
  * @param int $product_id
  * @return array
  */
  public function get_vsentences_by_id($id)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('vsentences_id', $id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_vsentences_by_videos_id($videos_id)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('videos_id', $videos_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_vsentences_by_users_id_and_videos_id($users_id, $videos_id)
  {
    $this->db->select('vs.*,v.title as title, (select count(*)>0 from review r where r.`vsentences_id`=vs.`vsentences_id` and r.users_id='.$users_id.') as reviewed_by_user');
    $this->db->from('vsentences vs');
    $this->db->join('videos v', 'vs.videos_id=v.videos_id');
    $this->db->where('vs.videos_id', $videos_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_vsentences_by_users_id_and_id($users_id, $id)
  {
    $query = $this->db->query(
      'select "vsentences" as type, vs.*,v.title as title, (select count(*)>0 from review r where r.`vsentences_id`=vs.`vsentences_id` and r.users_id='.$users_id.') as reviewed_by_user from vsentences vs, videos v where vs.vsentences_id='.$id.' and vs.videos_id=v.videos_id'
      );
    if ($query->num_rows() <= 0)
      return array();
    $result = $query->result_array();
    return $result[0];
  }

  public function add_lang_column($lang)
  {
    if (!$this->db->field_exists($lang, $this->table)) {
      $this->dbforge->add_column($this->table, array($lang => array('type' => 'TEXT', 'null' => TRUE)));
    }
  }

  public function store_vsentences($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  function update_vsentences($id, $data)
  {
  }

  public function delete_vsentences($id){
    $this->db->where('vsentences_id', $id);
    $this->db->delete($this->table);
  }

  function search_videos($users_id, $query, $s_language, $d_language, $from, $count) 
  {
    $query = str_replace("'", "''", $query);
    $sql = 'select vs.*,v.title as title, (select count(*)>0 from review r where r.`vsentences_id`=vs.`vsentences_id` and r.users_id='.$users_id.') as reviewed_by_user,(MATCH(en) AGAINST ("'.$query.'")) AS relevance from vsentences vs, videos v where vs.videos_id=v.videos_id and MATCH(en) AGAINST ("'.$query.'") order by relevance desc limit '.$from.','.$count;

    $query = $this->db->query($sql);
    $result_array = $query->result_array();
    return $result_array;
  }

  function search_videos_es($users_id, $query, $s_language, $d_language, $from, $count) {
    $params = [
        'index' => 'vsentences',
        'body' => [
            'query' => [
                'match' => [
                    'en' => $query,
                ]
            ],
            'size' => $count,
            'from' => $from
        ]
    ];

    $response = $this->config->item('es_client')->search($params);
    return $response;
  }

  function increase_view_count($vsentences_id)
  {
    $this->db->set('viewed_count', 'viewed_count + ' . 1, FALSE);
    $this->db->where('vsentences_id', $vsentences_id);
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

  function increase_review_count($vsentences_id)
  {
    $this->db->set('reviewed_count', 'reviewed_count + ' . 1, FALSE);
    $this->db->where('vsentences_id', $vsentences_id);
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

  function decrease_review_count($vsentences_id)
  {
    $this->db->set('reviewed_count', 'reviewed_count - ' . 1, FALSE);
    $this->db->where('vsentences_id', $vsentences_id);
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
}
