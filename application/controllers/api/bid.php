<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class bid extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("bids_model");
        $this->load->model("accounts_model");
    }

    function get_bids_count_get()
    {
        $count = $this->bids_model->count_bids();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_today_bids_count_get()
    {
        $count = $this->bids_model->count_bids(null, null, null, false, true);
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_bids_detail_get()
    {
        if (!$this->get('bids_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $bids_id = $this->get('bids_id');

        $this->load->model("bids_model");
        if ($bids_id > 0) {
            $bids_detail = $this->bids_model->get_bids_by_id($bids_id);
            $bids_detail = $bids_detail[0];
            $bids_detail['rx_chart_files'] = $this->medications_model->get_rx_chart_files($bids_id);

            $this->response(array("status" => 200, "bids_detail" => $bids_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid bids ID"), 200);
        }
    }

    function get_bids_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $bids_id_like = $this->get('bids_id_like');
        $bids_proposal_like = $this->get('proposal_like');
        $bids_price_like = $this->get('price_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
        $only_new = $this->get('only_new');
        $accounts_user_name = $this->get('accounts_user_name');

        if ($accounts_user_name) {
            $this->accounts_model->update_account_by_user_name($accounts_user_name, ['active_time' => date('Y-m-d H:i:s')]);
        }

        $this->load->model("bids_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->bids_model->count_bids($bids_id_like, $bids_proposal_like, $bids_price_like, $only_new, false, $accounts_user_name));
        header('link: _');
        $users = $this->bids_model->get_bids($bids_id_like, $bids_proposal_like, $bids_price_like, $only_new, false, $accounts_user_name, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_bid_post()
    {
        if (!$this->post('bids_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $bids_id = $this->post('bids_id');

        $this->load->model("bids_model");
        if ($bids_id > 0) {
            $this->bids_model->delete_bid($bids_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the bid"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid bids ID"), 200);
        }
    }

    function add_new_bid_post()
    {
        if (!$this->post('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("bids_model");
        $result = $this->bids_model->store_bid(
            array(
                'jobs_id' => $this->post('jobs_id'),
                'users_id' => $this->post('users_id'),
                'accounts_user_name' => $this->post('accounts_user_name'),
                'proposal' => $this->post('proposal'),
                'price' => $this->post('price'),
                'period' => $this->post('period'),
                'created_at' => date('Y-m-d H:i:s')
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new bid"), 200);
    }

    function update_bid_get()
    {
        if (!$this->get('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $jobs_id = $this->get('jobs_id');

        $data_for_bids = [];

        if ($this->get('bid_at'))
            $data_for_bids['bid_at'] = $this->get('bid_at');

        $this->load->model("bids_model");
        if ($this->bids_model->update_bid($jobs_id, $data_for_bids) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the bid"), 200);
        }
    }
}
