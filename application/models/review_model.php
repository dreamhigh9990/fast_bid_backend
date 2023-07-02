<?php
class Review_model extends CI_Model {
 
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
    public function get_review_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('review');
		$this->db->where('review_id', $id);
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
    public function get_review_by_users_id($users_id)
    {
		$this->db->select('*');
		$this->db->from('review');
        $this->db->where('users_id', $users_id);
		$query = $this->db->get();
		$result_array = $query->result_array(); 	
        return $result_array;
    }

    public function store_review($data)
    {
        $this->db->insert('review', $data);
        return $this->db->insert_id();
    }

    public function delete_review($data)
    {
        $this->db->where($data);
        $this->db->delete('review');
    }

    public function get_review_sentences($users_id, $user_s_lang, $user_d_lang, $from_date, $to_date)
    {
        $reviewed_sentences = array();

        $this->db->select('sentences.*, review.date AS reviewed_date');
        $this->db->from('review, sentences');
        $this->db->where('review.sentences_id > ', 0);
        $this->db->where('review.sentences_id = sentences.sentences_id');
        $this->db->where('review.date >= ', $from_date);
        $time_add = strtotime($to_date) + (3600*24); //add seconds of one day
        $to_date_add = date("Y-m-d", $time_add);
        $this->db->where('review.date <= ', $to_date_add);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        $sentences = $query->result_array();
        foreach ($sentences as $key => $sentence) {
            //$sentences[$key]['source_text'] = $sentence['s_'.$sentence['s_language']];
            //$sentences[$key]['target_text'] = $sentence['d_'.$sentence['d_language']];
            $sentences[$key]['source_text'] = $sentence['zh-CN'];
            $sentences[$key]['target_text'] = $sentence['en'];
            $sentences[$key]['types'] = "sentences";

            if ($sentence['chapters_id'] > 0) {
                $this->load->model("chapters_model");
                $chapter = $this->chapters_model->get_chapter_by_id($sentence['chapters_id']);
                if ($chapter) {
                    $sentences[$key]['chapters_name'] = $chapter['name'];

                    if ($chapter['books_id'] > 0) {
                        $this->load->model("books_model");
                        $book = $this->books_model->get_book_by_id($chapter['books_id']);
                        if ($book) {
                            $sentences[$key]['books_name'] = $book['name'];

                            if ($book['courses_id'] > 0) {
                                $this->load->model("courses_model");
                                $course = $this->courses_model->get_course_by_id($book['courses_id']);
                                if ($course)
                                    $sentences[$key]['courses_name'] = $course['name'];
                            }
                        }
                    }
                }
            }

            // get random sentences for candidates
            $this->db->select('sentences.*');
            $this->db->from('sentences');
            $this->db->where('rand() <', 0.5);
            $this->db->where('sentences_id <>', $sentence['sentences_id']);
            $this->db->limit(5);
            $query = $this->db->get();
            $candidates = $query->result_array();
            // foreach ($candidates as $key2 => $candidate) {
            //     if (trim($candidate['d_'.$candidate['d_language']]) != "")
            //         $candidates[$key2]['target_text'] = $candidate['d_'.$candidate['d_language']];
            // }
            foreach ($candidates as $key2 => $candidate) {
                if (trim($candidate['en']) != "")
                    $candidates[$key2]['target_text'] = $candidate['en'];
            }
            $sentences[$key]['candidates'] = $candidates;
        }
        $reviewed_sentences['sentences'] = $sentences;

        $this->db->select('questions.questions_id, questions.question AS questions_text, answers.answers_id, answers.answer AS answers_text, answers.viewed_count, answers.reviewed_count, questions.answered_count, review.date AS reviewed_date, u.user_name as asker_name');
        $this->db->from('review, questions, answers');
        $this->db->where('review.questions_id > ', 0);
        $this->db->where('review.answers_id > ', 0);
        $this->db->where('review.questions_id = questions.questions_id');
        $this->db->where('questions.questions_id = answers.questions_id');
        $this->db->where('review.answers_id = answers.answers_id');
        $this->db->where('review.date >= ', $from_date);
        $time_add = strtotime($to_date) + (3600*24); //add seconds of one day
        $to_date_add = date("Y-m-d", $time_add);
        $this->db->where('review.date <= ', $to_date_add);
        $this->db->where('review.users_id', $users_id);
        $this->db->join('users u', 'u.users_id=questions.asker_id');
        $query = $this->db->get();
        $discussion = $query->result_array();
        foreach ($discussion as $key => $dis) {
            $discussion[$key]['source_text'] = $dis['questions_text'];
            $discussion[$key]['target_text'] = $dis['answers_text'];
            $discussion[$key]['question_text'] = $dis['questions_text'];
            $discussion[$key]['answer_text'] = $dis['answers_text'];
            $discussion[$key]['types'] = "discussions";

            // get random sentences for candidates
            $this->db->select('*');
            $this->db->from('answers');
            $this->db->where('rand() <', 0.5);
            $this->db->where('answers_id <>', $dis['answers_id']);
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

        $this->db->select('videos.title as video_title, vsentences.*, review.date as reviewed_date');
        $this->db->from('review, videos, vsentences');
        $this->db->where('review.vsentences_id > ', 0);
        $this->db->where('review.vsentences_id = vsentences.vsentences_id');
        $this->db->where('vsentences.videos_id = videos.videos_id');
        $this->db->where('review.date >= ', $from_date);
        $time_add = strtotime($to_date) + (3600*24); //add seconds of one day
        $to_date_add = date("Y-m-d", $time_add);
        $this->db->where('review.date <= ', $time_add);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        $vsentences = $query->result_array();
        foreach ($vsentences as $key => $vsentence) {
            $vsentences[$key]['source_text'] = htmlspecialchars_decode($vsentence[$vsentence['main']], ENT_QUOTES);

            // temp code
            if ($user_d_lang == 'zh-CN' && trim($vsentence[$user_d_lang]) == "")
                $vsentence[$user_d_lang] = $vsentence['zh'];

            $vsentences[$key]['target_text'] = htmlspecialchars_decode($vsentence[$user_d_lang], ENT_QUOTES);
            $vsentences[$key]['types'] = "videos";

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
                if (trim($candidate[$candidate['d_language']]) != "")
                    $candidates[$key2]['target_text'] = $candidate[$candidate['d_language']];
            }
            $vsentences[$key]['candidates'] = $candidates;
        }
        $reviewed_sentences['videos'] = $vsentences;

        return $reviewed_sentences;
    }

    public function get_reviewed_count($users_id, $from_date, $to_date)
    {
        $this->db->select('*');
        $this->db->from('reviewed');
        $this->db->where('users_id', $users_id);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_review_score_history($users_id, $from_date, $to_date)
    {
        $this->db->select('date as reviewed_date, sentences_count, words_count, accuracy');
        $this->db->from('reviewed');
        $this->db->where('users_id', $users_id);
        $this->db->where('date >= ', $from_date);
        $this->db->where('date <= ', $to_date);
        $this->db->order_by('date', 'Asc');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function set_review_score($users_id, $reviewed_date, $reviewed_sentences_count, $reviewed_words_count, $reviewed_accuracy, $success, $from_date, $to_date)
    {
        $this->db->insert('reviewed', array('users_id'=>$users_id, 'date'=>$reviewed_date, 'sentences_count'=>$reviewed_sentences_count, 'words_count'=>$reviewed_words_count, 'accuracy'=>$reviewed_accuracy, 'review_from_date'=>$from_date, 'review_to_date'=>$to_date, 'successed'=>$success));
        return $this->db->insert_id();
    }


    public function get_smart_course($chapters_id) {
        $this->db->select('*');
        $this->db->from('sentences');
        $this->db->or_where('chapters_id', $chapters_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_simular_sentence_list($sentences_id) {
        $this->db->select('sentences.en, sentences.sentences_id');
        $this->db->from('sentences');
        $this->db->where('sentences.sentences_id >=', $sentences_id);
        $this->db->where('sentences.sentences_id <', $sentences_id + 1000);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }

    public function get_random_sentence_list() {
        $this->db->select('sentences.en, sentences.sentences_id');
        $this->db->from('sentences');
        $this->db->limit(1000, 1);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
        
    }

    public function get_sentence_by_id($senteces_id) {
        $this->db->select('*');
        $this->db->from('sentences');
        $this->db->where('sentences_id', $senteces_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function get_favourite_count_per_day() {
        $this->db->select('DATE(date) as date, COUNT(DISTINCT(users_id)) as co_cnt');
        $this->db->from('review');
        $this->db->group_by('DATE(date)');
        $query = $this->db->get();
        $result_array = $query->result_array();     
        return $result_array;
    }

    public function check_sentence_review($sentence_id, $users_id) {
        $this->db->select('*');
        $this->db->from('review');
        $this->db->where('sentences_id', $sentence_id);
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        $result_array = $query->result_array();     
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        } 
    }
}