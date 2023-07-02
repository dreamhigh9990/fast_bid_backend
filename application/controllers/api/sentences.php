<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class sentences extends REST_Controller
{	
	function get_sentence_detail_post()
	{
		if(!$this->post('users_id') or !$this->post('sentences_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$sentences_id	= $this->post('sentences_id');
		
		$this->load->model("sentences_model");

		$sentence = $this->sentences_model->get_sentences_by_id($sentences_id);
		if (!$sentence || count($sentence) <= 0)
			$this->response(array("status"=>400, "message"=>"No info for that sentences id"), 200);

		$sentence = $sentence[0];
		$chapter = array();
		if ($sentence['chapters_id'] > 0)
			$chapter = $this->get_chapter_detailed_info_by_id($users_id, $sentence['chapters_id']);

		$this->response(array("status"=>200, 
			"sentence"=>
				array(
					"sentences_id"=>$sentence['sentences_id'],
					"source_text"=>$sentence[$sentence['s_language']],
					"target_text"=>$sentence[$sentence['d_language']],
					"viewed_count"=>$sentence['viewed_count'],
					"reviewed_count"=>$sentence['reviewed_count'],
					), 
			"chapter"=>$chapter), 200);
	}

	function get_chapter_detailed_info_by_id($users_id, $chapters_id)
	{
		$this->load->model("chapters_model");
		$this->load->model("sentences_model");
		$chapter = $this->chapters_model->get_chapters_by_id($chapters_id);
		if (!$chapter || count($chapter) <= 0)
			return null;

		$chapter = $chapter[0];
		$sentences = $this->sentences_model->get_sentences_by_users_id_and_chapters_id($users_id, $chapters_id);
		$return_sentences = array();
		foreach ($sentences as $key => $sentence) {
			$return_sentences[$key]["sentences_id"]=$sentence['sentences_id'];
			$return_sentences[$key]["source_text"]=$sentence[$sentence['s_language']];
			$return_sentences[$key]["target_text"]=$sentence[$sentence['d_language']];
			$return_sentences[$key]["viewed_count"]=$sentence['viewed_count'];
			$return_sentences[$key]["reviewed_count"]=$sentence['reviewed_count'];
			$return_sentences[$key]["reviewed_by_user"]=$sentence['reviewed_by_user'];
		}
		$chapter['sentences'] = $return_sentences;
		return $chapter;
	}

	function get_sentences_list_get()
	{
		// if(!$this->get('token') or !$this->get('user_name'))
  //       {
  //           $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
  //       }
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $chapters_id = $this->get('chapters_id');
        $sentences_id_like = $this->get('sentences_id_like');
        $en_like = $this->get('en_like');
        $zh_CN_like = $this->get('zh-CN_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$this->load->model("sentences_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->sentences_model->count_sentences($chapters_id, $sentences_id_like, $en_like, $zh_CN_like));
		header('link: _');
		$sentences = $this->sentences_model->get_sentences($chapters_id, $sentences_id_like, $en_like, $zh_CN_like, $limit, $page * $limit, $sort, $order);
		$this->response($sentences, 200);
	}

	function delete_sentence_post()
	{
		if(!$this->post('sentences_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$sentences_id = $this->post('sentences_id');
		
		$this->load->model("sentences_model");
		if ($sentences_id > 0) {
			$this->sentences_model->delete_sentences($sentences_id);
			$this->response(array("status"=>200, "message"=>"Successfully deleted the sentence"), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid Sentences ID"), 200);
		}
	}

	function add_new_sentence_post()
	{
		if (!$this->post('chapters_id') && !$this->post('s_language') && !$this->post('d_language'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

		$this->load->model("sentences_model");
		$data_for_sentences = array(
			'chapters_id' => $this->post('chapters_id'),
			'date' => date('Y-m-d H:i:s'),
			's_language' => $this->post('s_language'),
			'd_language' => $this->post('d_language'),
			'en' => $this->post('en'),
			'zh-CN' => $this->post('zh-CN')
		);
		
		$upload = FALSE;

		if (isset($_FILES['image'])) {
			$config['upload_path'] = './upload/sentences/images/';
			$config['overwrite'] = FALSE;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config, 'sentences_image');

			if (!$this->sentences_image->do_upload("image")) {
				$upload = FALSE;
			} else {
				$attache_photo = $this->sentences_image->data();
				$upload = TRUE;
			}
		}

		if ($upload == TRUE)
			$data_for_sentences['image'] = $attache_photo["file_name"];

		$upload = FALSE;

		if (isset($_FILES['audio'])) {
			$config['upload_path'] = './upload/sentences/audios/';
			$config['overwrite'] = FALSE;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config, 'sentences_audio');

			if (!$this->sentences_audio->do_upload("audio")) {
				$upload = FALSE;
			} else {
				$attache_audio = $this->sentences_audio->data();
				$upload = TRUE;
			}
		}

		if ($upload == TRUE)
			$data_for_sentences['audio'] = $attache_audio["file_name"];

		$last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
		if ($last_inserted_id >= 0) {
			$this->response(array("status"=>200, "message"=>"Successfully added"), 200);
		}
		$this->response(array("status"=>401, "message"=>"Failed to add the sentence"), 200);
	}

	function update_sentence_post()
	{
		if (!$this->post('sentences_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $sentences_id = $this->post('sentences_id');

        $this->load->model("sentences_model");
		$data_for_sentences = array(
			'sentences_id' => $sentences_id,
			'date' => date('Y-m-d H:i:s'),
			's_language' => $this->post('s_language'),
			'd_language' => $this->post('d_language'),
			'en' => $this->post('en'),
			'zh-CN' => $this->post('zh-CN')
		);

        $upload = FALSE;

		if (isset($_FILES['image'])) {
			$config['upload_path'] = './upload/sentences/images/';
			$config['overwrite'] = FALSE;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config, 'sentences_image');

			if (!$this->sentences_image->do_upload("image")) {
				$upload = FALSE;
			} else {
				$attache_photo = $this->sentences_image->data();
				$upload = TRUE;
			}
		}

		if ($upload == TRUE)
			$data_for_sentences['image'] = $attache_photo["file_name"];

		$upload = FALSE;

		if (isset($_FILES['audio'])) {
			$config['upload_path'] = './upload/sentences/audios/';
			$config['overwrite'] = FALSE;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config, 'sentences_audio');

			if (!$this->sentences_audio->do_upload("audio")) {
				$upload = FALSE;
			} else {
				$attache_audio = $this->sentences_audio->data();
				$upload = TRUE;
			}
		}

		if ($upload == TRUE)
			$data_for_sentences['audio'] = $attache_audio["file_name"];

		$this->load->model("sentences_model");
		if ($this->sentences_model->update_sentences($sentences_id, $data_for_sentences) == TRUE){
			$this->response(array("status"=>200, "message"=>"Successfully updated"), 200);
		} else {
			$this->response(array("status"=>401, "message"=>"Failed to update the sentence"), 200);
		}
	}
}