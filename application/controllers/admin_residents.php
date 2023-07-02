<?php

class Admin_residents extends CI_Controller
{
  const VIEW_FOLDER = 'admin/residents';

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model('residents_model');

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
    $residents = $this->residents_model->get_residents_list();
    $data['residents'] = $residents;
    //load the view
    $data['main_content'] = 'admin/residents/list';
    $this->load->view('includes/template', $data);
  }

  public function answers()
  {
    $residents_id = $this->uri->segment(4);
    $question = $this->residents_model->get_question_by_id($residents_id);
    if (!$question || count($question) <= 0)
      return;
    $question = $question[0];
    if ($question['asker_id'] != 1)
        $question['asker'] = $this->users_model->get_users_name_by_users_id($question['asker_id']);
      else
        $question['asker'] = 'Admin';
    $answers = $this->answers_model->get_answers($residents_id);
    foreach ($answers as $key => $answer) {
      if ($answer['answerer_id'] != 1)
        $answers[$key]['answerer'] = $this->users_model->get_users_name_by_users_id($answer['answerer_id']);
      else
        $answers[$key]['answerer'] = 'Admin';
    }
    $data['answers'] = $answers;
    $data['question'] = $question;
    $data['main_content'] = 'admin/answers/list';
    $this->load->view('includes/template', $data);
  }

  public function upload_image()
  {
    if (isset($_FILES['file'])) {
      $config['upload_path'] = './upload/residents/images';
      $config['overwrite'] = FALSE;
      $config['allowed_types'] = '*';
      $config['max_size'] = '50000';
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config, 'question_image');
      if ($this->question_image->do_upload('file')) {
        $attached_photo = $this->question_image->data();
        echo $attached_photo["file_name"];
        return;
      }
    }
    echo 'failed';
  }

  public function delete_image()
  {
    unlink('./upload/residents/images/' . $this->input->get_post('image'));
    echo base_url() . 'upload/residents/images/' . $this->input->get_post('image');
  }

  public function get_residents_list()
  {
    $results = $this->residents_model->get_residents_list();
    echo json_encode($results);
  }

  public function get_question_info()
  {
    $id = $this->input->get_post('id');
    $question = $this->residents_model->get_question_by_id($id);
    if (count($question) > 0)
      $question = $question[0];
    echo json_encode($question);
  }

  public function add()
  {
    $data_for_question = [];
    $data_for_question['lang'] = $this->input->get_post('lang');
    $data_for_question['answer_lang'] = $this->input->get_post('target');
    $data_for_question['question'] = $this->input->get_post('question');
    $data_for_question['description'] = $this->input->get_post('description');
    $data_for_question['images'] = $this->input->get_post('images');
    $data_for_question['asker_id'] = 1;//by admin
    $data_for_question['date'] = date('Y-m-d H:i:s');
    $data_for_answer['answerer_id'] = 1;//by admin
    $data_for_answer['liked_count'] = 0;
    $data_for_answer['is_correct_answer'] = 1;
    $data_for_answer['answer'] = $this->input->get_post('answer');
    $data_for_answer['date'] = date('Y-m-d H:i:s');
    $question_id = $this->residents_model->store_question($data_for_question);
    $data_for_answer['residents_id'] = $question_id;
    if (trim($data_for_answer['answer']) != "")
      $this->answers_model->store_answers($data_for_answer);
    echo $question_id;
  }

  public function update()
  {
    $data_for_question = [];
    $residents_id = $this->input->get_post('residents_id');
    $data_for_question['lang'] = $this->input->get_post('lang');
    $data_for_question['answer_lang'] = $this->input->get_post('target');
    $data_for_question['question'] = $this->input->get_post('question');
    $data_for_question['description'] = $this->input->get_post('description');
    $data_for_question['images'] = $this->input->get_post('images');
    $data_for_question['asker_id'] = 1;//by admin
    $data_for_question['date'] = date('Y-m-d H:i:s');
    $result = $this->residents_model->update_residents($residents_id, $data_for_question);
    echo $result;
  }

  public function update_answer()
  {
    $data_for_answer = [];
    $answers_id = $this->input->get_post('answers_id');
    $data_for_answer['answer'] = $this->input->get_post('answer');
    $data_for_answer['is_correct_answer'] = $this->input->get_post('is_correct_answer');
    $result = $this->answers_model->update_answers($answers_id, $data_for_answer);
    echo $result;
  }

  public function delete()
  {
    $id = $this->input->get_post('id');
    $this->residents_model->delete_question($id);
    echo $id;
  }

  public function delete_answer()
  {
    $id = $this->input->get_post('id');
    $this->answers_model->delete_answer($id);
    echo $id;
  }
}

?>
