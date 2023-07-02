<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class floorplan extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("floorplans_model");
    }

    /**
     * get the floorplans all list
     * function get_floorplans_get()
     * 
     * URL: api/floorplan/get_floorplans
     * 
     * @method: GET
     * 
     * @return {data:[], status:x}
     */
    function get_floorplans_get()
    {
        $floorplans = $this->floorplans_model->get_floorplans(1);
        $this->response(array("data" => $floorplans, "status" => 200), 200);
    }

    /**
     * delete the floorplan by id
     * URL: /api/floorplan/delete_floorplan
     * 
     * @method: POST
     * 
     * @param: int id
     * 
     * @return: message for the result
     */
    function delete_floorplan_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $floorplan_id = $this->post('id');

        if ($floorplan_id > 0) {
            $this->floorplans_model->delete_floorplan($floorplan_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the floorplan"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid floorplan ID"), 200);
        }
    }


    /**
     * Add a new floorplan
     * URL: /api/floorplan/add_floorplan
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function add_floorplan_post()
    {
        if (!$this->post('fp_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/floorplan/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'floorplan_photo');

            if (!$this->floorplan_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->floorplan_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        $postVal = $this->post();

        if($photo_path){
            $postVal['fp_image_url'] = $photo_path;
        }

        $result = $this->floorplans_model->store_floorplan(
            $postVal
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occured while saving on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully added new floorplan"), 200);
    }


    /**
     * Update floorplan
     * URL: /api/floorplan/update_floorplan
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function update_floorplan_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $id = $this->post('id');

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/floorplan/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'floorplan_photo');

            if (!$this->floorplan_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->floorplan_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        $postVal = $this->post();

        if($photo_path){
            $postVal['fp_image_url'] = $photo_path;
        }

        $result = $this->floorplans_model->update_floorplan(
            $id,
            $postVal
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occured while updating on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully updated the floorplan"), 200);
    }
}
