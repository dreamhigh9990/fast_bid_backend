<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class videos extends REST_Controller
{	
	private $youtube;
	public function __construct()
	{
		parent::__construct();

		$DEVELOPER_KEY = $this->config->item('youtube_api_key');
		$client = new Google_Client();
		$client->setDeveloperKey($DEVELOPER_KEY);
		$this->youtube = new Google_Service_YouTube($client);
	}

	function get_videos_detail_post()
	{
		if(!$this->post('users_id') or !$this->post('vsentences_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 		= $this->post('users_id');
		$vsentences_id	= $this->post('vsentences_id');
		
		$this->load->model("videos_model");
		$this->load->model("vsentences_model");
		$this->load->model("users_model");
		$user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>400, "message"=>"Invalid users id"), 200);

		$vsentence = $this->vsentences_model->get_vsentences_by_id($vsentences_id);
		if (!$vsentence || count($vsentence) <= 0)
			$this->response(array("status"=>400, "message"=>"No info for that vsentences id"), 200);

		$vsentence = $vsentence[0];
		$video = array();
		if ($vsentence['videos_id'] > 0)
			$video = $this->get_videos_detailed_info($users_id, $vsentence['videos_id'], $user['target_language']);

		// $this->response(array("status"=>400, "video"=>$video), 200);

		$this->response(array("status"=>200, 
			"vsentence"=>
				array(
					"sentences_id"=>$vsentence['vsentences_id'],
					"source_text"=>htmlspecialchars_decode($vsentence[$vsentence['main']], ENT_QUOTES),
					"target_text"=>htmlspecialchars_decode($vsentence[$user['target_language']], ENT_QUOTES),
					"viewed_count"=>$vsentence['viewed_count'],
					"reviewed_count"=>$vsentence['reviewed_count'],
					), 
			"video"=>$video), 200);
	}

	function get_videos_detailed_info($users_id, $videos_id, $target_language)
	{
		$this->load->model("videos_model");
		$this->load->model("vsentences_model");
		$video = $this->videos_model->get_video_by_id($videos_id);
		if (!$video || count($video) <= 0)
			return null;

		$video = $video[0];
		$sentences = $this->vsentences_model->get_vsentences_by_users_id_and_videos_id($users_id, $videos_id);
		$return_sentences = array();
		foreach ($sentences as $key => $sentence) {
			$return_sentences[$key]["vsentences_id"]=$sentence['vsentences_id'];
			$return_sentences[$key]["source_text"]=htmlspecialchars_decode($sentence[$sentence['main']], ENT_QUOTES);
			// temp code
            if ($target_language == 'zh-CN' && trim($sentence[$target_language]) == "")
                $sentence[$target_language] = $sentence['zh'];
			$return_sentences[$key]["target_text"]=htmlspecialchars_decode($sentence[$target_language], ENT_QUOTES);
			$return_sentences[$key]["viewed_count"]=$sentence['viewed_count'];
			$return_sentences[$key]["reviewed_count"]=$sentence['reviewed_count'];
			$return_sentences[$key]["reviewed_by_user"]=$sentence['reviewed_by_user'];
		}
		$video['vsentences'] = $return_sentences;
		return $video;
	}

	function get_videos_list_get()
	{
		// if(!$this->get('token') or !$this->get('user_name'))
  //       {
  //           $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
  //       }
        $page = $this->get('_page'); $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit'); $limit = ($limit) ? $limit : 10;
        $videos_id_like = $this->get('videos_id_like');
        $videos_link_like = $this->get('videos_link_like');
        $videos_title_like = $this->get('videos_title_like');
        // $en_like = $this->get('en_like');
        // $zh_CN_like = $this->get('zh-CN_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
		
		$this->load->model("videos_model");
		header('Access-Control-Expose-Headers: X-Total-Count, Link');
		header('x-total-count: '.$this->videos_model->count_videos($videos_id_like, $videos_link_like, $videos_title_like));
		header('link: _');
		$videos = $this->videos_model->get_videos($videos_id_like, $videos_link_like, $videos_title_like, $limit, $page * $limit, $sort, $order);
		$this->response($videos, 200);
	}

	function delete_video_post()
	{
		if(!$this->post('videos_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
		
		$videos_id = $this->post('videos_id');
		
		$this->load->model("videos_model");
		if ($videos_id > 0) {
			$this->videos_model->delete_videos($videos_id);
			$this->response(array("status"=>200, "message"=>"Successfully deleted the video"), 200);
		} else {
			$this->response(array("status"=>400, "message"=>"Invalid Videos ID"), 200);
		}
	}

	function search_youtube_video_post()
	{
		if(!$this->post('query') or !$this->post('max_count'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

        $query = $this->post('query');
        $max_count = $this->post('max_count');

        try {
			$searchResponse = $this->youtube->search->listSearch('id, snippet', array(
				'type' => 'video',
				'videoCaption' => 'closedCaption',
				'q' => $query,
				'maxResults' => $max_count
				));
			foreach ($searchResponse['items'] as $i => $searchResult) {
				$data[$i]['videoId'] = $searchResult['id']['videoId'];
				$data[$i]['title'] = $searchResult['snippet']['title'];
				$data[$i]['thumbnail'] = $searchResult['snippet']['thumbnails']['default'];
			}

			$this->response(array("status"=>200, "videos"=>$data), 200);
		} catch (Google_Service_Exception $e) {
			$this->response(array("status"=>400, "message"=>"Google Service Exception occured while searching", "error"=>$e->getMessage()), 200);
		} catch (Google_Exception $e) {
			$this->response(array("status"=>401, "message"=>"Google Exception occured while searching", "error"=>$e->getMessage()), 200);
		}
	}

	public function add_new_video_post()
	{
		if(!$this->post('url'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }

		$data['isvalid'] = FALSE;
		//if save button was clicked, get the data sent via post
		$url = $this->post('url');

		$video_id = explode("?v=", $url); // For videos like http://www.youtube.com/watch?v=...
		if (empty($video_id[1]))
	  		$video_id = explode("/v/", $url); // For videos like http://www.youtube.com/watch/v/..
		$video_id = explode("&", $video_id[1]); // Deleting any other params
		$video_id = $video_id[0];

		$videoResponse = $this->youtube->videos->listVideos('snippet', array('id' => $video_id));

		//add to vsentences table

		$title = $videoResponse['items'][0]['snippet']['title'];
		$thumbnail = $videoResponse['items'][0]['snippet']['thumbnails']['default']['url'];

		$data = array(
		    'link' => $video_id,
		    'title' => $title,
		    'reviewed_count' => 0,
			'image' => $thumbnail,
			'date' =>date('Y-m-d H:i:s'),
		);
		$this->load->model("videos_model");
		$this->load->model("vsentences_model");
		$new_id = $this->videos_model->store_videos($data);
		$data['new_id'] = $new_id;

		$this->store_vsentences($video_id, $new_id);

		$this->response(array("status"=>200, "video"=>$data, "message"=>"Successfully added"), 200);
		// $this->response(array("status"=>401, "message"=>"Failed to add the sentence"), 200);
	}

	private function store_vsentences($video_id, $new_id)
	{
		$captions = $this->youtube->captions->listCaptions("snippet", $video_id);
		$all_sentences = array();
		foreach ($captions as $i => $caption) {
			$trackKind = $caption['snippet']['trackKind'];
			if ($trackKind === 'standard')
			{
				$lang = $caption['snippet']['language'];
				$this->vsentences_model->add_lang_column($lang);
				$all_sentences[$lang] = array();
				$sentences = @simplexml_load_file('http://video.google.com/timedtext?lang='.$lang.'&v='.$video_id, 'SimpleXMLElement', LIBXML_NOWARNING & LIBXML_NOERROR);
				if (!$sentences)
					continue;
				foreach ($sentences as $sentence) {
					$start = floatval($sentence['start']) * 1000; //milliseconds unit
					$dur = floatval($sentence['dur']) * 1000; //milliseconds unit
					$data = array(
					'main'      => 'en',
					'videos_id' => $new_id,
					'start_pos' => $start,
					'duration'  => $dur,
					$lang       => (string)$sentence,
					'date'      => (new DateTime("now", new DateTimeZone('UTC')))->format('Y-m-d H:i:s')
					);
					$all_sentences[$lang][(string)$start] = $data;
				}
			}
		}

		$this->merge_video_sentences($all_sentences);
	}

	private function merge_video_sentences($all_sentences) {
		$merged_sentences = array();
		foreach ($all_sentences as $lang => $lang_sentences) {
			foreach ($lang_sentences as $start => $sentence) {
				if (isset($merged_sentences[$start])) {
					$merged_sentences[$start][$lang] = $sentence[$lang];
				} else {
					$merged_sentences[$start] = $sentence;
				}
			}
		}

		foreach ($merged_sentences as $key => $sentence) {
			$this->vsentences_model->store_vsentences($sentence);
		}
	}
}