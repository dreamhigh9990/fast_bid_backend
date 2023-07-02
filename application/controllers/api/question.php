<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class question extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("questions_model");
    }

    function get_questions_count_get()
    {
        $count = $this->questions_model->count_questions();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_questions_detail_get()
    {
        if (!$this->get('questions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $questions_id = $this->get('questions_id');

        $this->load->model("questions_model");
        if ($questions_id > 0) {
            $questions_detail = $this->questions_model->get_questions_by_id($questions_id);
            $questions_detail = $questions_detail[0];

            $this->response(array("status" => 200, "questions_detail" => $questions_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid questions ID"), 200);
        }
    }

    function get_questions_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $questions_id_like = $this->get('questions_id_like');
        $questions_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("questions_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->questions_model->count_questions($questions_id_like, $questions_template_like));
        header('link: _');
        $users = $this->questions_model->get_questions($questions_id_like, $questions_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_question_post()
    {
        if (!$this->post('questions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $questions_id = $this->post('questions_id');

        $this->load->model("questions_model");
        if ($questions_id > 0) {
            $this->questions_model->delete_question($questions_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the question"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid questions ID"), 200);
        }
    }

    function add_new_question_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("questions_model");
        $result = $this->questions_model->store_question(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new question"), 200);
    }

    function update_question_post()
    {
        if (!$this->post('questions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $questions_id = $this->post('questions_id');

        $data_for_questions = [];

        if ($this->post('template'))
            $data_for_questions['template'] = $this->post('template');


        $this->load->model("questions_model");
        if ($this->questions_model->update_question($questions_id, $data_for_questions) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the question"), 200);
        }
    }
}
