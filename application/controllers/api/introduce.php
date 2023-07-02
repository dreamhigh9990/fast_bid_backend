<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class introduce extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("introduces_model");
    }

    function get_introduces_count_get()
    {
        $count = $this->introduces_model->count_introduces();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_introduces_detail_get()
    {
        if (!$this->get('introduces_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $introduces_id = $this->get('introduces_id');

        $this->load->model("introduces_model");
        if ($introduces_id > 0) {
            $introduces_detail = $this->introduces_model->get_introduces_by_id($introduces_id);
            $introduces_detail = $introduces_detail[0];

            $this->response(array("status" => 200, "introduces_detail" => $introduces_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid introduces ID"), 200);
        }
    }

    function get_introduces_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $introduces_id_like = $this->get('introduces_id_like');
        $introduces_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("introduces_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->introduces_model->count_introduces($introduces_id_like, $introduces_template_like));
        header('link: _');
        $users = $this->introduces_model->get_introduces($introduces_id_like, $introduces_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_introduce_post()
    {
        if (!$this->post('introduces_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $introduces_id = $this->post('introduces_id');

        $this->load->model("introduces_model");
        if ($introduces_id > 0) {
            $this->introduces_model->delete_introduce($introduces_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the introduce"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid introduces ID"), 200);
        }
    }

    function add_new_introduce_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("introduces_model");
        $result = $this->introduces_model->store_introduce(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new introduce"), 200);
    }

    function update_introduce_post()
    {
        if (!$this->post('introduces_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $introduces_id = $this->post('introduces_id');

        $data_for_introduces = [];

        if ($this->post('template'))
            $data_for_introduces['template'] = $this->post('template');


        $this->load->model("introduces_model");
        if ($this->introduces_model->update_introduce($introduces_id, $data_for_introduces) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the introduce"), 200);
        }
    }
}
