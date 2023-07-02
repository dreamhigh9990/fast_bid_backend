<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class account extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("accounts_model");
    }

    function get_accounts_count_get()
    {
        $count = $this->accounts_model->count_accounts();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_accounts_detail_get()
    {
        if (!$this->get('accounts_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $accounts_id = $this->get('accounts_id');

        $this->load->model("accounts_model");
        if ($accounts_id > 0) {
            $accounts_detail = $this->accounts_model->get_accounts_by_id($accounts_id);
            $accounts_detail = $accounts_detail[0];

            $this->response(array("status" => 200, "accounts_detail" => $accounts_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid accounts ID"), 200);
        }
    }

    function get_accounts_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $accounts_id_like = $this->get('accounts_id_like');
        $accounts_user_name_like = $this->get('user_name_like');
        $accounts_full_name_like = $this->get('full_name_like');
        $accounts_email_like = $this->get('email_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("accounts_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->accounts_model->count_accounts($accounts_id_like, $accounts_user_name_like, $accounts_full_name_like, $accounts_email_like));
        header('link: _');
        $users = $this->accounts_model->get_accounts($accounts_id_like, $accounts_user_name_like, $accounts_full_name_like, $accounts_email_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_account_post()
    {
        if (!$this->post('accounts_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $accounts_id = $this->post('accounts_id');

        $this->load->model("accounts_model");
        if ($accounts_id > 0) {
            $this->accounts_model->delete_account($accounts_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the account"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid accounts ID"), 200);
        }
    }

    function add_new_account_post()
    {
        if (!$this->post('user_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("accounts_model");
        $result = $this->accounts_model->store_account(
            array(
                'user_name' => $this->post('user_name'),
                'email' => $this->post('email'),
                'full_name' => $this->post('full_name'),
                'type' => $this->post('type'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new account"), 200);
    }

    function update_account_post()
    {
        if (!$this->post('accounts_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $accounts_id = $this->post('accounts_id');

        $data_for_accounts = [];

        if ($this->post('user_name'))
            $data_for_accounts['user_name'] = $this->post('user_name');
        if ($this->post('email'))
            $data_for_accounts['email'] = $this->post('email1');
        if ($this->post('full_name'))
            $data_for_accounts['full_name'] = $this->post('full_name');
        if ($this->post('type'))
            $data_for_accounts['type'] = $this->post('type');
        if ($this->post('active_time'))
            $data_for_accounts['active_time'] = $this->post('active_time');


        $this->load->model("accounts_model");
        if ($this->accounts_model->update_account($accounts_id, $data_for_accounts) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the account"), 200);
        }
    }

    function update_account_by_user_name_post()
    {
        if (!$this->post('user_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $user_name = $this->post('user_name');
        $data_for_accounts = [];

        if ($this->post('user_name'))
            $data_for_accounts['user_name'] = $this->post('user_name');
        if ($this->post('email'))
            $data_for_accounts['email'] = $this->post('email1');
        if ($this->post('full_name'))
            $data_for_accounts['full_name'] = $this->post('full_name');
        if ($this->post('type'))
            $data_for_accounts['type'] = $this->post('type');
        if ($this->post('active_time'))
            $data_for_accounts['active_time'] = $this->post('active_time');


        $this->load->model("accounts_model");
        if ($this->accounts_model->update_account_by_user_name($user_name, $data_for_accounts) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the account"), 200);
        }
    }

    function update_account_by_user_name_get()
    {
        if (!$this->get('user_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $user_name = $this->get('user_name');
        $data_for_accounts = [];

        if ($this->get('user_name'))
            $data_for_accounts['user_name'] = $this->get('user_name');
        if ($this->get('email'))
            $data_for_accounts['email'] = $this->get('email1');
        if ($this->get('full_name'))
            $data_for_accounts['full_name'] = $this->get('full_name');
        if ($this->get('type'))
            $data_for_accounts['type'] = $this->get('type');
        if ($this->get('active_time'))
            $data_for_accounts['active_time'] = $this->get('active_time');


        $this->load->model("accounts_model");
        if ($this->accounts_model->update_account_by_user_name($user_name, $data_for_accounts) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the account"), 200);
        }
    }
}
