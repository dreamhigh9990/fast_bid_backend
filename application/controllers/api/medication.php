<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class medication extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("medications_model");
        $this->load->model("residents_model");
    }

    function get_medications_count_get()
    {
        $count = $this->medications_model->count_medications();
        $outstanding_count = $this->medications_model->count_medications(null, null, null, null, null, true);
        $overdue_count = $this->medications_model->count_medications(null, null, null, null, null, true, true);
        $this->response(array("count" => $count, "outstanding_count" => $outstanding_count, "overdue_count" => $overdue_count, "status" => 200), 200);
    }

    function get_outstanding_medications_count_get()
    {
        $scanRXCount = $this->medications_model->count_medications(null, null, null, null, 'Started');
        $uploadMedscomRXCount = $this->medications_model->count_medications(null, null, null, null, 'Scanned RX Chart');
        $medicationDispatchCount = $this->medications_model->count_medications(null, null, null, null, 'Uploaded to Medscom');
        $medicationReceiveCount = $this->medications_model->count_medications(null, null, null, null, 'Medication Dispatched');
        $updateLeeCareCount = $this->medications_model->count_medications(null, null, null, null, 'Medication Received');

        $this->response(array("scanRXCount" => $scanRXCount, "uploadMedscomRXCount" => $uploadMedscomRXCount, "medicationDispatchCount" => $medicationDispatchCount, "medicationReceiveCount" => $medicationReceiveCount, "updateLeeCareCount" => $updateLeeCareCount, "status" => 200), 200);
    }

    function get_medications_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $medications_id_like = $this->get('medications_id_like');
        $residents_id_like = $this->get('residents_id_like');
        $resident_name_like = $this->get('residents_name_like');
        $medication_text_like = $this->get('medication_text_like');
        $status_like = $this->get('status_like');
        $sort = $this->get('_sort');
        $order = $this->get('_order');
        $only_running = $this->get('only_running');
        $overdue = $this->get('overdue');

        if ($residents_id_like == 0) {
            $medications_id_like = null;
            $residents_id_like = null;
            $resident_name_like = null;
        }

        if (!$sort) {
            $sort = 'started_date';
            $order = 'DESC';
        }

        $this->load->model("medications_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->medications_model->count_medications($medications_id_like, $residents_id_like, $resident_name_like, $medication_text_like, $status_like, $only_running, $overdue));
        header('link: _');
        $medications = $this->medications_model->get_medications($medications_id_like, $residents_id_like, $resident_name_like, $medication_text_like, $status_like, $only_running, $overdue, $limit, $page * $limit, $sort, $order);
        $this->response($medications, 200);
    }

    function delete_medication_post()
    {
        if (!$this->post('medications_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $medications_id = $this->post('medications_id');

        $this->load->model("medications_model");
        if ($medications_id > 0) {
            $this->medications_model->delete_medications($medications_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the medication"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid medications ID"), 200);
        }
    }

    function add_new_medication_post()
    {
        if (!$this->post('residents_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $resident = $this->residents_model->get_residents_by_id($this->post('residents_id'));
        if (!$resident) {
            $this->response(array("status" => 400, "message" => "Invalid residents ID"), 200);
            return;
        }
        $resident = $resident[0];
        $this->load->model("medications_model");
        $result = $this->medications_model->store_medication(
            array(
                'residents_id' => $this->post('residents_id'),
                'residents_code' => $resident['code'],
                'residents_name' => $resident['name'],
                'medication_text' => $this->post('medication_text'),
                'rx_chart_file_path' => null,
                'status' => $this->post('status'),
                'started_date' => date("Y-m-d H:i:s"),
                'ended_date' => null,
                'started_by' => 'Administrator',
            )
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new medication", "result" => $result), 200);
    }

    function update_medication_post()
    {
        if (!$this->post('medications_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $medications_id = $this->post('medications_id');
        $data_for_medications = [];

        if ($this->post('status')) {
            $data_for_medications['status'] = $this->post('status');
        }
        if ($this->post('started_date')) {
            $data_for_medications['started_date'] = $this->post('started_date');
        }
        if ($this->post('is_ended')) {
            $data_for_medications['ended_date'] = date("Y-m-d H:i:s");
        }

        if (isset($_FILES['file'])) {
            $config['upload_path']  = './upload/chart/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'rx_chart_pdf');

            if (!$this->rx_chart_pdf->do_upload("file")) {
                $upload = FALSE;
            } else {
                $rx_chart_pdf_file = $this->rx_chart_pdf->data();
                $upload = TRUE;
            }
            if (!$upload) {
                $this->response(array("status" => 400, "message" => "Failed to upload the file"), 200);
                return;
            }
            $data_for_medications['rx_chart_file_path'] = $rx_chart_pdf_file['file_name'];
        }

        $this->load->model("medications_model");
        if ($this->medications_model->update_medications($medications_id, $data_for_medications) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the medication"), 200);
        }
    }

    function complete_medication_post()
    {
        if (!$this->post('medications_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $medications_id = $this->post('medications_id');
        $data_for_medications = [];

        $data_for_medications['status'] = "Updated LeeCare";
        $data_for_medications['ended_date'] = date("Y-m-d H:i:s");

        $this->load->model("medications_model");
        if ($this->medications_model->update_medications($medications_id, $data_for_medications) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully completed"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to complete the medication"), 200);
        }
    }
}
