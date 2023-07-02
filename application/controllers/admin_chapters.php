<?php
class Admin_chapters extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/chapters';
 
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

        $config['base_url'] = base_url().'admin/chapters';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $books_id = $this->uri->segment(3);

        $config['total_rows'] = $this->chapters_model->count_chapters_old($books_id);
        $data['chapters'] = $this->chapters_model->get_chapters_old($books_id);

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/chapters/list';

        //side-menu highlight
        $books = $this->books_model->get_books_by_id($books_id);
        if ($books[0]['courses_id'] <= 0) {// individual course 
            $data['ibooks_selected']['book'] = $books_id;
            //$data['individual_selected']['course'] = $books[0]['courses_id'];
        } else {
            $data['main_selected']['book'] = $books_id;
            $data['main_selected']['course'] = $books[0]['courses_id'];
        }

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
                $data_for_chapters = array(
					'name' => $this->input->post('name'),
                    'books_id' => $this->uri->segment(4),
                );
				
				//if the insert has returned true then we show the flash message
                $last_inserted_id = $this->chapters_model->store_chapters($data_for_chapters);
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
        $data['main_content'] = 'admin/chapters/add';
        //side-menu highlight
        $books_id = $this->uri->segment(4);
        $books = $this->books_model->get_books_by_id($books_id);
        if ($books[0]['courses_id'] <= 0) {// individual course 
            $data['ibooks_selected']['book'] = $books_id;
            //$data['individual_selected']['course'] = $books[0]['courses_id'];
        } else {
            $data['main_selected']['book'] = $books_id;
            $data['main_selected']['course'] = $books[0]['courses_id'];
        }
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
                if (isset($_FILES['photo'])) 
                {
                    $config['upload_path']  = '../offers/upload/chapters/photo/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config, 'chapters_photo');
                    
                    if (!$this->chapters_photo->do_upload("photo")) {
                        $upload = FALSE;
                    } else {
                        $attache_photo = $this->chapters_photo->data();
                        $upload = TRUE;
                    }
                }
                $data_for_chapters = array(
                    'name' => $this->input->post('name'),
                );

                if ($upload == TRUE)
                    $data_for_chapters['image'] = $attache_photo["file_name"];
				
				//if the insert has returned true then we show the flash message
				if($this->chapters_model->update_chapters($id, $data_for_chapters) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/chapters/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE;
            }
        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
        $data['chapters'] = $this->chapters_model->get_chapters_by_id($id);

        //load the view
        $data['main_content'] = 'admin/chapters/edit';
        //side-menu highlight
        $books_id = $data['chapters'][0]['books_id'];
        $books = $this->books_model->get_books_by_id($books_id);
        if ($books[0]['courses_id'] <= 0) {// individual course 
            $data['ibooks_selected']['book'] = $books_id;
            //$data['individual_selected']['course'] = $books[0]['courses_id'];
        } else {
            $data['main_selected']['book'] = $books_id;
            $data['main_selected']['course'] = $books[0]['courses_id'];
        }
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
        $this->chapters_model->delete_chapters($id);
        // redirect('admin/chapters');
        // header("location:javascript://history.go(-1)");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>