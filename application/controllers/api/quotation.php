<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class quotation extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("quotations_model");
    }

    function get_quotations_count_get()
    {
        $count = $this->quotations_model->count_quotations();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_quotations_detail_get()
    {
        if (!$this->get('quotations_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $quotations_id = $this->get('quotations_id');

        $this->load->model("quotations_model");
        if ($quotations_id > 0) {
            $quotations_detail = $this->quotations_model->get_quotations_by_id($quotations_id);
            $quotations_detail = $quotations_detail[0];

            $this->response(array("status" => 200, "quotations_detail" => $quotations_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid quotations ID"), 200);
        }
    }

    function get_quotations_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $quotations_id_like = $this->get('quotations_id_like');
        $quotations_template_like = $this->get('template_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("quotations_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->quotations_model->count_quotations($quotations_id_like, $quotations_template_like));
        header('link: _');
        $users = $this->quotations_model->get_quotations($quotations_id_like, $quotations_template_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_quotation_post()
    {
        if (!$this->post('quotations_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $quotations_id = $this->post('quotations_id');

        $this->load->model("quotations_model");
        if ($quotations_id > 0) {
            $this->quotations_model->delete_quotation($quotations_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the quotation"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid quotations ID"), 200);
        }
    }

    function add_new_quotation_post()
    {
        if (!$this->post('template')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("quotations_model");
        $result = $this->quotations_model->store_quotation(
            array(
                'template' => $this->post('template'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new quotation"), 200);
    }

    function update_quotation_post()
    {
        if (!$this->post('quotations_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $quotations_id = $this->post('quotations_id');

        $data_for_quotations = [];

        if ($this->post('template'))
            $data_for_quotations['template'] = $this->post('template');


        $this->load->model("quotations_model");
        if ($this->quotations_model->update_quotation($quotations_id, $data_for_quotations) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the quotation"), 200);
        }
    }
}
