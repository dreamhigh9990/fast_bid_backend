<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class url extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("urls_model");
    }

    function get_urls_count_get()
    {
        $count = $this->urls_model->count_urls();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_urls_detail_get()
    {
        if (!$this->get('urls_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $urls_id = $this->get('urls_id');

        $this->load->model("urls_model");
        if ($urls_id > 0) {
            $urls_detail = $this->urls_model->get_urls_by_id($urls_id);
            $urls_detail = $urls_detail[0];

            $this->response(array("status" => 200, "urls_detail" => $urls_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid urls ID"), 200);
        }
    }

    function get_urls_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $urls_id_like = $this->get('urls_id_like');
        $urls_url_like = $this->get('url_like');
        $urls_description_like = $this->get('description_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("urls_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->urls_model->count_urls($urls_id_like, $urls_url_like, $urls_description_like));
        header('link: _');
        $users = $this->urls_model->get_urls($urls_id_like, $urls_url_like, $urls_description_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_url_post()
    {
        if (!$this->post('urls_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $urls_id = $this->post('urls_id');

        $this->load->model("urls_model");
        if ($urls_id > 0) {
            $this->urls_model->delete_url($urls_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the url"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid urls ID"), 200);
        }
    }

    function add_new_url_post()
    {
        if (!$this->post('title')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("urls_model");
        $result = $this->urls_model->store_url(
            array(
                'title' => $this->post('title'),
                'url' => $this->post('url'),
                'description' => $this->post('description'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new url"), 200);
    }

    function update_url_post()
    {
        if (!$this->post('urls_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $urls_id = $this->post('urls_id');

        $data_for_urls = [];

        if ($this->post('title'))
            $data_for_urls['title'] = $this->post('title');
        if ($this->post('url'))
            $data_for_urls['url'] = $this->post('url');
        if ($this->post('description'))
            $data_for_urls['description'] = $this->post('description');


        $this->load->model("urls_model");
        if ($this->urls_model->update_url($urls_id, $data_for_urls) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the url"), 200);
        }
    }
}
