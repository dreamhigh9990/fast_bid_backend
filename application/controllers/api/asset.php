<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class asset extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("assets_model");
    }

    /**
     * get the assets all list
     * function get_assets_get()
     * 
     * URL: api/asset/get_assets
     * 
     * @method: GET
     * 
     * @return {data:[], status:x}
     */
    function get_assets_get()
    {
        $assets = $this->assets_model->get_assets(1);
        $this->response(array("data" => $assets, "status" => 200), 200);
    }

    /**
     * delete the asset by id
     * URL: /api/asset/delete_asset
     * 
     * @method: POST
     * 
     * @param: int id
     * 
     * @return: message for the result
     */
    function delete_asset_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $id = $this->post('id');

        if ($id > 0) {
            $this->assets_model->delete_asset($id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the asset"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid asset ID"), 200);
        }
    }


    /**
     * Add a new asset
     * URL: /api/asset/add_asset
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function add_asset_post()
    {
        if (!$this->post('asset_name')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/asset/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'asset_photo');

            if (!$this->asset_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->asset_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        
        $postVal = $this->post();

        if($photo_path){
            $postVal['asset_img_url'] = $photo_path;
        }
        

        $result = $this->assets_model->store_asset(
            $postVal
        );

        if (!$result){
            $this->response(array("status" => 401, "message" => "Errors occured while saving on DB"), 200);
        }else{
            $this->response(array("status" => 200, "message" => "Successfully added new asset"), 200);
        }        
    }


    /**
     * Update asset
     * URL: /api/asset/update_asset
     * 
     * @method: POST
     * 
     * @param: 
     * 
     */
    function update_asset_post()
    {
        if (!$this->post('id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $id = $this->post('id');

        $upload = FALSE;
        if (isset($_FILES['photo'])) {
            $config['upload_path']  = './upload/asset/photo/';
            $config['overwrite']        = FALSE;
            $config['allowed_types']    = '*';
            $config['max_size'] = '50000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config, 'asset_photo');

            if (!$this->asset_photo->do_upload("photo")) {
                $upload = FALSE;
            } else {
                $attache_photo = $this->asset_photo->data();
                $upload = TRUE;
            }
        }

        $photo_path = "";
        if ($upload == TRUE) {
            $photo_path = $attache_photo["file_name"];
        }

        $postVal = $this->post();
        unset($postVal['id']);

        if($photo_path){
            $postVal['asset_img_url'] = $photo_path;
        }
        
        $result = $this->assets_model->update_asset(
            $id,
            $postVal
        );

        if (!$result)
            $this->response(array("status" => 401, "message" => "Errors occured while updating on DB"), 200);

        $this->response(array("status" => 200, "message" => "Successfully updated the asset"), 200);
    }
}
