<?php
class Admin_categories extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/categories';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');

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
        //pagination settings
        $config['per_page'] = 15;

        $config['base_url'] = base_url().'admin/categories';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        
        $config['total_rows'] = $this->categories_model->count_all_categories();
        $data['categories'] = $this->categories_model->get_all_categories();

        // get parent category name
        foreach ($data['categories'] as $key => $category) {
            
            if ($category['parent'] == 0) {
                $data['categories'][$key]['parent_category'] = "";
            } else {
                $temp = $this->categories_model->get_categories_by_id($category['parent']);
                $data['categories'][$key]['parent_category'] = $temp[0]['name'];
            }
        }
         
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/categories/list';
        $this->load->view('includes/template', $data);
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('parent', 'Parent Category', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
			// if (empty($_FILES['photo']['name']))
			// {
			// 	$this->form_validation->set_rules('photo', 'Image', 'required');
			// }
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {
                $data_for_categories = array(
                    'parent' => $this->input->post('parent'),
					'name' => $this->input->post('name'),
                );
				
				//if the insert has returned true then we show the flash message
                $last_inserted_id = $this->categories_model->store_categories($data_for_categories);
				if($last_inserted_id >= 0){
				    $data['flash_message'] = TRUE; 
				}else{
					$data['flash_message'] = FALSE; 
				}
            } else {
                $data['flash_message'] = FALSE;
            }
        }
        // load the view
        $data['main_categories'] = $this->categories_model->get_categories();
        // sort by level
        $array = array();
        $prefix = '';
        foreach ($data['main_categories'] as $key => $category) {
            if ($category['parent'] == 0) {
                $array = $this->add_sub_array($array, $data['main_categories'], $category, $prefix);
            }
        }
        $data['main_categories'] = $array;
        $data['main_content'] = 'admin/categories/add';
        $this->load->view('includes/template', $data);  
    }

    public function add_sub_array($array, $main_categories, $category, $prefix) {
        $category['title'] = $prefix . $category['name'];
        array_push($array, $category);
        foreach ($main_categories as $key => $sub_category) {
            if ($sub_category['parent'] == $category['categories_id']) {
//                array_push($array, $sub_category);
                $array = $this->add_sub_array($array, $main_categories, $sub_category, $prefix."&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
        return $array;
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
//            $this->form_validation->set_rules('parent', 'Parent Category', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {    
                if (isset($_FILES['photo'])) 
                {
                    $config['upload_path']  = '../offers/upload/categories/photo/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config, 'categories_photo');
                    
                    if (!$this->categories_photo->do_upload("photo")) {
                        $upload = FALSE;
                    } else {
                        $attache_photo = $this->categories_photo->data();
                        $upload = TRUE;
                    }
                }
                $data_for_categories = array(
                    'parent' => (int)$this->input->post('parent'),
                    'name' => $this->input->post('name'),
                );

                if ($upload == TRUE)
                    $data_for_categories['image'] = $attache_photo["file_name"];
				
				//if the insert has returned true then we show the flash message
				if($this->categories_model->update_categories($id, $data_for_categories) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/categories/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE;
            }
        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
        $data['categories'] = $this->categories_model->get_categories_by_id($id);
        $data['main_categories'] = $this->categories_model->get_categories();

        // sort by level
        $array = array();
        $prefix = '';
        foreach ($data['main_categories'] as $key => $category) {
            if ($category['parent'] == 0) {
                $array = $this->add_sub_array($array, $data['main_categories'], $category, $prefix);
            }
        }
        $data['main_categories'] = $array;

        //load the view
        $data['main_content'] = 'admin/categories/edit';
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
        $this->categories_model->delete_categories($id);
        redirect('admin/categories');
    }
}
?>