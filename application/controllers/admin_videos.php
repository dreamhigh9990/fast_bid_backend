<?php
if (!file_exists(__DIR__ . '/../../vendor/autoload.php')) {
  throw new Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/../../vendor/autoload.php';

class Admin_videos extends CI_Controller {

  /**
  * name of the folder responsible for the views
  * which are manipulated by this controller
  * @constant string
  */
  const VIEW_FOLDER = 'admin/videos';
  private $youtube;

  public function __construct()
  {
    parent::__construct();

    if(!$this->session->userdata('is_logged_in')){
      redirect('admin/login');
    }

    $this->load->model('courses_model');
    $this->load->model('chapters_model');
    $this->load->model('books_model');
    $this->load->model('sentences_model');
    $this->load->model('videos_model');
    $this->load->model('vsentences_model');

    $DEVELOPER_KEY = $this->config->item('youtube_api_key');
    $client = new Google_Client();
    $client->setDeveloperKey($DEVELOPER_KEY);
    $this->youtube = new Google_Service_YouTube($client);

  }

  /**
  * Load the main view with all the current model model's data.
  * @return void
  */
  public function index()
  {
    //all the posts sent by the view
    $search_string = $this->input->post('search_string');
    $order = $this->input->post('order');
    $order_type = $this->input->post('order_type');

    //pagination settings
    $config['per_page'] = 15;

    $config['base_url'] = base_url().'admin/videos';
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 20;
    $config['full_tag_open'] = '<ul>';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a>';
    $config['cur_tag_close'] = '</a></li>';

    //limit end
    $page = $this->uri->segment(3);

    //math to get the initial record to be select in the database
    $limit_end = ($page * $config['per_page']) - $config['per_page'];
    if ($limit_end < 0){
      $limit_end = 0;
    }

    //if order type was changed
    if($order_type){
      $filter_session_data['order_type'] = $order_type;
    }
    else{
      //we have something stored in the session?
      if($this->session->userdata('order_type')){
          $order_type = $this->session->userdata('order_type');
      }else{
          //if we have nothing inside session, so it's the default "Asc"
          $order_type = 'Asc';
      }
    }
    //make the data type var avaible to our view
    $data['order_type_selected'] = $order_type;

    //we must avoid a page reload with the previous session data
    //if any filter post was sent, then it's the first time we load the content
    //in this case we clean the session filter data
    //if any filter post was sent but we are in some page, we must load the session data

    //filtered && || paginated
    if($search_string !== false && $order !== false || $this->uri->segment(3) == true){

      /*
      The comments here are the same for line 79 until 99

      if post is not null, we store it in session data array
      if is null, we use the session data already stored
      we save order into the the var to load the view with the param already selected
      */
      if($search_string){
          $filter_session_data['search_string_selected'] = $search_string;
      }else{
          $search_string = $this->session->userdata('search_string_selected');
      }
      $data['search_string_selected'] = $search_string;

      if($order){
          $filter_session_data['order'] = $order;
      }
      else{
          $order = $this->session->userdata('order');
      }
      $data['order'] = $order;

      //save session data into the session
      if(isset($filter_session_data)){
        $this->session->set_userdata($filter_session_data);
      }

      //fetch sql data into arrays
      $data['count_videos']= $this->videos_model->count_videos_old($search_string, $order);
      $config['total_rows'] = $data['count_videos'];

      //fetch sql data into arrays
      if($search_string){
          if($order){
              $data['videos'] = $this->videos_model->get_videos_old($search_string, $order, $order_type, $config['per_page'],$limit_end);
          }else{
              $data['videos'] = $this->videos_model->get_videos_old($search_string, '', $order_type, $config['per_page'],$limit_end);
          }
      }else{
          if($order){
              $data['videos'] = $this->videos_model->get_videos_old('', $order, $order_type, $config['per_page'],$limit_end);
          }else{
              $data['videos'] = $this->videos_model->get_videos_old('', '', $order_type, $config['per_page'],$limit_end);
          }
      }

    }else{

      //clean filter data inside section
      $filter_session_data['videos_selected'] = null;
      $filter_session_data['search_string_selected'] = null;
      $filter_session_data['order'] = null;
      $filter_session_data['order_type'] = null;
      $this->session->set_userdata($filter_session_data);

      //pre selected options
      $data['search_string_selected'] = '';
      $data['order'] = 'id';

      //fetch sql data into arrays
      $data['count_videos']= $this->videos_model->count_videos_old();
      $data['videos'] = $this->videos_model->get_videos_old('', '', $order_type, $config['per_page'],$limit_end);
      $config['total_rows'] = $data['count_videos'];

    }//!isset($search_string) && !isset($order)

    //initializate the panination helper
    $this->pagination->initialize($config);

    //load the view
    $data['main_content'] = 'admin/videos/list';
    $this->load->view('includes/template', $data);

  }//index

  public function search()
  {
    $keywords = $this->input->get_post('keywords');
    $maxCount = $this->input->get_post('max_count');

    try {
      $searchResponse = $this->youtube->search->listSearch('id, snippet', array(
        'type' => 'video',
        'videoCaption' => 'closedCaption',
        'q' => $keywords,
        'maxResults' => $maxCount
      ));
      foreach ($searchResponse['items'] as $i => $searchResult) {
        $data[$i]['videoId'] = $searchResult['id']['videoId'];
        $data[$i]['title'] = $searchResult['snippet']['title'];
        $data[$i]['thumbnail'] = $searchResult['snippet']['thumbnails']['default'];
      }

      echo json_encode($data);
    } catch (Google_Service_Exception $e) {
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Google_Exception $e) {
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function add()
  {
    $data['isvalid'] = FALSE;
    //if save button was clicked, get the data sent via post
    $url = $this->input->get_post('url');

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
        'image' => $thumbnail
    );
    $new_id = $this->videos_model->store_videos($data);
    $data['new_id'] = $new_id;

    $this->store_vsentences($video_id, $new_id);
    echo json_encode($data);
  }

  public function update()
  {
    $id = $this->uri->segment(4);
  }

  public function delete()
  {
    $this->videos_model->delete_videos($this->input->get_post('id'));
    echo TRUE;
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
?>