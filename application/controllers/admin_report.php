<?php
class Admin_report extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/report';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('stores_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
        $stores_id = $this->uri->segment(3);

        $data['users'] = $this->users_model->get_users();
         
        //load the view
        $data['main_content'] = 'admin/report/list';
        $this->load->view('includes/template', $data);  
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('type', 'type', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
			if (empty($_FILES['photo']['name']))
			{
				$this->form_validation->set_rules('photo', 'Image', 'required');
			}
            if ($this->input->post('type') == 1) 
            {
                $this->form_validation->set_rules('store_name', 'store_name', 'required');
                $this->form_validation->set_rules('store_address', 'store_address', 'required');
                $this->form_validation->set_rules('store_phone', 'store_phone', 'required');
                $this->form_validation->set_rules('store_country', 'store_country', 'required');
                if (empty($_FILES['store_logo']['name']))
                {
                    $this->form_validation->set_rules('store_logo', 'Image', 'required');
                }
            }
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {
				if (isset($_FILES['photo'])) 
				{
					$config['upload_path']	= './upload/users/photo/';
					$config['overwrite']		= FALSE;
					$config['allowed_types']	= '*';
					$config['max_size']	= '50000';			
					$this->load->library('upload', $config, 'user_photo');
					
					if (!$this->user_photo->do_upload("photo")) {
						$upload = FALSE;
					} else {
						$attache_photo = $this->user_photo->data();
						$upload = TRUE;
					}
				}
                if (isset($_FILES['store_logo'])) 
                {
                    $config['upload_path']  = './upload/stores/photo/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config, 'store_logo');
                    
                    if (!$this->store_logo->do_upload("store_logo")) {
                        $upload = FALSE;
                    } else {
                        $attache_store_logo = $this->store_logo->data();
                        $upload = TRUE;
                    }
                }
                $data_for_users = array(
                    'email' => $this->input->post('email'),
					'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'type' => $this->input->post('type'),
                    'password' => MD5($this->input->post('password')),
					'photo' => $attache_photo["file_name"],
                );
				
				if ($upload) {
					//if the insert has returned true then we show the flash message
                    $last_inserted_id = $this->users_model->store_users($data_for_users);
					if($last_inserted_id >= 0){
                        if ($this->input->post('type') == 1) {
                            $data_for_stores = array(
                                'owner_id' => $last_inserted_id,
                                'name' => $this->input->post('store_name'),
                                'address' => $this->input->post('store_address'),
                                'phone' => $this->input->post('store_phone'),
                                'country' => $this->input->post('store_country'),
                                'description' => $this->input->post('store_description'),
                                'photo' => $attache_store_logo["file_name"],
                            );
                            if($this->stores_model->store_stores($data_for_stores)){
    						    $data['flash_message'] = TRUE; 
                            } else {
                                $data['flash_message'] = FALSE; 
                            }
                        } else {
                            $data['flash_message'] = TRUE; 
                        }
					}else{
						$data['flash_message'] = FALSE; 
					}
				} else {
					$data['flash_message'] = FALSE; 
				}
            } else {
                $data['flash_message'] = FALSE;
            }
        }
        //load the view
        $data['main_content'] = 'admin/users/add';
        $this->load->view('includes/template', $data);  
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('type', 'type', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->input->post('type') == 1) 
            {
                $this->form_validation->set_rules('store_name', 'store_name', 'required');
                $this->form_validation->set_rules('store_address', 'store_address', 'required');
                $this->form_validation->set_rules('store_phone', 'store_phone', 'required');
                $this->form_validation->set_rules('store_country', 'store_country', 'required');
            }
            if ($this->form_validation->run())
            {    
                $data_for_users = array(
                    'email' => $this->input->post('email'),
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'type' => $this->input->post('type'),
                    'password' => MD5($this->input->post('password')),
                );
                $data_for_stores = array(
                    'name' => $this->input->post('store_name'),
                    'address' => $this->input->post('store_address'),
                    'phone' => $this->input->post('store_phone'),
                    'country' => $this->input->post('store_country'),
                    'description' => $this->input->post('store_description'),
                    'photo' => $attache_store_logo["file_name"],
                );
				if (isset($_FILES['photo'])) 
				{
					$config['upload_path']	= './upload/users/photo/';
					$config['overwrite']		= FALSE;
					$config['allowed_types']	= '*';
					$config['max_size']	= '50000';			
					$this->load->library('upload', $config);
					
					if (!$this->upload->do_upload("photo")) {
						
					} else {
						$attache_photo = $this->upload->data();
						$data_for_users['photo'] = $attache_photo['file_name'];
					}
				}
                if (isset($_FILES['store_logo'])) 
                {
                    $config['upload_path']  = './upload/stores/photo/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload("store_logo")) {
                        
                    } else {
                        $attache_store_logo = $this->upload->data();
                        $data_for_stores['photo'] = $attache_store_logo['file_name'];
                    }
                }
				
				//if the insert has returned true then we show the flash message
				if($this->users_model->update_users($id, $data_for_users) == TRUE){
                    if ($this->input->post('type') == 1) {
                        if($this->stores_model->update_stores($id, $data_for_stores)){
                            $this->session->set_flashdata('flash_message', 'updated');
                        } else {
                            $this->session->set_flashdata('flash_message', 'not_updated');
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', 'updated');
                    }
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/users/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE;
            }
        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //product data 
        $data['users'] = $this->users_model->get_users_by_id($id);
        $data['stores'] = $this->stores_model->get_stores_by_owner_id($id);
        //load the view
        $data['main_content'] = 'admin/users/edit';
        $this->load->view('includes/template', $data);            

    }

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $this->users_model->delete_users($id);
        $this->stores_model->delete_stores_by_owner_id($id);
        redirect('admin/users');
    }
}
?>