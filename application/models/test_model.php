<?php
class Test_model extends CI_Model {
 
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
    public function get_test_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('test');
		$this->db->where('test_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    /**
    * Fetch manufacturers data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_tested_by_users_id($users_id)
    {
		$this->db->select('*');
		$this->db->from('tested');
        $this->db->where('users_id', $users_id);
		$query = $this->db->get();
		$result_array = $query->result_array(); 	
        return $result_array;
    }

    public function get_local_test_sentences($users_id, $user_s_lang, $user_d_lang, $from_date, $to_date, $limited_count)
    {
        $reviewed_sentences = array();

        $this->db->select('sentences.*');
        $this->db->from('review, sentences');
        $this->db->where('review.sentences_id > ', 0);
        $this->db->where('review.sentences_id = sentences.sentences_id');
        $this->db->where('review.date >= ', $from_date);
        $this->db->where('review.date <= ', $to_date);
        $this->db->where('users_id', $users_id);
        $this->db->limit($limited_count / 2);
        $query = $this->db->get();
        $sentences = $query->result_array();
        foreach ($sentences as $key => $sentence) {
            $sentences[$key]['source_text'] = $sentence['s_'.$sentence['s_language']];
            $sentences[$key]['target_text'] = $sentence['d_'.$sentence['d_language']];
            $sentences[$key]['type'] = "sentences";

            // get random sentences for candidates
            $this->db->select('sentences.*');
            $this->db->from('sentences');
            $this->db->where('rand() <', 0.5);
            $this->db->where('sentences_id <>', $sentence['sentences_id']);
            $this->db->limit(5);
            $query = $this->db->get();
            $candidates = $query->result_array();
            foreach ($candidates as $key2 => $candidate) {
                if (trim($candidate['d_'.$candidate['d_language']]) != "")
                    $candidates[$key2]['target_text'] = $candidate['d_'.$candidate['d_language']];
            }
            $sentences[$key]['candidates'] = $candidates;
        }
        $reviewed_sentences['sentences'] = $sentences;

        $this->db->select('questions.questions_id, questions.question AS questions_text, answers.answers_id, answers.answer AS answers_text, answers.viewed_count, answers.reviewed_count, questions.answered_count');
        $this->db->from('review, questions, answers');
        $this->db->where('review.questions_id > ', 0);
        $this->db->where('review.answers_id > ', 0);
        $this->db->where('review.questions_id = questions.questions_id');
        $this->db->where('questions.questions_id = answers.questions_id');
        $this->db->where('review.answers_id = answers.answers_id');
        $this->db->where('review.date >= ', $from_date);
        $this->db->where('review.date <= ', $to_date);
        $this->db->where('users_id', $users_id);
        $this->db->limit($limited_count / 4);
        $query = $this->db->get();
        $discussion = $query->result_array();
        foreach ($discussion as $key => $dis) {
            $discussion[$key]['source_text'] = $dis['questions_text'];
            $discussion[$key]['target_text'] = $dis['answers_text'];
            $discussion[$key]['type'] = "discussion";

            // get random sentences for candidates
            $this->db->select('*');
            $this->db->from('answers');
            $this->db->where('rand() <', 0.5);
            $this->db->limit(5);
            $query = $this->db->get();
            $candidates = $query->result_array();
            foreach ($candidates as $key2 => $candidate) {
                if (trim($candidate['answer']) != "")
                    $candidates[$key2]['target_text'] = $candidate['answer'];
            }
            $discussion[$key]['candidates'] = $candidates;
        }
        $reviewed_sentences['discussion'] = $discussion;

        $this->db->select('videos.title as video_title, vsentences.*');
        $this->db->from('review, videos, vsentences');
        $this->db->where('review.vsentences_id > ', 0);
        $this->db->where('review.vsentences_id = vsentences.vsentences_id');
        $this->db->where('vsentences.videos_id = videos.videos_id');
        $this->db->where('review.date >= ', $from_date);
        $this->db->where('review.date <= ', $to_date);
        $this->db->where('users_id', $users_id);
        $this->db->limit($limited_count / 4);
        $query = $this->db->get();
        $vsentences = $query->result_array();
        foreach ($vsentences as $key => $vsentence) {
            $vsentences[$key]['source_text'] = htmlspecialchars_decode($vsentence[$vsentence['main']], ENT_QUOTES);

            // temp code
            if ($user_d_lang == 'zh-CN' && trim($vsentence[$user_d_lang]) == "")
                $vsentence[$user_d_lang] = $vsentence['zh'];
            
            $vsentences[$key]['target_text'] = $vsentence[$user_d_lang];

            // get random sentences for candidates
            // $this->db->select('*');
            // $this->db->from('vsentences');
            // $this->db->where('rand() <', 0.5);
            // $this->db->limit(5);
            // $query = $this->db->get();
            // $candidates = $query->result_array();
            // foreach ($candidates as $key2 => $candidate) {
            //     $candidates[$key2]['target_text'] = htmlspecialchars_decode($vsentence[$user_d_lang], ENT_QUOTES);
            // }
            // $vsentences[$key]['candidates'] = $candidates;
            $this->db->select('sentences.*');
            $this->db->from('sentences');
            $this->db->where('rand() <', 0.5);
            $this->db->limit(5);
            $query = $this->db->get();
            $candidates = $query->result_array();
            foreach ($candidates as $key2 => $candidate) {
                if (trim($candidate['d_'.$candidate['d_language']]) != "")
                    $candidates[$key2]['target_text'] = $candidate['d_'.$candidate['d_language']];
            }
            $vsentences[$key]['candidates'] = $candidates;
        }
        $reviewed_sentences['videos'] = $vsentences;

        return $reviewed_sentences;
    }

    public function get_global_test_sentences($users_id, $user_s_lang, $user_d_lang, $limited_count)
    {
        $reviewed_sentences = array();

        $this->db->select('sentences.*');
        $this->db->from('review, sentences');
        $this->db->where('review.sentences_id > ', 0);
        $this->db->where('review.sentences_id = sentences.sentences_id');
        $this->db->where('users_id', $users_id);
        $this->db->order_by('rand()');
        $this->db->limit($limited_count / 2);
        $query = $this->db->get();
        $sentences = $query->result_array();
        foreach ($sentences as $key => $sentence) {
            $sentences[$key]['source_text'] = $sentence['s_'.$sentence['s_language']];
            $sentences[$key]['target_text'] = $sentence['d_'.$sentence['d_language']];
        }
        $reviewed_sentences['sentences'] = $sentences;

        $this->db->select('questions.questions_id, questions.question AS questions_text, answers.answers_id, answers.answer AS answer_text, answers.viewed_count, answers.reviewed_count, questions.answered_count');
        $this->db->from('review, questions, answers');
        $this->db->where('review.questions_id > ', 0);
        $this->db->where('review.answers_id > ', 0);
        $this->db->where('review.questions_id = questions.questions_id');
        $this->db->where('questions.questions_id = answers.questions_id');
        $this->db->where('review.answers_id = answers.answers_id');
        $this->db->where('users_id', $users_id);
        $this->db->order_by('rand()');
        $this->db->limit($limited_count / 4);
        $query = $this->db->get();
        $discussion = $query->result_array();
        $reviewed_sentences['discussion'] = $discussion;

        $this->db->select('videos.title as video_title, vsentences.*');
        $this->db->from('review, videos, vsentences');
        $this->db->where('review.vsentences_id > ', 0);
        $this->db->where('review.vsentences_id = vsentences.vsentences_id');
        $this->db->where('vsentences.videos_id = vsentences.videos_id');
        $this->db->where('users_id', $users_id);
        $this->db->order_by('rand()');
        $this->db->limit($limited_count / 4);
        $query = $this->db->get();
        $vsentences = $query->result_array();
        foreach ($vsentences as $key => $vsentence) {
            $vsentences[$key]['source_text'] = $vsentence[$vsentence['main']];
            $vsentences[$key]['target_text'] = $vsentence[$user_d_lang];
        }
        $reviewed_sentences['videos'] = $vsentences;

        return $reviewed_sentences;
    }

    public function get_tested_count($users_id, $from_date, $to_date)
    {
        $this->db->select('*');
        $this->db->from('tested');
        $this->db->where('users_id', $users_id);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_local_test_score_history($users_id, $from_date, $to_date)
    {
        $this->db->select('date as tested_date, sentences_count, score');
        $this->db->from('tested');
        $this->db->where('users_id', $users_id);
        $this->db->where('is_global', 0);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_global_test_score_history($users_id, $from_date, $to_date)
    {
        $this->db->select('date as tested_date, sentences_count, score');
        $this->db->from('tested');
        $this->db->where('users_id', $users_id);
        $this->db->where('is_global', 1);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_test_score_history($users_id, $from_date, $to_date)
    {
        $this->db->select('date as tested_date, sentences_count, score');
        $this->db->from('tested');
        $this->db->where('users_id', $users_id);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function set_local_test_score($users_id, $tested_date, $tested_sentences_count, $tested_score, $from_date, $to_date)
    {
        $this->db->insert('tested', array('users_id'=>$users_id, 'date'=>$tested_date, 'sentences_count'=>$tested_sentences_count, 'score'=>$tested_score, 'review_from_date'=>$from_date, 'review_to_date'=>$to_date, 'is_global'=>0));
        return $this->db->insert_id();
    }

    public function set_global_test_score($users_id, $tested_date, $tested_score)
    {
        $this->db->insert('tested', array('users_id'=>$users_id, 'date'=>$tested_date, 'score'=>$tested_score, 'is_global'=>1));
        return $this->db->insert_id();
    }

    public function get_likes_by_offers_id($offers_id)
    {
        $this->db->select('*');
        $this->db->from('likes');
        $this->db->where('offers_id', $offers_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function is_liked($offers_id, $users_id)
    {
        $this->db->select('unliked');
        $this->db->from('likes');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
            return false;
        $result_array = $query->result_array();
        return ($result_array[0]['unliked'] == 0);
    }


    public function liked_count($offers_id)
    {
        $this->db->select('*');
        $this->db->from('likes');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('unliked', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }    

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    public function like_offer($users_id, $offers_id)
    {
        $this->db->select('*');
        $this->db->from('likes');
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        if ($query->num_rows() >0) 
        {
            $data = array('unliked'=> 0, 'date'=> date("Y-m-d H:i:s"));
            $this->db->where('offers_id', $offers_id);
            $this->db->where('users_id', $users_id);
            return $this->db->update('likes', $data);
        }
        
        $data = array('users_id'=> $users_id, 'offers_id'=> $offers_id, 'date'=> date("Y-m-d H:i:s"));
		$insert = $this->db->insert('likes', $data);
	    return $insert;
	}

    public function unlike_offer($users_id, $offers_id)
    {
        $data = array('unliked'=> 1, 'date'=> date("Y-m-d H:i:s"));
        $this->db->where('offers_id', $offers_id);
        $this->db->where('users_id', $users_id);
        return $this->db->update('likes', $data);
    }
}