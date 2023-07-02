<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class sensor extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("sensors_model");
    }

    /**
     * get the sensors all list
     * function get_sensors_get()
     * 
     * URL: api/sensor/get_sensors
     * 
     * @method: GET
     * 
     * @return {data:[], status:x}
     */
    function get_sensors_get()
    {
        $sensors = $this->sensors_model->get_sensors(1);
        $this->response(array("data" => $sensors, "status" => 200), 200);
    }

    /**
     * delete the sensor by id
     * URL: /api/sensor/delete_sensor
     * 
     * @method: POST
     * 
     * @param: int id
     * 
     * @return: message for the result
     */
    function delete_sensor_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $id = $this->post('id');

        if ($id > 0) {
            $this->sensors_model->delete_sensor($id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the sensor"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid sensor ID"), 200);
        }
    }


    /**
     * Add a new sensor
     * URL: /api/sensor/add_sensor
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function add_sensor_post()
    {
        if (!$this->post('sensor_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/sensor/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'sensor_photo');

            if (!$this->sensor_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->sensor_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        
        $postVal = $this->post();

        if($photo_path){
            $postVal['sensor_img_url'] = $photo_path;
        }
        

        $result = $this->sensors_model->store_sensor(
            $postVal
        );

        if (!$result){
            $this->response(array("status" => 401, "message" => "Errors occured while saving on DB"), 200);
        }else{
            $this->response(array("status" => 200, "message" => "Successfully added new sensor"), 200);
        }        
    }


    /**
     * Update sensor
     * URL: /api/sensor/update_sensor
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function update_sensor_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $id = $this->post('id');

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/sensor/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'sensor_photo');

            if (!$this->sensor_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->sensor_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        $postVal = $this->post();

        if($photo_path){
            $postVal['sensor_img_url'] = $photo_path;
        }
        
        $result = $this->sensors_model->update_sensor(
            $id,
            $postVal
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occured while updating on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully updated the sensor"), 200);
    }
}
