<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class conclusion extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("conclusions_model");
    }

    function get_conclusions_count_get()
    {
        $count = $this->conclusions_model->count_conclusions();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_conclusions_detail_get()
    {
        if (!$this->get('conclusions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $conclusions_id = $this->get('conclusions_id');

        $this->load->model("conclusions_model");
        if ($conclusions_id > 0) {
            $conclusions_detail = $this->conclusions_model->get_conclusions_by_id($conclusions_id);
            $conclusions_detail = $conclusions_detail[0];

            $this->response(array("status" => 200, "conclusions_detail" => $conclusions_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid conclusions ID"), 200);
        }
    }

    function get_conclusions_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $conclusions_id_like = $this->get('conclusions_id_like');
        $conclusions_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("conclusions_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->conclusions_model->count_conclusions($conclusions_id_like, $conclusions_template_like));
        header('link: _');
        $users = $this->conclusions_model->get_conclusions($conclusions_id_like, $conclusions_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_conclusion_post()
    {
        if (!$this->post('conclusions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $conclusions_id = $this->post('conclusions_id');

        $this->load->model("conclusions_model");
        if ($conclusions_id > 0) {
            $this->conclusions_model->delete_conclusion($conclusions_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the conclusion"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid conclusions ID"), 200);
        }
    }

    function add_new_conclusion_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("conclusions_model");
        $result = $this->conclusions_model->store_conclusion(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new conclusion"), 200);
    }

    function update_conclusion_post()
    {
        if (!$this->post('conclusions_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $conclusions_id = $this->post('conclusions_id');

        $data_for_conclusions = [];

        if ($this->post('template'))
            $data_for_conclusions['template'] = $this->post('template');


        $this->load->model("conclusions_model");
        if ($this->conclusions_model->update_conclusion($conclusions_id, $data_for_conclusions) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the conclusion"), 200);
        }
    }
}
