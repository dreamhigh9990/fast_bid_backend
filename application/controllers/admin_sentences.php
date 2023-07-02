<?php

class Admin_sentences extends CI_Controller
{

  /**
   * name of the folder responsible for the views
   * which are manipulated by this controller
   * @constant string
   */
  const VIEW_FOLDER = 'admin/sentences';
  private $chapters_id = 0;

  /**
   * Responsable for auto load the model
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model('courses_model');
    $this->load->model('chapters_model');
    $this->load->model('books_model');
    $this->load->model('sentences_model');

    if (!$this->session->userdata('is_logged_in')) {
      redirect('admin/login');
    }
  }

  /**
   * Load the main view with all the current model model's data.
   * @return void
   */
  public function index()
  {
    $this->chapters_id = $this->uri->segment(3);

    $data['sentences'] = $this->sentences_model->get_sentences($this->chapters_id);

    //load the view
    $data['main_content'] = 'admin/sentences/list';

    //side-menu highlight

    $chapters = $this->chapters_model->get_chapters_by_id($this->chapters_id);
    if ($chapters[0]['books_id'] > 0) {
      $books = $this->books_model->get_books_by_id($chapters[0]['books_id']);

      if ($books[0]['courses_id'] <= 0) {
        $data['ibooks_selected']['book'] = $chapters[0]['books_id'];
        $data['ibooks_selected']['chapter'] = $this->chapters_id;
      } else {
        $data['main_selected']['chapter'] = $this->chapters_id;
        $data['main_selected']['book'] = $chapters[0]['books_id'];
        $data['main_selected']['course'] = $books[0]['courses_id'];
      }
    } else {
      $data['ichapters_selected']['chapter'] = $this->chapters_id;
    }
    $this->load->view('includes/template', $data);
  }

  public function get_sentence_list()
  {
    $results = $this->sentences_model->get_sentence_list($this->chapters_id);
    echo json_encode($results);
  }

  public function add()
  {
    $data['success'] = 0;
    //if save button was clicked, get the data sent via post
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
      //form validation
      $post_data = $this->input->post();

      $post_data['source'] = trim($post_data['source']);
      $post_data['target'] = trim($post_data['target']);

      $data_for_sentences = array(
        'chapters_id' => $post_data['chapters_id'],
        'date' => date('Y-m-d H:i:s')
      );
      if ($post_data['direction'] == "EN->CN") {
        $data_for_sentences['s_en'] = $post_data['source'];
        $data_for_sentences['d_zh-CN'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'en';
        $data_for_sentences['d_language'] = 'zh-CN';
      } else if ($post_data['direction'] == "EN->GE") {
        $data_for_sentences['s_en'] = $post_data['source'];
        $data_for_sentences['d_de'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'en';
        $data_for_sentences['d_language'] = 'de';
      } else if ($post_data['direction'] == "CN->EN") {
        $data_for_sentences['s_zh-CN'] = $post_data['source'];
        $data_for_sentences['d_en'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'zh-CN';
        $data_for_sentences['d_language'] = 'de';
      } else if ($post_data['direction'] == "GE->EN") {
        $data_for_sentences['s_de'] = $post_data['source'];
        $data_for_sentences['d_en'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'GE';
        $data_for_sentences['d_language'] = 'en';
      } else if ($post_data['direction'] == "CN->GE") {
        $data_for_sentences['s_zh-CN'] = $post_data['source'];
        $data_for_sentences['d_de'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'zh-CN';
        $data_for_sentences['d_language'] = 'de';
      } else if ($post_data['direction'] == "GE->CN") {
        $data_for_sentences['s_de'] = $post_data['source'];
        $data_for_sentences['d_zh-CN'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'de';
        $data_for_sentences['d_language'] = 'zh-CN';
      }

      //if the form has passed through the validation
      $upload = FALSE;

      if (isset($_FILES['image'])) {
        $config['upload_path'] = './upload/sentences/image/';
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
        $config['upload_path'] = './upload/sentences/audio/';
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

      //if the insert has returned true then we show the flash message
      $last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
      if ($last_inserted_id >= 0) {
        $data['id'] = $last_inserted_id;
        $data['source'] = $post_data['source'];
        $data['target'] = $post_data['target'];
        $data['direction'] = $post_data['direction'];
        $data['image'] = isset($data_for_sentences['image']) ? base_url() . 'upload/sentences/image/' . $data_for_sentences['image'] : '';
        $data['audio'] = isset($data_for_sentences['audio']) ? base_url() . 'upload/sentences/audio/' . $data_for_sentences['audio'] : '';
        $data['success'] = 1;
      }
    }
    echo json_encode($data);
  }

  /**
   * Update item by his id
   * @return void
   */
  public function update()
  {
    $data['success'] = 0;
    //if save button was clicked, get the data sent via post
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
      $post_data = $this->input->post();
      $id = $post_data['sentences_id'];
      $post_data['source'] = trim($post_data['source']);
      $post_data['target'] = trim($post_data['target']);

      $data_for_sentences = array(
        'chapters_id' => $post_data['chapters_id'],
        'date' => date('Y-m-d H:i:s')
      );
      if ($post_data['direction'] == "EN->CN") {
        $data_for_sentences['s_en'] = $post_data['source'];
        $data_for_sentences['d_zh-CN'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'en';
        $data_for_sentences['d_language'] = 'zh-CN';
      } else if ($post_data['direction'] == "EN->GE") {
        $data_for_sentences['s_en'] = $post_data['source'];
        $data_for_sentences['d_de'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'en';
        $data_for_sentences['d_language'] = 'de';
      } else if ($post_data['direction'] == "CN->EN") {
        $data_for_sentences['s_zh-CN'] = $post_data['source'];
        $data_for_sentences['d_en'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'zh-CN';
        $data_for_sentences['d_language'] = 'en';
      } else if ($post_data['direction'] == "GE->EN") {
        $data_for_sentences['s_de'] = $post_data['source'];
        $data_for_sentences['d_en'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'de';
        $data_for_sentences['d_language'] = 'en';
      } else if ($post_data['direction'] == "CN->GE") {
        $data_for_sentences['s_zh-CN'] = $post_data['source'];
        $data_for_sentences['d_de'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'zh-CN';
        $data_for_sentences['d_language'] = 'de';
      } else if ($post_data['direction'] == "GE->CN") {
        $data_for_sentences['s_de'] = $post_data['source'];
        $data_for_sentences['d_zh-CN'] = $post_data['target'];
        $data_for_sentences['s_language'] = 'de';
        $data_for_sentences['d_language'] = 'zh-CN';
      }

      //if the form has passed through the validation
      $upload = FALSE;

      if (isset($_FILES['image'])) {
        $config['upload_path'] = './upload/sentences/image/';
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
        $config['upload_path'] = './upload/sentences/audio/';
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

      if ($this->sentences_model->update_sentences($id, $data_for_sentences)) {
        $sentence = $this->sentences_model->get_sentences_by_id($id)[0];
        $data['id'] = $id;
        $data['source'] = $post_data['source'];
        $data['target'] = $post_data['target'];
        $data['direction'] = $post_data['direction'];
        $data['image'] = isset($sentence['image']) ? base_url() . 'upload/sentences/image/' . $sentence['image'] : '';
        $data['audio'] = isset($sentence['audio']) ? base_url() . 'upload/sentences/audio/' . $sentence['audio'] : '';
        $data['success'] = 1;
      }

      /*          //if we are updating, and the data did not pass trough the validation
                //the code below wel reload the current data
                $data['sentences'] = $this->sentences_model->get_sentences_by_id($id);

                //side-menu highlight
                $chapters_id = $data['sentences'][0]['chapters_id'];
                $chapters = $this->chapters_model->get_chapters_by_id($chapters_id);
                if ($chapters[0]['books_id'] > 0) {
                  $books = $this->books_model->get_books_by_id($chapters[0]['books_id']);

                  if ($books[0]['courses_id'] <= 0) {
                    $data['ibooks_selected']['book'] = $chapters[0]['books_id'];
                    $data['ibooks_selected']['chapter'] = $chapters_id;
                  } else {
                    $data['main_selected']['chapter'] = $chapters_id;
                    $data['main_selected']['book'] = $chapters[0]['books_id'];
                    $data['main_selected']['course'] = $books[0]['courses_id'];
                  }
                } else {
                  $data['ichapters_selected']['chapter'] = $chapters_id;
                }*/
    }
    echo json_encode($data);
  }

  /**
   * Delete product by his id
   * @return void
   */
  public function delete()
  {
    //product id
    $post_data = $this->input->post();
    $id = $post_data['id'];
    $this->sentences_model->delete_sentences($id);
    echo $id;
  }
}

?>