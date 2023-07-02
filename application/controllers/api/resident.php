<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class resident extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("residents_model");
        $this->load->model("medications_model");
    }

    function get_residents_count_get()
    {
        $count = $this->residents_model->count_residents();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_residents_detail_get()
    {
        if (!$this->get('residents_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $residents_id = $this->get('residents_id');

        $this->load->model("residents_model");
        if ($residents_id > 0) {
            $residents_detail = $this->residents_model->get_residents_by_id($residents_id);
            $residents_detail = $residents_detail[0];
            $residents_detail['rx_chart_files'] = $this->medications_model->get_rx_chart_files($residents_id);

            $this->response(array("status" => 200, "residents_detail" => $residents_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid residents ID"), 200);
        }
    }

    function get_residents_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $residents_id_like = $this->get('residents_id_like');
        $residents_name_like = $this->get('name_like');
        $residents_code_like = $this->get('code_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("residents_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->residents_model->count_residents($residents_id_like, $residents_name_like, $residents_code_like));
        header('link: _');
        $users = $this->residents_model->get_residents($residents_id_like, $residents_name_like, $residents_code_like, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function upload_csv_post()
    {
        if (isset($_FILES['csv'])) {
            $config['upload_path']  = './upload/csv/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'resident_csv');

            if (!$this->resident_csv->do_upload("csv")) {
                $upload = FALSE;
            } else {
                $csv_file = $this->resident_csv->data();
                $upload = TRUE;
            }
        } else {
            $this->response(array("status" => 401, "message" => "No CSV file."), 200);
        }

        if ($upload) {
            $file = fopen($csv_file['full_path'], "r");
            $i = 0;

            $csvArr = array();

            while (($file_data = fgetcsv($file, 1000, ",")) !== FALSE) {
                if ($i > 0) {
                    $csvArr[$i]['residents_id'] = $file_data[0];
                    $csvArr[$i]['code'] = $file_data[1];
                    $csvArr[$i]['name'] = $file_data[2];
                    $csvArr[$i]['room'] = $file_data[4];
                    $newDateString = date_format(date_create_from_format('d/m/Y', $file_data[5]), 'Y-m-d');
                    $csvArr[$i]['dob'] = $newDateString;
                }
                $i++;
            }
            fclose($file);

            $this->load->model("residents_model");
            $this->residents_model->empty_table();
            foreach ($csvArr as $user_data) {
                $result = $this->residents_model->store_resident($user_data);
            }
            $this->response(array("status" => 200, "message" => "Successfully imported the residents"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "CSV file coud not be imported."), 200);
        }
    }

    function delete_resident_post()
    {
        if (!$this->post('residents_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $residents_id = $this->post('residents_id');

        $this->load->model("residents_model");
        if ($residents_id > 0) {
            $this->residents_model->delete_residents($residents_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the resident"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid residents ID"), 200);
        }
    }

    function add_new_resident_post()
    {
        if (!$this->post('first_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/residents/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'resident_photo');

            if (!$this->resident_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->resident_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE)
            $photo_path = $attache_photo["file_name"];

        $this->load->model("residents_model");
        $result = $this->residents_model->store_residents(
            array(
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'gender' => $this->post('gender'),
                'dob' => $this->post('dob'),
                'room' => $this->post('room'),
                'doctor' => $this->post('doctor'),
                'photo' => $photo_path
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occured while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new resident"), 200);
    }

    function update_resident_post()
    {
        if (!$this->post('residents_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $residents_id = $this->post('residents_id');

        $upload = FALSE;
        if (isset($_FILES['image'])) {
            $config['upload_path']  = './upload/residents/images/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $this->load->library('upload', $config, 'residents_image');

            if (!$this->residents_image->do_upload("image")) {
                $upload = FALSE;
            } else {
                $attache_image = $this->residents_image->data();
                $upload = TRUE;
            }
        }
        $data_for_residents = [];

        if ($this->post('name'))
            $data_for_residents['name'] = $this->post('name');

        if ($upload == TRUE)
            $data_for_residents['image'] = $attache_image["file_name"];

        $this->load->model("residents_model");
        if ($this->residents_model->update_residents($residents_id, $data_for_residents) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the resident"), 200);
        }
    }
}
