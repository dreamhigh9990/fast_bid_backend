<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class message extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("messages_model");
    }

    function get_messages_count_get()
    {
        $count = $this->messages_model->count_messages();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_unread_messages_count_get()
    {
        $count = $this->messages_model->count_unread_messages();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_messages_detail_get()
    {
        if (!$this->get('messages_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $messages_id = $this->get('messages_id');

        $this->load->model("messages_model");
        if ($messages_id > 0) {
            $messages_detail = $this->messages_model->get_messages_by_id($messages_id);
            $messages_detail = $messages_detail[0];

            $this->response(array("status" => 200, "messages_detail" => $messages_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid messages ID"), 200);
        }
    }

    function get_messages_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $messages_id_like = $this->get('messages_id_like');
        $message_like = $this->get('message_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("messages_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->messages_model->count_messages($messages_id_like, $message_like));
        header('link: _');
        $users = $this->messages_model->get_messages($messages_id_like, $message_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_message_post()
    {
        if (!$this->post('messages_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $messages_id = $this->post('messages_id');

        $this->load->model("messages_model");
        if ($messages_id > 0) {
            $this->messages_model->delete_message($messages_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the message"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid messages ID"), 200);
        }
    }

    function add_new_message_post()
    {
        if (!$this->post('user_name') || !$this->post('client_id') || !$this->post('message')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("messages_model");
        $result = $this->messages_model->store_message(
            array(
                'user_name' => $this->post('user_name'),
                'client_id' => $this->post('client_id'),
                'message' => $this->post('message'),
                'created_date' => date('Y-m-d H:i:s'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new message"), 200);
    }

    function add_new_message_get()
    {
        if (!$this->get('user_name') || !$this->get('client_id') || !$this->get('message')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $this->load->model("messages_model");
        $result = $this->messages_model->store_message(
            array(
                'user_name' => $this->get('user_name'),
                'client_id' => $this->get('client_id'),
                'message' => $this->get('message'),
                'created_date' => date('Y-m-d H:i:s'),
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new message"), 200);
    }

    function update_message_post()
    {
        if (!$this->post('messages_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $messages_id = $this->post('messages_id');

        $data_for_messages = [];

        if ($this->post('template'))
            $data_for_messages['template'] = $this->post('template');


        $this->load->model("messages_model");
        if ($this->messages_model->update_message($messages_id, $data_for_messages) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the message"), 200);
        }
    }

    function mark_all_read_post() 
    {
        $data_for_messages = [];
        $data_for_messages['read_date'] = date('Y-m-d H:i:s');


        $this->load->model("messages_model");
        if ($this->messages_model->update_all_message($data_for_messages) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the message"), 200);
        }
    }
}
