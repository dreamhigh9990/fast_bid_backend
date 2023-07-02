<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class greeting extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("greetings_model");
    }

    function get_greetings_count_get()
    {
        $count = $this->greetings_model->count_greetings();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_greetings_detail_get()
    {
        if (!$this->get('greetings_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $greetings_id = $this->get('greetings_id');

        $this->load->model("greetings_model");
        if ($greetings_id > 0) {
            $greetings_detail = $this->greetings_model->get_greetings_by_id($greetings_id);
            $greetings_detail = $greetings_detail[0];

            $this->response(array("status" => 200, "greetings_detail" => $greetings_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid greetings ID"), 200);
        }
    }

    function get_greetings_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $greetings_id_like = $this->get('greetings_id_like');
        $greetings_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("greetings_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->greetings_model->count_greetings($greetings_id_like, $greetings_template_like));
        header('link: _');
        $users = $this->greetings_model->get_greetings($greetings_id_like, $greetings_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_greeting_post()
    {
        if (!$this->post('greetings_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $greetings_id = $this->post('greetings_id');

        $this->load->model("greetings_model");
        if ($greetings_id > 0) {
            $this->greetings_model->delete_greeting($greetings_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the greeting"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid greetings ID"), 200);
        }
    }

    function add_new_greeting_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("greetings_model");
        $result = $this->greetings_model->store_greeting(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new greeting"), 200);
    }

    function update_greeting_post()
    {
        if (!$this->post('greetings_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $greetings_id = $this->post('greetings_id');

        $data_for_greetings = [];

        if ($this->post('template'))
            $data_for_greetings['template'] = $this->post('template');


        $this->load->model("greetings_model");
        if ($this->greetings_model->update_greeting($greetings_id, $data_for_greetings) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the greeting"), 200);
        }
    }
}
