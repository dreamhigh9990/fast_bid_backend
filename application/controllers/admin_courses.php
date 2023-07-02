<?php
class Admin_courses extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/courses';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('courses_model');
        $this->load->model('chapters_model');
        $this->load->model('books_model');
        $this->load->model('sentences_model');

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

        $config['base_url'] = base_url().'admin/courses';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $config['total_rows'] = $this->courses_model->count_all_courses();
        $data['courses'] = $this->courses_model->get_all_courses();

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/courses/list';
        $data['main_selected'] = true;
        $this->load->view('includes/template', $data);
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
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
                $data_for_courses = array(
					'name' => $this->input->post('name'),
                );
				
				//if the insert has returned true then we show the flash message
                $last_inserted_id = $this->courses_model->store_courses($data_for_courses);
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
        $data['main_courses'] = $this->courses_model->get_courses();
        $data['main_content'] = 'admin/courses/add';
        $data['main_selected'] = true;
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
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {    
                $upload = FALSE;
                if (isset($_FILES['image'])) 
                {
                    $config['upload_path']  = './upload/courses/images/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config, 'courses_photo');
                    
                    if (!$this->courses_photo->do_upload("image")) {
                        $upload = FALSE;
                    } else {
                        $attache_photo = $this->courses_photo->data();
                        $upload = TRUE;
                    }
                }
                $data_for_courses = array(
                    'name' => $this->input->post('name'),
                );

                if ($upload == TRUE)
                    $data_for_courses['image'] = $attache_photo["file_name"];
				
				//if the insert has returned true then we show the flash message
				if($this->courses_model->update_courses($id, $data_for_courses) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/courses/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE;
            }
        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
        $data['courses'] = $this->courses_model->get_courses_by_id($id);
        $data['main_courses'] = $this->courses_model->get_courses();

        //load the view
        $data['main_content'] = 'admin/courses/edit';
        $data['main_selected'] = true;
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
        $this->courses_model->delete_courses($id);
        redirect('admin/courses');
    }
}
?>