<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class template extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("templates_model");
    }

    function get_templates_count_get()
    {
        $count = $this->templates_model->count_templates();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_templates_detail_get()
    {
        if (!$this->get('templates_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $templates_id = $this->get('templates_id');

        $this->load->model("templates_model");
        if ($templates_id > 0) {
            $templates_detail = $this->templates_model->get_templates_by_id($templates_id);
            $templates_detail = $templates_detail[0];

            $this->response(array("status" => 200, "templates_detail" => $templates_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid templates ID"), 200);
        }
    }

    function get_templates_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $templates_id_like = $this->get('templates_id_like');
        $templates_name_like = $this->get('name_like');
        $templates_text_like = $this->get('text_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("templates_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->templates_model->count_templates($templates_id_like, $templates_name_like, $templates_text_like));
        header('link: _');
        $users = $this->templates_model->get_templates($templates_id_like, $templates_name_like, $templates_text_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_template_post()
    {
        if (!$this->post('templates_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $templates_id = $this->post('templates_id');

        $this->load->model("templates_model");
        if ($templates_id > 0) {
            $this->templates_model->delete_template($templates_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the template"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid templates ID"), 200);
        }
    }

    function add_new_template_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("templates_model");
        $result = $this->templates_model->store_template($this->post('template'));

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new template"), 200);
    }

    function update_template_post()
    {
        if (!$this->post('templates_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $templates_id = $this->post('templates_id');

        $data_for_templates = [];

        if ($this->post('template'))
            $data_for_templates = $this->post('template');


        $this->load->model("templates_model");
        if ($this->templates_model->update_template($templates_id, $data_for_templates) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the template"), 200);
        }
    }
}
