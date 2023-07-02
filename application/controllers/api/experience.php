<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class experience extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("experiences_model");
    }

    function get_experiences_count_get()
    {
        $count = $this->experiences_model->count_experiences();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_experiences_detail_get()
    {
        if (!$this->get('experiences_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $experiences_id = $this->get('experiences_id');

        $this->load->model("experiences_model");
        if ($experiences_id > 0) {
            $experiences_detail = $this->experiences_model->get_experiences_by_id($experiences_id);
            $experiences_detail = $experiences_detail[0];

            $this->response(array("status" => 200, "experiences_detail" => $experiences_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid experiences ID"), 200);
        }
    }

    function get_experiences_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $experiences_id_like = $this->get('experiences_id_like');
        $experiences_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("experiences_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->experiences_model->count_experiences($experiences_id_like, $experiences_template_like));
        header('link: _');
        $users = $this->experiences_model->get_experiences($experiences_id_like, $experiences_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_experience_post()
    {
        if (!$this->post('experiences_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $experiences_id = $this->post('experiences_id');

        $this->load->model("experiences_model");
        if ($experiences_id > 0) {
            $this->experiences_model->delete_experience($experiences_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the experience"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid experiences ID"), 200);
        }
    }

    function add_new_experience_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("experiences_model");
        $result = $this->experiences_model->store_experience(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new experience"), 200);
    }

    function update_experience_post()
    {
        if (!$this->post('experiences_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $experiences_id = $this->post('experiences_id');

        $data_for_experiences = [];

        if ($this->post('template'))
            $data_for_experiences['template'] = $this->post('template');


        $this->load->model("experiences_model");
        if ($this->experiences_model->update_experience($experiences_id, $data_for_experiences) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the experience"), 200);
        }
    }
}
